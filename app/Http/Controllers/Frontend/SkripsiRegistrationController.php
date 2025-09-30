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

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.skripsiRegistrations.create', compact('applications', 'preference_supervisions', 'themes', 'tps_lecturers'));
    }

    public function store(StoreSkripsiRegistrationRequest $request)
    {
        $skripsiRegistration = SkripsiRegistration::create($request->all());

        foreach ($request->input('khs_all', []) as $file) {
            $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
        }

        if ($request->input('krs_latest', false)) {
            $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiRegistration->id]);
        }

        return redirect()->route('frontend.skripsi-registrations.index');
    }

    public function edit(SkripsiRegistration $skripsiRegistration)
    {
        abort_if(Gate::denies('skripsi_registration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Keilmuan::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tps_lecturers = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preference_supervisions = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiRegistration->load('application', 'theme', 'tps_lecturer', 'preference_supervision', 'created_by');

        return view('frontend.skripsiRegistrations.edit', compact('applications', 'preference_supervisions', 'skripsiRegistration', 'themes', 'tps_lecturers'));
    }

    public function update(UpdateSkripsiRegistrationRequest $request, SkripsiRegistration $skripsiRegistration)
    {
        $skripsiRegistration->update($request->all());

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
                $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('khs_all');
            }
        }

        if ($request->input('krs_latest', false)) {
            if (! $skripsiRegistration->krs_latest || $request->input('krs_latest') !== $skripsiRegistration->krs_latest->file_name) {
                if ($skripsiRegistration->krs_latest) {
                    $skripsiRegistration->krs_latest->delete();
                }
                $skripsiRegistration->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
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
