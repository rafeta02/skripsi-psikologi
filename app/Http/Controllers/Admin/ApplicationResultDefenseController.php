<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultDefenseRequest;
use App\Http\Requests\StoreApplicationResultDefenseRequest;
use App\Http\Requests\UpdateApplicationResultDefenseRequest;
use App\Models\Application;
use App\Models\ApplicationResultDefense;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationResultDefenseController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('application_result_defense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationResultDefense::with(['application'])->select(sprintf('%s.*', (new ApplicationResultDefense)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_result_defense_show';
                $editGate      = 'application_result_defense_edit';
                $deleteGate    = 'application_result_defense_delete';
                $crudRoutePart = 'application-result-defenses';

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

            $table->editColumn('result', function ($row) {
                return $row->result ? ApplicationResultDefense::RESULT_SELECT[$row->result] : '';
            });

            $table->editColumn('invitation_document', function ($row) {
                if (! $row->invitation_document) {
                    return '';
                }
                $links = [];
                foreach ($row->invitation_document as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('feedback_document', function ($row) {
                if (! $row->feedback_document) {
                    return '';
                }
                $links = [];
                foreach ($row->feedback_document as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('minutes_document', function ($row) {
                return $row->minutes_document ? '<a href="' . $row->minutes_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('latest_script', function ($row) {
                return $row->latest_script ? '<a href="' . $row->latest_script->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('approval_page', function ($row) {
                return $row->approval_page ? '<a href="' . $row->approval_page->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('report_document', function ($row) {
                if (! $row->report_document) {
                    return '';
                }
                $links = [];
                foreach ($row->report_document as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('revision_approval_sheet', function ($row) {
                if (! $row->revision_approval_sheet) {
                    return '';
                }
                $links = [];
                foreach ($row->revision_approval_sheet as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'invitation_document', 'feedback_document', 'minutes_document', 'latest_script', 'approval_page', 'report_document', 'revision_approval_sheet']);

            return $table->make(true);
        }

        return view('admin.applicationResultDefenses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationResultDefenses.create', compact('applications'));
    }

    public function store(StoreApplicationResultDefenseRequest $request)
    {
        $applicationResultDefense = ApplicationResultDefense::create($request->all());

        foreach ($request->input('documentation', []) as $file) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('documentation');
        }

        foreach ($request->input('invitation_document', []) as $file) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('invitation_document');
        }

        foreach ($request->input('feedback_document', []) as $file) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('feedback_document');
        }

        if ($request->input('minutes_document', false)) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('minutes_document'))))->toMediaCollection('minutes_document');
        }

        if ($request->input('latest_script', false)) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
        }

        if ($request->input('approval_page', false)) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_page'))))->toMediaCollection('approval_page');
        }

        foreach ($request->input('report_document', []) as $file) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
        }

        foreach ($request->input('revision_approval_sheet', []) as $file) {
            $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('revision_approval_sheet');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationResultDefense->id]);
        }

        return redirect()->route('admin.application-result-defenses.index');
    }

    public function edit(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationResultDefense->load('application');

        return view('admin.applicationResultDefenses.edit', compact('applicationResultDefense', 'applications'));
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
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('documentation');
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
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('invitation_document');
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
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('feedback_document');
            }
        }

        if ($request->input('minutes_document', false)) {
            if (! $applicationResultDefense->minutes_document || $request->input('minutes_document') !== $applicationResultDefense->minutes_document->file_name) {
                if ($applicationResultDefense->minutes_document) {
                    $applicationResultDefense->minutes_document->delete();
                }
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('minutes_document'))))->toMediaCollection('minutes_document');
            }
        } elseif ($applicationResultDefense->minutes_document) {
            $applicationResultDefense->minutes_document->delete();
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultDefense->latest_script || $request->input('latest_script') !== $applicationResultDefense->latest_script->file_name) {
                if ($applicationResultDefense->latest_script) {
                    $applicationResultDefense->latest_script->delete();
                }
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
            }
        } elseif ($applicationResultDefense->latest_script) {
            $applicationResultDefense->latest_script->delete();
        }

        if ($request->input('approval_page', false)) {
            if (! $applicationResultDefense->approval_page || $request->input('approval_page') !== $applicationResultDefense->approval_page->file_name) {
                if ($applicationResultDefense->approval_page) {
                    $applicationResultDefense->approval_page->delete();
                }
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('approval_page'))))->toMediaCollection('approval_page');
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
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('report_document');
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
                $applicationResultDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('revision_approval_sheet');
            }
        }

        return redirect()->route('admin.application-result-defenses.index');
    }

    public function show(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->load('application');

        return view('admin.applicationResultDefenses.show', compact('applicationResultDefense'));
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
}
