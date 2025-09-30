<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiDefenseRequest;
use App\Http\Requests\StoreSkripsiDefenseRequest;
use App\Http\Requests\UpdateSkripsiDefenseRequest;
use App\Models\Application;
use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SkripsiDefenseController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('skripsi_defense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SkripsiDefense::with(['application', 'created_by'])->select(sprintf('%s.*', (new SkripsiDefense)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'skripsi_defense_show';
                $editGate      = 'skripsi_defense_edit';
                $deleteGate    = 'skripsi_defense_delete';
                $crudRoutePart = 'skripsi-defenses';

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
            $table->editColumn('abstract', function ($row) {
                return $row->abstract ? $row->abstract : '';
            });
            $table->editColumn('defence_document', function ($row) {
                return $row->defence_document ? '<a href="' . $row->defence_document->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('plagiarism_report', function ($row) {
                return $row->plagiarism_report ? '<a href="' . $row->plagiarism_report->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('ethics_statement', function ($row) {
                if (! $row->ethics_statement) {
                    return '';
                }
                $links = [];
                foreach ($row->ethics_statement as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('research_instruments', function ($row) {
                if (! $row->research_instruments) {
                    return '';
                }
                $links = [];
                foreach ($row->research_instruments as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('data_collection_letter', function ($row) {
                if (! $row->data_collection_letter) {
                    return '';
                }
                $links = [];
                foreach ($row->data_collection_letter as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('research_module', function ($row) {
                if (! $row->research_module) {
                    return '';
                }
                $links = [];
                foreach ($row->research_module as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('mbkm_recommendation_letter', function ($row) {
                return $row->mbkm_recommendation_letter ? '<a href="' . $row->mbkm_recommendation_letter->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('publication_statement', function ($row) {
                return $row->publication_statement ? '<a href="' . $row->publication_statement->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('defense_approval_page', function ($row) {
                if (! $row->defense_approval_page) {
                    return '';
                }
                $links = [];
                foreach ($row->defense_approval_page as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('spp_receipt', function ($row) {
                return $row->spp_receipt ? '<a href="' . $row->spp_receipt->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('krs_latest', function ($row) {
                return $row->krs_latest ? '<a href="' . $row->krs_latest->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('eap_certificate', function ($row) {
                return $row->eap_certificate ? '<a href="' . $row->eap_certificate->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('transcript', function ($row) {
                return $row->transcript ? '<a href="' . $row->transcript->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('mbkm_report', function ($row) {
                if (! $row->mbkm_report) {
                    return '';
                }
                $links = [];
                foreach ($row->mbkm_report as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('research_poster', function ($row) {
                if (! $row->research_poster) {
                    return '';
                }
                $links = [];
                foreach ($row->research_poster as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });
            $table->editColumn('siakad_supervisor_screenshot', function ($row) {
                return $row->siakad_supervisor_screenshot ? '<a href="' . $row->siakad_supervisor_screenshot->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('supervision_logbook', function ($row) {
                if (! $row->supervision_logbook) {
                    return '';
                }
                $links = [];
                foreach ($row->supervision_logbook as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>';
                }

                return implode(', ', $links);
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'defence_document', 'plagiarism_report', 'ethics_statement', 'research_instruments', 'data_collection_letter', 'research_module', 'mbkm_recommendation_letter', 'publication_statement', 'defense_approval_page', 'spp_receipt', 'krs_latest', 'eap_certificate', 'transcript', 'mbkm_report', 'research_poster', 'siakad_supervisor_screenshot', 'supervision_logbook']);

            return $table->make(true);
        }

        return view('admin.skripsiDefenses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.skripsiDefenses.create', compact('applications'));
    }

    public function store(StoreSkripsiDefenseRequest $request)
    {
        $skripsiDefense = SkripsiDefense::create($request->all());

        if ($request->input('defence_document', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('defence_document'))))->toMediaCollection('defence_document');
        }

        if ($request->input('plagiarism_report', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_report'))))->toMediaCollection('plagiarism_report');
        }

        foreach ($request->input('ethics_statement', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('ethics_statement');
        }

        foreach ($request->input('research_instruments', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_instruments');
        }

        foreach ($request->input('data_collection_letter', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('data_collection_letter');
        }

        foreach ($request->input('research_module', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_module');
        }

        if ($request->input('mbkm_recommendation_letter', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('mbkm_recommendation_letter'))))->toMediaCollection('mbkm_recommendation_letter');
        }

        if ($request->input('publication_statement', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('publication_statement'))))->toMediaCollection('publication_statement');
        }

        foreach ($request->input('defense_approval_page', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('defense_approval_page');
        }

        if ($request->input('spp_receipt', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('spp_receipt'))))->toMediaCollection('spp_receipt');
        }

        if ($request->input('krs_latest', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
        }

        if ($request->input('eap_certificate', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('eap_certificate'))))->toMediaCollection('eap_certificate');
        }

        if ($request->input('transcript', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('transcript'))))->toMediaCollection('transcript');
        }

        foreach ($request->input('mbkm_report', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('mbkm_report');
        }

        foreach ($request->input('research_poster', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_poster');
        }

        if ($request->input('siakad_supervisor_screenshot', false)) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('siakad_supervisor_screenshot'))))->toMediaCollection('siakad_supervisor_screenshot');
        }

        foreach ($request->input('supervision_logbook', []) as $file) {
            $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supervision_logbook');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiDefense->id]);
        }

        return redirect()->route('admin.skripsi-defenses.index');
    }

    public function edit(SkripsiDefense $skripsiDefense)
    {
        abort_if(Gate::denies('skripsi_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $skripsiDefense->load('application', 'created_by');

        return view('admin.skripsiDefenses.edit', compact('applications', 'skripsiDefense'));
    }

    public function update(UpdateSkripsiDefenseRequest $request, SkripsiDefense $skripsiDefense)
    {
        $skripsiDefense->update($request->all());

        if ($request->input('defence_document', false)) {
            if (! $skripsiDefense->defence_document || $request->input('defence_document') !== $skripsiDefense->defence_document->file_name) {
                if ($skripsiDefense->defence_document) {
                    $skripsiDefense->defence_document->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('defence_document'))))->toMediaCollection('defence_document');
            }
        } elseif ($skripsiDefense->defence_document) {
            $skripsiDefense->defence_document->delete();
        }

        if ($request->input('plagiarism_report', false)) {
            if (! $skripsiDefense->plagiarism_report || $request->input('plagiarism_report') !== $skripsiDefense->plagiarism_report->file_name) {
                if ($skripsiDefense->plagiarism_report) {
                    $skripsiDefense->plagiarism_report->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('plagiarism_report'))))->toMediaCollection('plagiarism_report');
            }
        } elseif ($skripsiDefense->plagiarism_report) {
            $skripsiDefense->plagiarism_report->delete();
        }

        if (count($skripsiDefense->ethics_statement) > 0) {
            foreach ($skripsiDefense->ethics_statement as $media) {
                if (! in_array($media->file_name, $request->input('ethics_statement', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->ethics_statement->pluck('file_name')->toArray();
        foreach ($request->input('ethics_statement', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('ethics_statement');
            }
        }

        if (count($skripsiDefense->research_instruments) > 0) {
            foreach ($skripsiDefense->research_instruments as $media) {
                if (! in_array($media->file_name, $request->input('research_instruments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->research_instruments->pluck('file_name')->toArray();
        foreach ($request->input('research_instruments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_instruments');
            }
        }

        if (count($skripsiDefense->data_collection_letter) > 0) {
            foreach ($skripsiDefense->data_collection_letter as $media) {
                if (! in_array($media->file_name, $request->input('data_collection_letter', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->data_collection_letter->pluck('file_name')->toArray();
        foreach ($request->input('data_collection_letter', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('data_collection_letter');
            }
        }

        if (count($skripsiDefense->research_module) > 0) {
            foreach ($skripsiDefense->research_module as $media) {
                if (! in_array($media->file_name, $request->input('research_module', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->research_module->pluck('file_name')->toArray();
        foreach ($request->input('research_module', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_module');
            }
        }

        if ($request->input('mbkm_recommendation_letter', false)) {
            if (! $skripsiDefense->mbkm_recommendation_letter || $request->input('mbkm_recommendation_letter') !== $skripsiDefense->mbkm_recommendation_letter->file_name) {
                if ($skripsiDefense->mbkm_recommendation_letter) {
                    $skripsiDefense->mbkm_recommendation_letter->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('mbkm_recommendation_letter'))))->toMediaCollection('mbkm_recommendation_letter');
            }
        } elseif ($skripsiDefense->mbkm_recommendation_letter) {
            $skripsiDefense->mbkm_recommendation_letter->delete();
        }

        if ($request->input('publication_statement', false)) {
            if (! $skripsiDefense->publication_statement || $request->input('publication_statement') !== $skripsiDefense->publication_statement->file_name) {
                if ($skripsiDefense->publication_statement) {
                    $skripsiDefense->publication_statement->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('publication_statement'))))->toMediaCollection('publication_statement');
            }
        } elseif ($skripsiDefense->publication_statement) {
            $skripsiDefense->publication_statement->delete();
        }

        if (count($skripsiDefense->defense_approval_page) > 0) {
            foreach ($skripsiDefense->defense_approval_page as $media) {
                if (! in_array($media->file_name, $request->input('defense_approval_page', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->defense_approval_page->pluck('file_name')->toArray();
        foreach ($request->input('defense_approval_page', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('defense_approval_page');
            }
        }

        if ($request->input('spp_receipt', false)) {
            if (! $skripsiDefense->spp_receipt || $request->input('spp_receipt') !== $skripsiDefense->spp_receipt->file_name) {
                if ($skripsiDefense->spp_receipt) {
                    $skripsiDefense->spp_receipt->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('spp_receipt'))))->toMediaCollection('spp_receipt');
            }
        } elseif ($skripsiDefense->spp_receipt) {
            $skripsiDefense->spp_receipt->delete();
        }

        if ($request->input('krs_latest', false)) {
            if (! $skripsiDefense->krs_latest || $request->input('krs_latest') !== $skripsiDefense->krs_latest->file_name) {
                if ($skripsiDefense->krs_latest) {
                    $skripsiDefense->krs_latest->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('krs_latest'))))->toMediaCollection('krs_latest');
            }
        } elseif ($skripsiDefense->krs_latest) {
            $skripsiDefense->krs_latest->delete();
        }

        if ($request->input('eap_certificate', false)) {
            if (! $skripsiDefense->eap_certificate || $request->input('eap_certificate') !== $skripsiDefense->eap_certificate->file_name) {
                if ($skripsiDefense->eap_certificate) {
                    $skripsiDefense->eap_certificate->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('eap_certificate'))))->toMediaCollection('eap_certificate');
            }
        } elseif ($skripsiDefense->eap_certificate) {
            $skripsiDefense->eap_certificate->delete();
        }

        if ($request->input('transcript', false)) {
            if (! $skripsiDefense->transcript || $request->input('transcript') !== $skripsiDefense->transcript->file_name) {
                if ($skripsiDefense->transcript) {
                    $skripsiDefense->transcript->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('transcript'))))->toMediaCollection('transcript');
            }
        } elseif ($skripsiDefense->transcript) {
            $skripsiDefense->transcript->delete();
        }

        if (count($skripsiDefense->mbkm_report) > 0) {
            foreach ($skripsiDefense->mbkm_report as $media) {
                if (! in_array($media->file_name, $request->input('mbkm_report', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->mbkm_report->pluck('file_name')->toArray();
        foreach ($request->input('mbkm_report', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('mbkm_report');
            }
        }

        if (count($skripsiDefense->research_poster) > 0) {
            foreach ($skripsiDefense->research_poster as $media) {
                if (! in_array($media->file_name, $request->input('research_poster', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->research_poster->pluck('file_name')->toArray();
        foreach ($request->input('research_poster', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('research_poster');
            }
        }

        if ($request->input('siakad_supervisor_screenshot', false)) {
            if (! $skripsiDefense->siakad_supervisor_screenshot || $request->input('siakad_supervisor_screenshot') !== $skripsiDefense->siakad_supervisor_screenshot->file_name) {
                if ($skripsiDefense->siakad_supervisor_screenshot) {
                    $skripsiDefense->siakad_supervisor_screenshot->delete();
                }
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($request->input('siakad_supervisor_screenshot'))))->toMediaCollection('siakad_supervisor_screenshot');
            }
        } elseif ($skripsiDefense->siakad_supervisor_screenshot) {
            $skripsiDefense->siakad_supervisor_screenshot->delete();
        }

        if (count($skripsiDefense->supervision_logbook) > 0) {
            foreach ($skripsiDefense->supervision_logbook as $media) {
                if (! in_array($media->file_name, $request->input('supervision_logbook', []))) {
                    $media->delete();
                }
            }
        }
        $media = $skripsiDefense->supervision_logbook->pluck('file_name')->toArray();
        foreach ($request->input('supervision_logbook', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $skripsiDefense->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('supervision_logbook');
            }
        }

        return redirect()->route('admin.skripsi-defenses.index');
    }

    public function show(SkripsiDefense $skripsiDefense)
    {
        abort_if(Gate::denies('skripsi_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiDefense->load('application', 'created_by');

        return view('admin.skripsiDefenses.show', compact('skripsiDefense'));
    }

    public function destroy(SkripsiDefense $skripsiDefense)
    {
        abort_if(Gate::denies('skripsi_defense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiDefense->delete();

        return back();
    }

    public function massDestroy(MassDestroySkripsiDefenseRequest $request)
    {
        $skripsiDefenses = SkripsiDefense::find(request('ids'));

        foreach ($skripsiDefenses as $skripsiDefense) {
            $skripsiDefense->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('skripsi_defense_create') && Gate::denies('skripsi_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new SkripsiDefense();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
