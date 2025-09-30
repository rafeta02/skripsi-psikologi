<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmSeminarRequest;
use App\Http\Requests\StoreMbkmSeminarRequest;
use App\Http\Requests\UpdateMbkmSeminarRequest;
use App\Models\Application;
use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class MbkmSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('mbkm_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminars = MbkmSeminar::with(['application', 'created_by', 'media'])->get();

        return view('frontend.mbkmSeminars.index', compact('mbkmSeminars'));
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.mbkmSeminars.create', compact('applications'));
    }

    public function store(StoreMbkmSeminarRequest $request)
    {
        $mbkmSeminar = MbkmSeminar::create($request->all());

        if ($request->input('proposal_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
        }

        if ($request->input('approval_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
        }

        if ($request->input('plagiarism_document', false)) {
            $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mbkmSeminar->id]);
        }

        return redirect()->route('frontend.mbkm-seminars.index');
    }

    public function edit(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmSeminar->load('application', 'created_by');

        return view('frontend.mbkmSeminars.edit', compact('applications', 'mbkmSeminar'));
    }

    public function update(UpdateMbkmSeminarRequest $request, MbkmSeminar $mbkmSeminar)
    {
        $mbkmSeminar->update($request->all());

        if ($request->input('proposal_document', false)) {
            if (! $mbkmSeminar->proposal_document || $request->input('proposal_document') !== $mbkmSeminar->proposal_document->file_name) {
                if ($mbkmSeminar->proposal_document) {
                    $mbkmSeminar->proposal_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
            }
        } elseif ($mbkmSeminar->proposal_document) {
            $mbkmSeminar->proposal_document->delete();
        }

        if ($request->input('approval_document', false)) {
            if (! $mbkmSeminar->approval_document || $request->input('approval_document') !== $mbkmSeminar->approval_document->file_name) {
                if ($mbkmSeminar->approval_document) {
                    $mbkmSeminar->approval_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
            }
        } elseif ($mbkmSeminar->approval_document) {
            $mbkmSeminar->approval_document->delete();
        }

        if ($request->input('plagiarism_document', false)) {
            if (! $mbkmSeminar->plagiarism_document || $request->input('plagiarism_document') !== $mbkmSeminar->plagiarism_document->file_name) {
                if ($mbkmSeminar->plagiarism_document) {
                    $mbkmSeminar->plagiarism_document->delete();
                }
                $mbkmSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
            }
        } elseif ($mbkmSeminar->plagiarism_document) {
            $mbkmSeminar->plagiarism_document->delete();
        }

        return redirect()->route('frontend.mbkm-seminars.index');
    }

    public function show(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->load('application', 'created_by');

        return view('frontend.mbkmSeminars.show', compact('mbkmSeminar'));
    }

    public function destroy(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmSeminarRequest $request)
    {
        $mbkmSeminars = MbkmSeminar::find(request('ids'));

        foreach ($mbkmSeminars as $mbkmSeminar) {
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
}
