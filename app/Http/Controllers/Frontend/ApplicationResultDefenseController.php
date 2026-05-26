<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultDefenseRequest;
use App\Http\Requests\StoreApplicationResultDefenseRequest;
use App\Http\Requests\UpdateApplicationResultDefenseRequest;
use App\Models\Application;
use App\Models\ApplicationResultDefense;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ApplicationResultDefenseController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_result_defense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $applicationIds = Application::where('mahasiswa_id', $mahasiswaId)->pluck('id');

        $applicationResultDefenses = ApplicationResultDefense::with(['application'])
            ->whereIn('application_id', $applicationIds)
            ->orderByDesc('created_at')
            ->get();

        $formAccessService = new FormAccessService();
        $canCreate = $formAccessService->canAccessDefenseResult($mahasiswaId);

        return view('frontend.applicationResultDefenses.index', compact('applicationResultDefenses', 'canCreate'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessDefenseResult($mahasiswaId);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-defenses.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.applicationResultDefenses.create', compact('activeApplication'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('application_result_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessDefenseResult($mahasiswaId);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-defenses.index')
                ->with('error', $access['message']);
        }

        $rules = [
            'application_id' => 'required|exists:applications,id',
            'result' => 'required|in:passed,revision,failed',
            'note' => 'nullable|string|max:5000',
            'revision_deadline' => 'nullable|required_if:result,revision|date',
            'report_document' => 'required|array|min:1',
            'report_document.*' => 'file|mimes:pdf|max:10240',
            'attendance_document' => 'required|file|mimes:pdf|max:10240',
            'revision_approval_sheet' => 'nullable|array',
            'revision_approval_sheet.*' => 'file|mimes:pdf|max:10240',
            'form_document' => 'nullable|array',
            'form_document.*' => 'file|mimes:pdf|max:10240',
            'latest_script' => 'nullable|file|mimes:pdf|max:20480',
            'documentation' => 'nullable|array',
            'documentation.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'certificate_document' => 'nullable|file|mimes:pdf|max:10240',
            'publication_document' => 'nullable|file|mimes:pdf|max:10240',
        ];

        if ($request->input('result') === 'revision') {
            $rules['revision_approval_sheet'] = 'required|array|min:1';
        }

        $validated = $request->validate($rules);

        if ((int) $validated['application_id'] !== (int) $access['application']->id) {
            abort(403, 'Aplikasi tidak valid.');
        }

        $applicationResultDefense = ApplicationResultDefense::create([
            'application_id' => $validated['application_id'],
            'result' => $validated['result'],
            'note' => $validated['note'] ?? null,
            'revision_deadline' => $validated['revision_deadline'] ?? null,
        ]);

        foreach ($request->file('report_document', []) as $file) {
            $applicationResultDefense->addMedia($file)->toMediaCollection('report_document');
        }

        $applicationResultDefense->addMedia($request->file('attendance_document'))
            ->toMediaCollection('attendance_document');

        foreach ($request->file('revision_approval_sheet', []) as $file) {
            $applicationResultDefense->addMedia($file)->toMediaCollection('revision_approval_sheet');
        }

        if ($request->hasFile('form_document')) {
            foreach ($request->file('form_document') as $file) {
                $applicationResultDefense->addMedia($file)->toMediaCollection('form_document');
            }
        }

        if ($request->hasFile('latest_script')) {
            $applicationResultDefense->addMedia($request->file('latest_script'))
                ->toMediaCollection('latest_script');
        }

        foreach ($request->file('documentation', []) as $file) {
            $applicationResultDefense->addMedia($file)->toMediaCollection('documentation');
        }

        if ($request->hasFile('certificate_document')) {
            $applicationResultDefense->addMedia($request->file('certificate_document'))
                ->toMediaCollection('certificate_document');
        }

        if ($request->hasFile('publication_document')) {
            $applicationResultDefense->addMedia($request->file('publication_document'))
                ->toMediaCollection('publication_document');
        }

        $applicationResultDefense->syncApplicationStatus();

        $message = match ($validated['result']) {
            'passed' => 'Laporan hasil sidang berhasil dikirim. Menunggu validasi admin sebelum penilaian dosen.',
            'revision' => 'Laporan hasil sidang (revisi) berhasil dikirim. Menunggu validasi admin.',
            default => 'Laporan hasil sidang berhasil dikirim.',
        };

        return redirect()->route('frontend.application-result-defenses.index')
            ->with('success', $message);
    }

    public function edit(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->load('application');

        return view('frontend.applicationResultDefenses.edit', compact('applicationResultDefense'));
    }

    public function update(UpdateApplicationResultDefenseRequest $request, ApplicationResultDefense $applicationResultDefense)
    {
        $applicationResultDefense->update($request->all());

        if (count($applicationResultDefense->documentation) > 0) {
            foreach ($applicationResultDefense->documentation as $media) {
                if (! in_array($media->file_name, $request->input('documentation', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultDefense->documentation->pluck('file_name')->toArray();
        foreach ($request->input('documentation', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'documentation'
                );
            }
        }

        if (count($applicationResultDefense->invitation_document) > 0) {
            foreach ($applicationResultDefense->invitation_document as $media) {
                if (! in_array($media->file_name, $request->input('invitation_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultDefense->invitation_document->pluck('file_name')->toArray();
        foreach ($request->input('invitation_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'invitation_document'
                );
            }
        }

        if (count($applicationResultDefense->feedback_document) > 0) {
            foreach ($applicationResultDefense->feedback_document as $media) {
                if (! in_array($media->file_name, $request->input('feedback_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultDefense->feedback_document->pluck('file_name')->toArray();
        foreach ($request->input('feedback_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'feedback_document'
                );
            }
        }

        if ($request->input('minutes_document', false)) {
            if (! $applicationResultDefense->minutes_document || $request->input('minutes_document') !== $applicationResultDefense->minutes_document->file_name) {
                if ($applicationResultDefense->minutes_document) {
                    $applicationResultDefense->minutes_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('minutes_document'))),
                    'minutes_document'
                );
            }
        } elseif ($applicationResultDefense->minutes_document) {
            $applicationResultDefense->minutes_document->delete();
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultDefense->latest_script || $request->input('latest_script') !== $applicationResultDefense->latest_script->file_name) {
                if ($applicationResultDefense->latest_script) {
                    $applicationResultDefense->latest_script->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                    'latest_script'
                );
            }
        } elseif ($applicationResultDefense->latest_script) {
            $applicationResultDefense->latest_script->delete();
        }

        if ($request->input('approval_page', false)) {
            if (! $applicationResultDefense->approval_page || $request->input('approval_page') !== $applicationResultDefense->approval_page->file_name) {
                if ($applicationResultDefense->approval_page) {
                    $applicationResultDefense->approval_page->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('approval_page'))),
                    'approval_page'
                );
            }
        } elseif ($applicationResultDefense->approval_page) {
            $applicationResultDefense->approval_page->delete();
        }

        if (count($applicationResultDefense->report_document) > 0) {
            foreach ($applicationResultDefense->report_document as $media) {
                if (! in_array($media->file_name, $request->input('report_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultDefense->report_document->pluck('file_name')->toArray();
        foreach ($request->input('report_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'report_document'
                );
            }
        }

        if (count($applicationResultDefense->revision_approval_sheet) > 0) {
            foreach ($applicationResultDefense->revision_approval_sheet as $media) {
                if (! in_array($media->file_name, $request->input('revision_approval_sheet', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultDefense->revision_approval_sheet->pluck('file_name')->toArray();
        foreach ($request->input('revision_approval_sheet', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'revision_approval_sheet'
                );
            }
        }

        if ($request->input('attendance_document', false)) {
            if (! $applicationResultDefense->attendance_document || $request->input('attendance_document') !== $applicationResultDefense->attendance_document->file_name) {
                if ($applicationResultDefense->attendance_document) {
                    $applicationResultDefense->attendance_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('attendance_document'))),
                    'attendance_document'
                );
            }
        } elseif ($applicationResultDefense->attendance_document) {
            $applicationResultDefense->attendance_document->delete();
        }

        if ($request->input('form_document', false)) {
            if (! $applicationResultDefense->form_document || $request->input('form_document') !== $applicationResultDefense->form_document->file_name) {
                if ($applicationResultDefense->form_document) {
                    $applicationResultDefense->form_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('form_document'))),
                    'form_document'
                );
            }
        } elseif ($applicationResultDefense->form_document) {
            $applicationResultDefense->form_document->delete();
        }

        if ($request->input('certificate_document', false)) {
            if (! $applicationResultDefense->certificate_document || $request->input('certificate_document') !== $applicationResultDefense->certificate_document->file_name) {
                if ($applicationResultDefense->certificate_document) {
                    $applicationResultDefense->certificate_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('certificate_document'))),
                    'certificate_document'
                );
            }
        } elseif ($applicationResultDefense->certificate_document) {
            $applicationResultDefense->certificate_document->delete();
        }

        if ($request->input('publication_document', false)) {
            if (! $applicationResultDefense->publication_document || $request->input('publication_document') !== $applicationResultDefense->publication_document->file_name) {
                if ($applicationResultDefense->publication_document) {
                    $applicationResultDefense->publication_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('publication_document'))),
                    'publication_document'
                );
            }
        } elseif ($applicationResultDefense->publication_document) {
            $applicationResultDefense->publication_document->delete();
        }

        return redirect()->route('frontend.application-result-defenses.index');
    }

    public function show(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->load('application', 'scores');
        $applicationResultDefense->syncApplicationStatus();
        $applicationResultDefense->loadMedia(
            'report_document',
            'attendance_document',
            'revision_approval_sheet',
            'latest_script',
            'form_document',
            'documentation',
            'certificate_document',
            'publication_document'
        );

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if ($mahasiswaId && $applicationResultDefense->application?->mahasiswa_id !== $mahasiswaId) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        return view('frontend.applicationResultDefenses.show', compact('applicationResultDefense'));
    }

    public function destroy(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationResultDefenseRequest $request)
    {
        $applicationResultDefenses = ApplicationResultDefense::find(request('ids'));

        foreach ($applicationResultDefenses as $applicationResultDefense) {
            $applicationResultDefense->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_result_defense_create') && Gate::denies('application_result_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationResultDefense();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function printScore(ApplicationResultDefense $applicationResultDefense)
    {
        // Check if user is authorized - either admin or the student who owns this defense result
        $user = auth()->user();
        
        // Load relationships
        $applicationResultDefense->load([
            'application.mahasiswa',
            'scores.examiner'
        ]);
        
        // Check authorization - must be the student who owns this result or have show permission
        if (!Gate::allows('application_result_defense_show')) {
            if (!$user->mahasiswa || 
                $applicationResultDefense->application->mahasiswa_id !== $user->mahasiswa->id) {
                abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
            }
        }
        
        return view('frontend.applicationResultDefenses.print-score', compact('applicationResultDefense'));
    }
}
