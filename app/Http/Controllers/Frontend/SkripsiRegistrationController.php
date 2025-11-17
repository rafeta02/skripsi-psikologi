<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiRegistrationRequest;
use App\Http\Requests\StoreSkripsiRegistrationRequest;
use App\Http\Requests\UpdateSkripsiRegistrationRequest;
use App\Models\Application;
use App\Models\Dosen;
use App\Models\Keilmuan;
use App\Models\SkripsiRegistration;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SkripsiRegistrationController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('skripsi_registration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiRegistrations = SkripsiRegistration::with(['application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by', 'media'])->get();

        return view('frontend.skripsiRegistrations.index', compact('skripsiRegistrations'));
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_registration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiRegistration(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-registrations.index')
                ->with('error', $access['message']);
        }

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.skripsiRegistrations.create', compact('preference_supervisions', 'themes', 'tps_lecturers'));
    }

    public function store(StoreSkripsiRegistrationRequest $request)
    {
        // Check if student can create new application (ensure only 1 active)
        $formAccessService = new FormAccessService();
        $canCreate = $formAccessService->canAccessSkripsiRegistration(auth()->user()->mahasiswa_id);

        if (!$canCreate['allowed']) {
            return redirect()->route('frontend.skripsi-registrations.index')
                ->with('error', $canCreate['message']);
        }

        // Step 1: Create Application first
        $application = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => 'skripsi',
            'stage' => 'registration',
            'status' => 'submitted',
            'submitted_at' => now()->format('d-m-Y H:i:s'),
        ]);

        // Step 2: Create SkripsiRegistration with the application_id
        $data = $request->all();
        $data['application_id'] = $application->id;
        
        $skripsiRegistration = SkripsiRegistration::create($data);

        // Upload KHS files with custom naming
        foreach ($request->input('khs_all', []) as $file) {
            $skripsiRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)), 
                'khs_all'
            );
        }

        // Upload KRS file with custom naming
        if ($request->input('krs_latest', false)) {
            $skripsiRegistration->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('krs_latest'))), 
                'krs_latest'
            );
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiRegistration->id]);
        }

        return redirect()->route('frontend.skripsi-registrations.index');
    }

    public function edit(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiRegistration->load('application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by');

        return view('frontend.skripsiRegistrations.edit', compact('preference_supervisions', 'skripsiRegistration', 'themes', 'tps_lecturers'));
    }

    public function update(UpdateSkripsiRegistrationRequest $request, SkripsiRegistration $skripsiRegistration)
    {
        $skripsiRegistration->update($request->all());
        
        // If status was revision, change it back to submitted and clear revision_notes
        if ($skripsiRegistration->application->status == 'revision') {
            $skripsiRegistration->application->update(['status' => 'submitted']);
            $skripsiRegistration->update(['revision_notes' => null]);
        }
        
        // If status was approved but supervisor rejected, change back to submitted
        if ($skripsiRegistration->application->status == 'approved') {
            // Check if supervisor rejected the assignment
            $supervisorAssignment = \App\Models\ApplicationAssignment::where('application_id', $skripsiRegistration->application_id)
                ->where('role', 'supervisor')
                ->where('status', 'rejected')
                ->first();
            
            if ($supervisorAssignment) {
                // Delete the rejected assignment
                $supervisorAssignment->delete();
                
                // Change application status back to submitted
                $skripsiRegistration->application->update(['status' => 'submitted']);
                
                // Clear the assigned supervisor
                $skripsiRegistration->update([
                    'assigned_supervisor_id' => null,
                    'approval_date' => null
                ]);
            }
        }

        if (count($skripsiRegistration->khs_all) > 0) {
            foreach ($skripsiRegistration->khs_all as $media) {
                if (! in_array($media->file_name, $request->input('khs_all', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiRegistration->khs_all->pluck('file_name')->toArray();
        foreach ($request->input('khs_all', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                // Upload new KHS file with custom naming
                $skripsiRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)), 
                    'khs_all'
                );
            }
        }

        if ($request->input('krs_latest', false)) {
            if (! $skripsiRegistration->krs_latest || $request->input('krs_latest') !== $skripsiRegistration->krs_latest->file_name) {
                if ($skripsiRegistration->krs_latest) {
                    $skripsiRegistration->krs_latest->delete();
                }
                // Upload new KRS file with custom naming
                $skripsiRegistration->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('krs_latest'))), 
                    'krs_latest'
                );
            }
        } elseif ($skripsiRegistration->krs_latest) {
            $skripsiRegistration->krs_latest->delete();
        }

        return redirect()->route('frontend.skripsi-registrations.index');
    }

    public function show(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiRegistration->load('application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by');

        return view('frontend.skripsiRegistrations.show', compact('skripsiRegistration'));
    }

    public function destroy(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiRegistration->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiRegistrationRequest $request)
    {
        $skripsiRegistrations = SkripsiRegistration::find(request('ids'));

        foreach ($skripsiRegistrations as $skripsiRegistration) {
            $skripsiRegistration->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_registration_create') && Gate::denies('skripsi_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiRegistration();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
