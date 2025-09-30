<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\Mahasiswa;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::with(['mahasiswa'])->get();

        return view('frontend.applications.index', compact('applications'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.applications.create', compact('mahasiswas'));
    }

    public function store(StoreApplicationRequest $request)
    {
        $application = Application::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $application->id]);
        }

        return redirect()->route('frontend.applications.index');
    }

    public function edit(Application $application)
    {
        abort_if(Gate::denies('application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $application->load('mahasiswa');

        return view('frontend.applications.edit', compact('application', 'mahasiswas'));
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $application->update($request->all());

        return redirect()->route('frontend.applications.index');
    }

    public function show(Application $application)
    {
        abort_if(Gate::denies('application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->load('mahasiswa');

        return view('frontend.applications.show', compact('application'));
    }

    public function destroy(Application $application)
    {
        abort_if(Gate::denies('application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationRequest $request)
    {
        $applications = Application::find(request('ids'));

        foreach ($applications as $application) {
            $application->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_create') && Gate::denies('application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Application();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
