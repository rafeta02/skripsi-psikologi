<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmSeminarRequest;
use App\Http\Requests\StoreMbkmSeminarRequest;
use App\Http\Requests\UpdateMbkmSeminarRequest;
use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\MbkmSeminar;
use App\Services\FormAccessService;
use App\Services\MbkmGroupProgressService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MbkmSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('mbkm_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = (int) auth()->user()->mahasiswa_id;
        $groupService = app(MbkmGroupProgressService::class);
        $formAccess = app(FormAccessService::class);

        $isGroupFollower = $groupService->isFollowerAnggota($mahasiswaId);
        $access = $formAccess->canAccessMbkmSeminar($mahasiswaId);

        $ownerMahasiswaId = $groupService->getOwnerMahasiswaId($mahasiswaId) ?? $mahasiswaId;

        // 1 kelompok = 1 form (milik ketua). Anggota melihat form ketua via withoutGlobalScopes.
        $ownerSeminarApps = Application::where('mahasiswa_id', $ownerMahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'seminar')
            ->where('is_group_mirror', false)
            ->pluck('id');

        $mbkmSeminars = MbkmSeminar::withoutGlobalScopes()
            ->with(['application.mahasiswa', 'created_by', 'media'])
            ->whereIn('application_id', $ownerSeminarApps)
            ->orderByDesc('id')
            ->get();

        $registrationApp = $groupService->resolveOwnerApplication($mahasiswaId, 'registration');
        $registration = $registrationApp
            ? MbkmRegistration::withoutGlobalScopes()
                ->with(['groupMembers.mahasiswa', 'research_group', 'themes'])
                ->where('application_id', $registrationApp->id)
                ->first()
            : null;

        $canCreate = !$isGroupFollower && !empty($access['allowed']);

        return view('frontend.mbkmSeminars.index', compact(
            'mbkmSeminars',
            'isGroupFollower',
            'canCreate',
            'access',
            'registration',
            'registrationApp'
        ));
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $formAccessService = app(FormAccessService::class);
        $access = $formAccessService->canAccessMbkmSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.mbkm-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        $registration = MbkmRegistration::withoutGlobalScopes()
            ->with(['groupMembers.mahasiswa', 'research_group', 'themes', 'preference_supervision'])
            ->where('application_id', $activeApplication->id)
            ->first();

        return view('frontend.mbkmSeminars.create', compact('activeApplication', 'registration'));
    }

    public function store(StoreMbkmSeminarRequest $request)
    {
        $formAccessService = app(FormAccessService::class);
        $access = $formAccessService->canAccessMbkmSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.mbkm-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];
        $mahasiswaId = (int) auth()->user()->mahasiswa_id;

        try {
            $mbkmSeminar = DB::transaction(function () use ($request, $activeApplication, $mahasiswaId) {
                // Application stage seminar milik ketua; mirror anggota dibuat oleh ApplicationObserver
                $seminarApplication = Application::create([
                    'mahasiswa_id' => $mahasiswaId,
                    'type' => 'mbkm',
                    'stage' => 'seminar',
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);

                $registration = MbkmRegistration::withoutGlobalScopes()
                    ->where('application_id', $activeApplication->id)
                    ->first();

                $title = $request->input('title')
                    ?: ($registration->title_mbkm ?? $registration->title ?? 'Review Kelayakan Proposal');

                $mbkmSeminar = MbkmSeminar::create([
                    'application_id' => $seminarApplication->id,
                    'title' => $title,
                    'title_en' => $request->input('title_en'),
                    'lokasi_kkn' => $request->input('lokasi_kkn') ?: $registration?->lokasi_kkn,
                    'created_by_id' => auth()->id(),
                ]);

                foreach (['proposal_document', 'approval_document', 'plagiarism_document'] as $collection) {
                    if ($request->hasFile($collection)) {
                        $mbkmSeminar->addMediaWithCustomName($request->file($collection), $collection);
                    }
                }

                return $mbkmSeminar;
            });

            return redirect()->route('frontend.mbkm-seminars.show', $mbkmSeminar->id)
                ->with('success', 'Pendaftaran Review Kelayakan Proposal kelompok berhasil dikirim. Status anggota ikut terbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar = MbkmSeminar::withoutGlobalScopes()->with(['application', 'created_by', 'media'])->findOrFail($mbkmSeminar->id);
        $this->authorizeKetuaSeminar($mbkmSeminar);

        if ($mbkmSeminar->application && !in_array($mbkmSeminar->application->status, ['submitted', 'revision'], true)) {
            return redirect()->route('frontend.mbkm-seminars.show', $mbkmSeminar->id)
                ->with('error', 'Pendaftaran tidak dapat diedit pada status ini.');
        }

        return view('frontend.mbkmSeminars.edit', compact('mbkmSeminar'));
    }

    public function update(UpdateMbkmSeminarRequest $request, MbkmSeminar $mbkmSeminar)
    {
        $mbkmSeminar = MbkmSeminar::withoutGlobalScopes()->with('application')->findOrFail($mbkmSeminar->id);
        $this->authorizeKetuaSeminar($mbkmSeminar);

        $mbkmSeminar->update($request->only(['title', 'title_en', 'lokasi_kkn']));

        foreach (['proposal_document', 'approval_document', 'plagiarism_document'] as $collection) {
            if ($request->hasFile($collection)) {
                $mbkmSeminar->clearMediaCollection($collection);
                $mbkmSeminar->addMediaWithCustomName($request->file($collection), $collection);
            }
        }

        if ($mbkmSeminar->application && $mbkmSeminar->application->status === 'revision') {
            $mbkmSeminar->application->update(['status' => 'submitted']);
        }

        return redirect()->route('frontend.mbkm-seminars.show', $mbkmSeminar->id)
            ->with('success', 'Pendaftaran Review Kelayakan Proposal berhasil diupdate!');
    }

    public function show(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar = MbkmSeminar::withoutGlobalScopes()
            ->with(['application.mahasiswa', 'created_by', 'media'])
            ->findOrFail($mbkmSeminar->id);

        $this->authorizeGroupSeminarView($mbkmSeminar);

        $mahasiswaId = (int) auth()->user()->mahasiswa_id;
        $groupService = app(MbkmGroupProgressService::class);
        $isGroupFollower = $groupService->isFollowerAnggota($mahasiswaId);
        $isKetua = !$isGroupFollower
            && (int) ($mbkmSeminar->application->mahasiswa_id ?? 0) === $mahasiswaId;

        $registrationApp = $groupService->resolveOwnerApplication($mahasiswaId, 'registration');
        $registration = $registrationApp
            ? MbkmRegistration::withoutGlobalScopes()
                ->with(['groupMembers.mahasiswa'])
                ->where('application_id', $registrationApp->id)
                ->first()
            : null;

        return view('frontend.mbkmSeminars.show', compact(
            'mbkmSeminar',
            'isGroupFollower',
            'isKetua',
            'registration'
        ));
    }

    public function destroy(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar = MbkmSeminar::withoutGlobalScopes()->findOrFail($mbkmSeminar->id);
        $this->authorizeKetuaSeminar($mbkmSeminar);
        $mbkmSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmSeminarRequest $request)
    {
        $mbkmSeminars = MbkmSeminar::withoutGlobalScopes()->find(request('ids'));

        foreach ($mbkmSeminars as $mbkmSeminar) {
            $this->authorizeKetuaSeminar($mbkmSeminar);
            $mbkmSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mbkm_seminar_create') && Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new MbkmSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    private function authorizeKetuaSeminar(MbkmSeminar $mbkmSeminar): void
    {
        $mahasiswaId = (int) auth()->user()->mahasiswa_id;
        $groupService = app(MbkmGroupProgressService::class);

        if ($groupService->isFollowerAnggota($mahasiswaId)) {
            abort(403, 'Hanya ketua kelompok yang dapat mengubah form Review Kelayakan Proposal.');
        }

        $app = $mbkmSeminar->application;
        if (!$app || (int) $app->mahasiswa_id !== $mahasiswaId || $app->is_group_mirror) {
            abort(403, 'Unauthorized access');
        }
    }

    private function authorizeGroupSeminarView(MbkmSeminar $mbkmSeminar): void
    {
        $mahasiswaId = (int) auth()->user()->mahasiswa_id;
        $app = $mbkmSeminar->application;

        if (!$app) {
            abort(404);
        }

        $groupService = app(MbkmGroupProgressService::class);
        if (!$groupService->canViewOwnerApplication($mahasiswaId, $app)) {
            abort(403, 'Unauthorized access');
        }
    }
}
