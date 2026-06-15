<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultDefenseRequest;
use App\Http\Requests\StoreApplicationResultDefenseRequest;
use App\Http\Requests\UpdateApplicationResultDefenseRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationScore;
use App\Services\ThesisTranscriptDocumentNumberService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationResultDefenseController extends Controller
{
    use MediaUploadingTrait;

    public function __construct(
        private readonly ThesisTranscriptDocumentNumberService $transcriptDocumentNumberService
    ) {
    }

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

            $table->editColumn('final_title', function ($row) {
                return $row->final_title ?? '';
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
            $table->editColumn('revision_approval_sheet', function ($row) {
                return $row->revision_approval_sheet ? '<a href="' . $row->revision_approval_sheet->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('title_change_form', function ($row) {
                return $row->title_change_form ? '<a href="' . $row->title_change_form->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'invitation_document', 'feedback_document', 'minutes_document', 'latest_script', 'approval_page', 'title_change_form', 'revision_approval_sheet']);

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
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'documentation'
            );
        }

        foreach ($request->input('invitation_document', []) as $file) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'invitation_document'
            );
        }

        foreach ($request->input('feedback_document', []) as $file) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($file)),
                'feedback_document'
            );
        }

        if ($request->input('minutes_document', false)) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('minutes_document'))),
                'minutes_document'
            );
        }

        if ($request->input('latest_script', false)) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                'latest_script'
            );
        }

        if ($request->input('approval_page', false)) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('approval_page'))),
                'approval_page'
            );
        }

        if ($request->input('title_change_form', false)) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('title_change_form'))),
                'title_change_form'
            );
        }

        if ($request->input('revision_approval_sheet', false)) {
            $applicationResultDefense->addMediaWithCustomName(
                storage_path('tmp/uploads/' . basename($request->input('revision_approval_sheet'))),
                'revision_approval_sheet'
            );
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
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'documentation'
                );
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
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'invitation_document'
                );
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
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($file)),
                    'feedback_document'
                );
            }
        }

        if ($request->input('minutes_document', false)) {
            if (! $applicationResultDefense->minutes_document || $request->input('minutes_document') !== $applicationResultDefense->minutes_document->file_name) {
                if ($applicationResultDefense->minutes_document) {
                    $applicationResultDefense->minutes_document->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('minutes_document'))),
                    'minutes_document'
                );
            }
        } elseif ($applicationResultDefense->minutes_document) {
            $applicationResultDefense->minutes_document->delete();
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultDefense->latest_script || $request->input('latest_script') !== $applicationResultDefense->latest_script->file_name) {
                if ($applicationResultDefense->latest_script) {
                    $applicationResultDefense->latest_script->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('latest_script'))),
                    'latest_script'
                );
            }
        } elseif ($applicationResultDefense->latest_script) {
            $applicationResultDefense->latest_script->delete();
        }

        if ($request->input('approval_page', false)) {
            if (! $applicationResultDefense->approval_page || $request->input('approval_page') !== $applicationResultDefense->approval_page->file_name) {
                if ($applicationResultDefense->approval_page) {
                    $applicationResultDefense->approval_page->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('approval_page'))),
                    'approval_page'
                );
            }
        } elseif ($applicationResultDefense->approval_page) {
            $applicationResultDefense->approval_page->delete();
        }

        if ($request->input('title_change_form', false)) {
            if (! $applicationResultDefense->title_change_form || $request->input('title_change_form') !== $applicationResultDefense->title_change_form->file_name) {
                if ($applicationResultDefense->title_change_form) {
                    $applicationResultDefense->title_change_form->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('title_change_form'))),
                    'title_change_form'
                );
            }
        } elseif ($applicationResultDefense->title_change_form) {
            $applicationResultDefense->title_change_form->delete();
        }

        if ($request->input('revision_approval_sheet', false)) {
            if (! $applicationResultDefense->revision_approval_sheet || $request->input('revision_approval_sheet') !== $applicationResultDefense->revision_approval_sheet->file_name) {
                if ($applicationResultDefense->revision_approval_sheet) {
                    $applicationResultDefense->revision_approval_sheet->delete();
                }
                $applicationResultDefense->addMediaWithCustomName(
                    storage_path('tmp/uploads/' . basename($request->input('revision_approval_sheet'))),
                    'revision_approval_sheet'
                );
            }
        } elseif ($applicationResultDefense->revision_approval_sheet) {
            $applicationResultDefense->revision_approval_sheet->delete();
        }

        return redirect()->route('admin.application-result-defenses.index');
    }

    public function show(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultDefense->load([
            'application.mahasiswa.prodi',
            'application.actions',
            'scores.examiner',
            'media',
        ]);
        $applicationResultDefense->syncApplicationStatus();

        return view('admin.applicationResultDefenses.show', compact('applicationResultDefense'));
    }

    public function approve(Request $request, $id)
    {
        $resultDefense = ApplicationResultDefense::with('application')->findOrFail($id);

        if ($resultDefense->isValidatedByAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan hasil sidang ini sudah divalidasi.',
            ], 422);
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($resultDefense, $request) {
                ApplicationAction::create([
                    'application_id' => $resultDefense->application_id,
                    'action_type' => 'result_defense_approved',
                    'action_by' => auth()->id(),
                    'notes' => $request->notes ?? 'Hasil sidang disetujui admin',
                    'metadata' => [
                        'result_defense_id' => $resultDefense->id,
                        'result' => $resultDefense->result,
                    ],
                ]);

                if (in_array($resultDefense->result, ['passed', 'revision'], true)) {
                    if ($resultDefense->scores()->count() === 0) {
                        $resultDefense->provisionScoreAssignments();
                    }
                } elseif ($resultDefense->result === 'failed') {
                    ApplicationScore::where('application_result_defence_id', $resultDefense->id)->delete();
                }

                $resultDefense->syncApplicationStatus();
            });

            $message = match ($resultDefense->result) {
                'passed' => 'Laporan hasil sidang (lulus tanpa revisi) divalidasi.',
                'revision' => 'Laporan hasil sidang (lulus dengan revisi) divalidasi.',
                'failed' => 'Laporan hasil sidang (tidak lulus) divalidasi. Mahasiswa dapat mendaftar ulang sidang skripsi.',
                default => 'Laporan hasil sidang divalidasi.',
            };

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function finalize(Request $request, $id)
    {
        abort_if(Gate::denies('application_result_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resultDefense = ApplicationResultDefense::with(['application', 'scores'])->findOrFail($id);

        if (!$resultDefense->isValidatedByAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Hasil sidang harus divalidasi admin terlebih dahulu.',
            ], 422);
        }

        if (!in_array($resultDefense->result, ['passed', 'revision'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Finalisasi kelulusan hanya untuk hasil lulus / revisi.',
            ], 422);
        }

        $alreadyFinalized = ApplicationAction::where('application_id', $resultDefense->application_id)
            ->where('action_type', 'defense_finalized')
            ->exists();

        if ($alreadyFinalized) {
            return response()->json([
                'success' => false,
                'message' => 'Kelulusan sidang ini sudah difinalisasi.',
            ], 422);
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $total = $resultDefense->scores()->count();
        $completed = $resultDefense->scores()->whereNotNull('score')->count();

        if ($total === 0 || $completed < $total) {
            return response()->json([
                'success' => false,
                'message' => 'Belum semua dosen mengisi nilai. Finalisasi belum dapat dilakukan.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($resultDefense, $request) {
                ApplicationAction::create([
                    'application_id' => $resultDefense->application_id,
                    'action_type' => 'defense_finalized',
                    'action_by' => auth()->id(),
                    'notes' => $request->notes ?? 'Finalisasi kelulusan sidang',
                    'metadata' => [
                        'result_defense_id' => $resultDefense->id,
                        'final_score' => $resultDefense->final_score,
                        'final_grade_letter' => $resultDefense->final_grade_letter,
                    ],
                ]);

                if ($resultDefense->application) {
                    $resultDefense->application->update([
                        'status' => 'done',
                        'notes' => $request->notes ?? $resultDefense->application->notes,
                    ]);
                }

                $this->transcriptDocumentNumberService->assign($resultDefense->application->fresh());
            });

            return response()->json([
                'success' => true,
                'message' => 'Kelulusan difinalisasi. Mahasiswa dapat mengunduh rekap nilai.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $resultDefense = ApplicationResultDefense::with('application')->findOrFail($id);

        $request->validate([
            'reason' => 'required|string|min:10',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi',
            'reason.min' => 'Alasan penolakan minimal 10 karakter',
        ]);

        try {
            DB::transaction(function () use ($resultDefense, $request) {
                if ($resultDefense->application) {
                    $resultDefense->application->update([
                        'notes' => $request->reason,
                    ]);
                }

                ApplicationAction::create([
                    'application_id' => $resultDefense->application_id,
                    'action_type' => 'result_defense_rejected',
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                    'metadata' => [
                        'result_defense_id' => $resultDefense->id,
                        'result' => $resultDefense->result,
                    ],
                ]);

                $resultDefense->syncApplicationStatus();
            });

            return response()->json([
                'success' => true,
                'message' => 'Laporan hasil sidang ditolak. Mahasiswa dapat memperbaiki dan mengunggah ulang.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
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

    public function printScore(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('application_result_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        // Load relationships
        $applicationResultDefense->load([
            'application.mahasiswa',
            'scores.examiner'
        ]);
        
        return view('frontend.applicationResultDefenses.print-score', compact('applicationResultDefense'));
    }
}
