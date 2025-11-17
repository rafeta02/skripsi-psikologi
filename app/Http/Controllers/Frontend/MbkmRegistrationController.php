<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmRegistrationRequest;
use App\Http\Requests\StoreMbkmRegistrationRequest;
use App\Http\Requests\UpdateMbkmRegistrationRequest;
use App\Models\Application;
use App\Models\Dosen;
use App\Models\Keilmuan;
use App\Models\Mahasiswa;
use App\Models\MbkmGroupMember;
use App\Models\MbkmRegistration;
use App\Models\ResearchGroup;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MbkmRegistrationController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('mbkm_registration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistrations = MbkmRegistration::with(['application', 'research_group', 'preference_supervision', 'theme', 'created_by', 'media'])->get();

        return view('frontend.mbkmRegistrations.index', compact('mbkmRegistrations'));
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_registration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessMbkmRegistration(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.mbkm-registrations.index')
                ->with('error', $access['message']);
        }

        $research_groups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::all()->mapWithKeys(function ($mahasiswa) {
            return [$mahasiswa->id => $mahasiswa->nim . ' - ' . $mahasiswa->nama];
        })->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.mbkmRegistrations.create', compact('preference_supervisions', 'research_groups', 'themes', 'mahasiswas'));
    }

    public function store(StoreMbkmRegistrationRequest $request)
    {
        // Check if student can create new application (ensure only 1 active)
        $formAccessService = new FormAccessService();
        $canCreate = $formAccessService->canAccessMbkmRegistration(auth()->user()->mahasiswa_id);

        if (!$canCreate['allowed']) {
            return redirect()->route('frontend.mbkm-registrations.index')
                ->with('error', $canCreate['message']);
        }

        // Step 1: Create Application first
        $application = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => 'mbkm',
            'stage' => 'registration',
            'status' => 'submitted',
            'submitted_at' => now()->format('d-m-Y H:i:s'),
        ]);

        // Step 2: Create MbkmRegistration with the application_id
        $data = $request->all();
        $data['application_id'] = $application->id;
        
        $mbkmRegistration = MbkmRegistration::create($data);

        // Step 3: Create Group Members
        if ($request->has('group_members')) {
            foreach ($request->input('group_members', []) as $member) {
                if (!empty($member['mahasiswa_id']) && !empty($member['role'])) {
                    MbkmGroupMember::create([
                        'mbkm_registration_id' => $mbkmRegistration->id,
                        'mahasiswa_id' => $member['mahasiswa_id'],
                        'role' => $member['role'],
                    ]);
                }
            }
        }

        // Step 4: Upload files with custom naming using FileNamingTrait
        foreach ($request->input('khs_all', []) as $file) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)), 
                'khs_all'
            );
        }

        if ($request->input('krs_latest', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('krs_latest'))), 
                'krs_latest'
            );
        }

        if ($request->input('spp', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('spp'))), 
                'spp'
            );
        }

        if ($request->input('proposal_mbkm', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('proposal_mbkm'))), 
                'proposal_mbkm'
            );
        }

        if ($request->input('recognition_form', false)) {
            $mbkmRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('recognition_form'))), 
                'recognition_form'
            );
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mbkmRegistration->id]);
        }

        return redirect()->route('frontend.mbkm-registrations.index');
    }

    public function edit(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $research_groups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::all()->mapWithKeys(function ($mahasiswa) {
            return [$mahasiswa->id => $mahasiswa->nim . ' - ' . $mahasiswa->nama];
        })->prepend(trans('global.pleaseSelect'), '');

        $mbkmRegistration->load('application', 'research_group', 'preference_supervision', 'theme', 'created_by', 'groupMembers.mahasiswa');

        return view('frontend.mbkmRegistrations.edit', compact('mbkmRegistration', 'preference_supervisions', 'research_groups', 'themes', 'mahasiswas'));
    }

    public function update(UpdateMbkmRegistrationRequest $request, MbkmRegistration $mbkmRegistration)
    {
        $mbkmRegistration->update($request->all());
        
        // If status was revision, change it back to submitted and clear revision_notes
        if ($mbkmRegistration->application->status == 'revision') {
            $mbkmRegistration->application->update(['status' => 'submitted']);
            $mbkmRegistration->update(['revision_notes' => null]);
        }
        
        // If status was approved but supervisor rejected, change back to submitted
        if ($mbkmRegistration->application->status == 'approved') {
            // Check if supervisor rejected the assignment
            $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $mbkmRegistration->application_id)
                ->where('role', 'supervisor')
                ->where('status', 'rejected')
                ->first();
            
            if ($supervisorAssignment) {
                // Delete the rejected assignment
                $supervisorAssignment->delete();
                
                // Change application status back to submitted
                $mbkmRegistration->application->update(['status' => 'submitted']);
                
                // Clear approval date (preference_supervision_id stays as it was originally chosen by student)
                $mbkmRegistration->update([
                    'approval_date' => null
                ]);
            }
        }

        // Update Group Members
        // Delete existing members and recreate
        $mbkmRegistration->groupMembers()->delete();
        
        if ($request->has('group_members')) {
            foreach ($request->input('group_members', []) as $member) {
                if (!empty($member['mahasiswa_id']) && !empty($member['role'])) {
                    MbkmGroupMember::create([
                        'mbkm_registration_id' => $mbkmRegistration->id,
                        'mahasiswa_id' => $member['mahasiswa_id'],
                        'role' => $member['role'],
                    ]);
                }
            }
        }

        // Handle KHS files
        if (count($mbkmRegistration->khs_all) > 0) {
            foreach ($mbkmRegistration->khs_all as $media) {
                if (! in_array($media->file_name, $request->input('khs_all', []))) {
                    $media->delete();
                }
            }
        }
        $media = $mbkmRegistration->khs_all->pluck('file_name')->toArray();
        foreach ($request->input('khs_all', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $mbkmRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)), 
                    'khs_all'
                );
            }
        }

        if ($request->input('krs_latest', false)) {
            if (! $mbkmRegistration->krs_latest || $request->input('krs_latest') !== $mbkmRegistration->krs_latest->file_name) {
                if ($mbkmRegistration->krs_latest) {
                    $mbkmRegistration->krs_latest->delete();
                }
                $mbkmRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('krs_latest'))), 
                    'krs_latest'
                );
            }
        } elseif ($mbkmRegistration->krs_latest) {
            $mbkmRegistration->krs_latest->delete();
        }

        if ($request->input('spp', false)) {
            if (! $mbkmRegistration->spp || $request->input('spp') !== $mbkmRegistration->spp->file_name) {
                if ($mbkmRegistration->spp) {
                    $mbkmRegistration->spp->delete();
                }
                $mbkmRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('spp'))), 
                    'spp'
                );
            }
        } elseif ($mbkmRegistration->spp) {
            $mbkmRegistration->spp->delete();
        }

        if ($request->input('proposal_mbkm', false)) {
            if (! $mbkmRegistration->proposal_mbkm || $request->input('proposal_mbkm') !== $mbkmRegistration->proposal_mbkm->file_name) {
                if ($mbkmRegistration->proposal_mbkm) {
                    $mbkmRegistration->proposal_mbkm->delete();
                }
                $mbkmRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('proposal_mbkm'))), 
                    'proposal_mbkm'
                );
            }
        } elseif ($mbkmRegistration->proposal_mbkm) {
            $mbkmRegistration->proposal_mbkm->delete();
        }

        if ($request->input('recognition_form', false)) {
            if (! $mbkmRegistration->recognition_form || $request->input('recognition_form') !== $mbkmRegistration->recognition_form->file_name) {
                if ($mbkmRegistration->recognition_form) {
                    $mbkmRegistration->recognition_form->delete();
                }
                $mbkmRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('recognition_form'))), 
                    'recognition_form'
                );
            }
        } elseif ($mbkmRegistration->recognition_form) {
            $mbkmRegistration->recognition_form->delete();
        }

        return redirect()->route('frontend.mbkm-registrations.index');
    }

    public function show(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistration->load('application', 'research_group', 'preference_supervision', 'theme', 'created_by', 'groupMembers.mahasiswa');

        return view('frontend.mbkmRegistrations.show', compact('mbkmRegistration'));
    }

    public function destroy(MbkmRegistration $mbkmRegistration)
    {
        abort_if(Gate::denies('mbkm_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmRegistration->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmRegistrationRequest $request)
    {
        $mbkmRegistrations = MbkmRegistration::find(request('ids'));

        foreach ($mbkmRegistrations as $mbkmRegistration) {
            $mbkmRegistration->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mbkm_registration_create') && Gate::denies('mbkm_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MbkmRegistration();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
