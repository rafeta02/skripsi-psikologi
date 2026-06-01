<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationScheduleRequest;
use App\Http\Requests\StoreApplicationScheduleRequest;
use App\Http\Requests\UpdateApplicationScheduleRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationSchedule;
use App\Models\Ruang;
use App\Models\SkripsiDefense;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ApplicationScheduleController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_schedule_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get only schedules for current mahasiswa
        $mahasiswa = auth()->user()->mahasiswa;
        
        if ($mahasiswa) {
            $applicationSchedules = ApplicationSchedule::with(['application.mahasiswa.user', 'ruang', 'media'])
                ->whereHas('application', function($query) use ($mahasiswa) {
                    $query->where('mahasiswa_id', $mahasiswa->id);
                })
                ->orderBy('waktu', 'desc')
                ->get();
        } else {
            $applicationSchedules = collect();
        }

        return view('frontend.applicationSchedules.index', compact('applicationSchedules'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('application_schedule_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswa = auth()->user()->mahasiswa;
        $formAccessService = new FormAccessService();
        $scheduleAccess = $mahasiswa
            ? $formAccessService->canAccessApplicationSchedule($mahasiswa->id)
            : ['allowed' => false, 'message' => 'Profil mahasiswa tidak ditemukan.'];

        $activeApplication = null;
        $scheduleContext = null;

        if ($mahasiswa && ($scheduleAccess['allowed'] ?? false)) {
            $activeApplication = $scheduleAccess['application'] ?? null;
            $scheduleContext = $scheduleAccess['context'] ?? null;
        }

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.applicationSchedules.create', compact(
            'activeApplication',
            'ruangs',
            'scheduleAccess',
            'scheduleContext'
        ));
    }

    public function store(StoreApplicationScheduleRequest $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_if(!$mahasiswa, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application = Application::where('id', $request->application_id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();

        if ($application->stage === 'defense') {
            $defense = SkripsiDefense::where('application_id', $application->id)->first();
            abort_if(!$defense || !$defense->isAccepted(), Response::HTTP_FORBIDDEN, 'Pendaftaran sidang belum diterima admin.');

            if (ApplicationSchedule::where('application_id', $application->id)->exists()) {
                return redirect()->route('frontend.application-schedules.index')
                    ->with('error', 'Jadwal sidang sudah diajukan.');
            }
        }

        $applicationSchedule = ApplicationSchedule::create($request->only([
            'application_id',
            'schedule_type',
            'waktu',
            'ruang_id',
            'custom_place',
            'online_meeting',
            'note',
        ]));

        ApplicationAction::create([
            'application_id' => $application->id,
            'action_type' => 'schedule_submitted',
            'action_by' => auth()->id(),
            'notes' => 'Pengajuan jadwal ' . ($request->schedule_type ?? ''),
            'metadata' => [
                'schedule_id' => $applicationSchedule->id,
                'schedule_type' => $applicationSchedule->schedule_type,
                'waktu' => $applicationSchedule->waktu,
            ],
        ]);

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationSchedule->id]);
        }

        $label = $request->schedule_type === 'skripsi_defense' ? 'sidang skripsi' : 'seminar';

        return redirect()->route('frontend.application-schedules.index')
            ->with('success', "Jadwal {$label} berhasil diajukan. Menunggu verifikasi admin.");
    }

    public function edit(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ruangs = Ruang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationSchedule->load('application', 'ruang');

        return view('frontend.applicationSchedules.edit', compact('applicationSchedule', 'applications', 'ruangs'));
    }

    public function update(UpdateApplicationScheduleRequest $request, ApplicationSchedule $applicationSchedule)
    {
        $applicationSchedule->update($request->all());

        return redirect()->route('frontend.application-schedules.index')->with('success', 'Jadwal seminar berhasil diupdate');
    }

    public function show(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationSchedule->load('application', 'ruang');

        return view('frontend.applicationSchedules.show', compact('applicationSchedule'));
    }

    public function destroy(ApplicationSchedule $applicationSchedule)
    {
        abort_if(Gate::denies('application_schedule_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationSchedule->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationScheduleRequest $request)
    {
        $applicationSchedules = ApplicationSchedule::find(request('ids'));

        foreach ($applicationSchedules as $applicationSchedule) {
            $applicationSchedule->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_schedule_create') && Gate::denies('application_schedule_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationSchedule();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
