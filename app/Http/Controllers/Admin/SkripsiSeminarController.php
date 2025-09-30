<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiSeminarRequest;
use App\Http\Requests\StoreSkripsiSeminarRequest;
use App\Http\Requests\UpdateSkripsiSeminarRequest;
use App\Models\Application;
use App\Models\SkripsiSeminar;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SkripsiSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('skripsi_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SkripsiSeminar::with(['application', 'created_by'])->select(sprintf('%s.*', (new SkripsiSeminar)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'skripsi_seminar_show';
                $editGate      = 'skripsi_seminar_edit';
                $deleteGate    = 'skripsi_seminar_delete';
                $crudRoutePart = 'skripsi-seminars';

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

        return view('admin.skripsiSeminars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.skripsiSeminars.create', compact('applications'));
    }

    public function store(StoreSkripsiSeminarRequest $request)
    {
        $skripsiSeminar = SkripsiSeminar::create($request->all());

        if ($request->input('proposal_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
        }

        if ($request->input('approval_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
        }

        if ($request->input('plagiarism_document', false)) {
            $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiSeminar->id]);
        }

        return redirect()->route('admin.skripsi-seminars.index');
    }

    public function edit(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiSeminar->load('application', 'created_by');

        return view('admin.skripsiSeminars.edit', compact('applications', 'skripsiSeminar'));
    }

    public function update(UpdateSkripsiSeminarRequest $request, SkripsiSeminar $skripsiSeminar)
    {
        $skripsiSeminar->update($request->all());

        if ($request->input('proposal_document', false)) {
            if (! $skripsiSeminar->proposal_document || $request->input('proposal_document') !== $skripsiSeminar->proposal_document->file_name) {
                if ($skripsiSeminar->proposal_document) {
                    $skripsiSeminar->proposal_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('proposal_document'))))->toMediaCollection('proposal_document');
            }
        } elseif ($skripsiSeminar->proposal_document) {
            $skripsiSeminar->proposal_document->delete();
        }

        if ($request->input('approval_document', false)) {
            if (! $skripsiSeminar->approval_document || $request->input('approval_document') !== $skripsiSeminar->approval_document->file_name) {
                if ($skripsiSeminar->approval_document) {
                    $skripsiSeminar->approval_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_document'))))->toMediaCollection('approval_document');
            }
        } elseif ($skripsiSeminar->approval_document) {
            $skripsiSeminar->approval_document->delete();
        }

        if ($request->input('plagiarism_document', false)) {
            if (! $skripsiSeminar->plagiarism_document || $request->input('plagiarism_document') !== $skripsiSeminar->plagiarism_document->file_name) {
                if ($skripsiSeminar->plagiarism_document) {
                    $skripsiSeminar->plagiarism_document->delete();
                }
                $skripsiSeminar->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_document'))))->toMediaCollection('plagiarism_document');
            }
        } elseif ($skripsiSeminar->plagiarism_document) {
            $skripsiSeminar->plagiarism_document->delete();
        }

        return redirect()->route('admin.skripsi-seminars.index');
    }

    public function show(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->load('application', 'created_by');

        return view('admin.skripsiSeminars.show', compact('skripsiSeminar'));
    }

    public function destroy(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiSeminarRequest $request)
    {
        $skripsiSeminars = SkripsiSeminar::find(request('ids'));

        foreach ($skripsiSeminars as $skripsiSeminar) {
            $skripsiSeminar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_seminar_create') && Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiSeminar();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
