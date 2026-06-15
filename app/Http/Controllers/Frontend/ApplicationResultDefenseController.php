<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultDefenseRequest;
use App\Http\Requests\StoreApplicationResultDefenseRequest;
use App\Http\Requests\UpdateApplicationResultDefenseRequest;
use App\Models\Application;
use App\Models\ApplicationResultDefense;
use App\Services\DefenseScoringService;
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

    public function store(StoreApplicationResultDefenseRequest $request)
    {
        abort_if(Gate::denies('application_result_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessDefenseResult($mahasiswaId);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-defenses.index')
                ->with('error', $access['message']);
        }

        if ((int) $request->input('application_id') !== (int) $access['application']->id) {
            abort(403, 'Aplikasi tidak valid.');
        }

        $applicationResultDefense = ApplicationResultDefense::create([
            'application_id' => $request->input('application_id'),
            'final_title' => $request->input('final_title'),
            'result' => $request->input('result'),
            'note' => $request->input('note'),
            'revision_deadline' => $request->input('revision_deadline'),
        ]);

        $this->attachResultDefenseDocuments($applicationResultDefense, $request);

        app(DefenseScoringService::class)->linkScoresToResultDefense($applicationResultDefense);
        $applicationResultDefense->syncApplicationStatus();

        $message = match ($request->input('result')) {
            'passed' => 'Laporan hasil sidang (lulus tanpa revisi) berhasil dikirim. Menunggu validasi admin.',
            'revision' => 'Laporan hasil sidang (lulus dengan revisi) berhasil dikirim. Menunggu validasi admin.',
            'failed' => 'Laporan hasil sidang (tidak lulus) berhasil dikirim. Menunggu validasi admin.',
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
        $applicationResultDefense->update($request->only([
            'final_title',
            'result',
            'note',
            'revision_deadline',
        ]));

        $this->syncSingleDocument($applicationResultDefense, $request, 'title_change_form');
        $this->syncSingleDocument($applicationResultDefense, $request, 'minutes_document');
        $this->syncSingleDocument($applicationResultDefense, $request, 'latest_script');
        $this->syncSingleDocument($applicationResultDefense, $request, 'approval_page');
        $this->syncSingleDocument($applicationResultDefense, $request, 'revision_approval_sheet');
        $this->syncMultipleDocuments($applicationResultDefense, $request, 'documentation');
        $this->syncMultipleDocuments($applicationResultDefense, $request, 'invitation_document');
        $this->syncMultipleDocuments($applicationResultDefense, $request, 'feedback_document');

        $applicationResultDefense->syncApplicationStatus();

        return redirect()->route('frontend.application-result-defenses.index');
    }

    public function show(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->load('application', 'scores');
        $applicationResultDefense->syncApplicationStatus();

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
        $user = auth()->user();

        $applicationResultDefense->load([
            'application.mahasiswa',
            'scores.examiner',
        ]);

        if (!Gate::allows('application_result_defense_show')) {
            if (!$user->mahasiswa ||
                $applicationResultDefense->application->mahasiswa_id !== $user->mahasiswa->id) {
                abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
            }
        }

        return view('frontend.applicationResultDefenses.print-score', compact('applicationResultDefense'));
    }

    protected function attachResultDefenseDocuments(ApplicationResultDefense $record, Request $request): void
    {
        $singleFileFields = [
            'title_change_form',
            'minutes_document',
            'latest_script',
            'approval_page',
            'revision_approval_sheet',
        ];

        foreach ($singleFileFields as $field) {
            if ($request->hasFile($field)) {
                $record->addMedia($request->file($field))->toMediaCollection($field);
                continue;
            }

            if ($request->input($field)) {
                $filePath = storage_path('tmp/uploads/' . basename($request->input($field)));
                if (is_file($filePath)) {
                    $record->addMediaWithCustomName($filePath, $field);
                }
            }
        }

        foreach (['documentation', 'invitation_document', 'feedback_document'] as $field) {
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $record->addMedia($file)->toMediaCollection($field);
                }
                continue;
            }

            foreach ($request->input($field, []) as $fileName) {
                $filePath = storage_path('tmp/uploads/' . basename($fileName));
                if (is_file($filePath)) {
                    $record->addMediaWithCustomName($filePath, $field);
                }
            }
        }
    }

    protected function syncSingleDocument(ApplicationResultDefense $record, Request $request, string $collection): void
    {
        $current = $record->getMedia($collection)->last();

        if ($request->input($collection, false)) {
            if (!$current || $request->input($collection) !== $current->file_name) {
                if ($current) {
                    $current->delete();
                }
                $record->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input($collection))),
                    $collection
                );
            }
        } elseif ($current) {
            $current->delete();
        }
    }

    protected function syncMultipleDocuments(ApplicationResultDefense $record, Request $request, string $collection): void
    {
        $existing = $record->getMedia($collection);

        if ($existing->count() > 0) {
            foreach ($existing as $media) {
                if (!in_array($media->file_name, $request->input($collection, []), true)) {
                    $media->delete();
                }
            }
        }

        $remaining = $record->getMedia($collection)->pluck('file_name')->toArray();

        foreach ($request->input($collection, []) as $file) {
            if (count($remaining) === 0 || !in_array($file, $remaining, true)) {
                $record->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    $collection
                );
            }
        }
    }
}
