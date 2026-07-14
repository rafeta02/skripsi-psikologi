<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Application;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationSchedule;
use App\Services\FormAccessService;
use App\Services\MbkmGroupProgressService;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationResultSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_result_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $applicationIds = Application::where('mahasiswa_id', $mahasiswaId)->pluck('id');

        // Anggota kelompok: laporan tersimpan di application ketua
        $ownerSeminar = app(MbkmGroupProgressService::class)
            ->resolveOwnerApplication((int) $mahasiswaId, 'seminar');
        if ($ownerSeminar) {
            $applicationIds = $applicationIds->push($ownerSeminar->id)->unique()->values();
        }

        $applicationResultSeminars = ApplicationResultSeminar::with(['application'])
            ->whereIn('application_id', $applicationIds)
            ->orderByDesc('created_at')
            ->get();

        $formAccessService = new FormAccessService();
        $canCreate = $formAccessService->canAccessApplicationResultSeminar($mahasiswaId, true);

        return view('frontend.applicationResultSeminars.index', compact('applicationResultSeminars', 'canCreate'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        if (!$mahasiswaId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Profil mahasiswa tidak ditemukan.');
        }

        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessApplicationResultSeminar($mahasiswaId, true);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.applicationResultSeminars.create', compact('activeApplication'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('application_result_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswaId = auth()->user()->mahasiswa_id;
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessApplicationResultSeminar($mahasiswaId, true);

        if (!$access['allowed']) {
            return redirect()->route('frontend.application-result-seminars.index')
                ->with('error', $access['message']);
        }

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'result' => 'required|in:' . implode(',', array_keys(ApplicationResultSeminar::RESULT_SELECT)),
            'note' => 'nullable|string',
            'meeting_recording_link' => 'nullable|url|max:500',
            'form_document' => 'required|array|min:1',
            'form_document.*' => 'file|mimes:pdf|max:10240',
            'attendance_document' => 'required|file|mimes:pdf|max:10240',
            'documentation' => 'required|array|min:1',
            'documentation.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'latest_script' => 'required|file|mimes:pdf|max:10240',
        ], [
            'result.required' => 'Hasil review wajib dipilih',
            'form_document.required' => 'Form Review Kelayakan Proposal MBKM Riset wajib diunggah',
            'attendance_document.required' => 'Presensi Peserta wajib diunggah',
            'documentation.required' => 'Dokumentasi Seminar wajib diunggah',
            'latest_script.required' => 'Naskah Proposal MBKM hasil revisi wajib diunggah',
            'meeting_recording_link.url' => 'Tautan record meeting harus berupa URL yang valid',
        ]);

        if ((int) $validated['application_id'] !== (int) $access['application']->id) {
            abort(403, 'Aplikasi tidak valid.');
        }

        $ownsApplication = Application::where('id', $validated['application_id'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if (!$ownsApplication) {
            abort(403, 'Unauthorized');
        }

        $applicationResultSeminar = ApplicationResultSeminar::create([
            'application_id' => $validated['application_id'],
            'result' => $validated['result'],
            'note' => $validated['note'] ?? null,
            'revision_deadline' => $this->resolveRevisionDeadline((int) $validated['application_id']),
            'meeting_recording_link' => $validated['meeting_recording_link'] ?? null,
        ]);

        $applicationResultSeminar->load('application.mahasiswa');

        $applicationResultSeminar->addMultipleMediaWithCustomName(
            $request->file('form_document'),
            'form_document'
        );

        $applicationResultSeminar->addMediaWithCustomName(
            $request->file('attendance_document'),
            'attendance_document'
        );

        $applicationResultSeminar->addMultipleMediaWithCustomName(
            $request->file('documentation'),
            'documentation'
        );

        $applicationResultSeminar->addMediaWithCustomName(
            $request->file('latest_script'),
            'latest_script'
        );

        $applicationResultSeminar->syncApplicationStatus();

        return redirect()->route('frontend.application-result-seminars.index')
            ->with('success', 'Laporan hasil review berhasil dikirim. Menunggu validasi admin sebelum Anda dapat mendaftar sidang skripsi.');
    }

    /**
     * Tenggat revisi = 2 minggu sejak jadwal Review Kelayakan Proposal.
     */
    protected function resolveRevisionDeadline(int $applicationId): string
    {
        $schedule = ApplicationSchedule::where('application_id', $applicationId)
            ->whereIn('schedule_type', ['mbkm_seminar', 'seminar'])
            ->orderByDesc('id')
            ->first();

        $baseDate = $schedule?->getRawOriginal('waktu')
            ? Carbon::parse($schedule->getRawOriginal('waktu'))
            : now();

        return $baseDate->copy()->addWeeks(2)->format(config('panel.date_format'));
    }

    public function show(ApplicationResultSeminar $applicationResultSeminar)
    {
        abort_if(Gate::denies('application_result_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->authorizeOwnership($applicationResultSeminar);

        $applicationResultSeminar->load('application');
        $applicationResultSeminar->syncApplicationStatus();

        $formAccessService = new FormAccessService();
        $canAccessDefense = $formAccessService->canAccessSkripsiDefense(auth()->user()->mahasiswa_id);

        return view('frontend.applicationResultSeminars.show', compact(
            'applicationResultSeminar',
            'canAccessDefense'
        ));
    }

    protected function authorizeOwnership(ApplicationResultSeminar $applicationResultSeminar): void
    {
        $mahasiswaId = (int) auth()->user()->mahasiswa_id;
        $applicationResultSeminar->loadMissing('application');
        $application = $applicationResultSeminar->application;

        if (!$application) {
            abort(403, 'Unauthorized');
        }

        // Ketua (pemilik) atau anggota kelompok boleh melihat laporan ketua
        if (! app(MbkmGroupProgressService::class)->canViewOwnerApplication($mahasiswaId, $application)) {
            abort(403, 'Unauthorized');
        }
    }
}
