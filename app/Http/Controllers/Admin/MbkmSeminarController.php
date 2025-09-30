<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class MbkmSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mbkm_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MbkmSeminar::with(['application', 'created_by'])->select(sprintf('%s.*', (new MbkmSeminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mbkm_seminar_show';
                $editGate      = 'mbkm_seminar_edit';
                $deleteGate    = 'mbkm_seminar_delete';
                $crudRoutePart = 'mbkm-seminars';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('application_status', function ($row) {
                return $row->application ? $row->application->status : '';
            });

            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('proposal_document', function ($row) {
                return $row->proposal_document ? '<a href="' . $row->proposal_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('approval_document', function ($row) {
                return $row->approval_document ? '<a href="' . $row->approval_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('plagiarism_document', function ($row) {
                return $row->plagiarism_document ? '<a href="' . $row->plagiarism_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'proposal_document', 'approval_document', 'plagiarism_document']);

            return $table->make(true);
        }

        return view('admin.mbkmSeminars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mbkmSeminars.create', compact('applications'));
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

        return redirect()->route('admin.mbkm-seminars.index');
    }

    public function edit(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmSeminar->load('application', 'created_by');

        return view('admin.mbkmSeminars.edit', compact('applications', 'mbkmSeminar'));
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

        return redirect()->route('admin.mbkm-seminars.index');
    }

    public function show(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->load('application', 'created_by');

        return view('admin.mbkmSeminars.show', compact('mbkmSeminar'));
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
