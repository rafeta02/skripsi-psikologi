<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyApplicationResultReviewRequest;
use App\Http\Requests\StoreApplicationResultReviewRequest;
use App\Http\Requests\UpdateApplicationResultReviewRequest;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationResultReview;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationResultReviewController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('application_result_review_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationResultReview::with(['application.mahasiswa'])->select(sprintf('%s.*', (new ApplicationResultReview)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_result_review_show';
                $editGate      = 'application_result_review_edit';
                $deleteGate    = 'application_result_review_delete';
                $crudRoutePart = 'application-result-reviews';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('mahasiswa_name', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nama : '';
            });

            $table->addColumn('mahasiswa_nim', function ($row) {
                return $row->application && $row->application->mahasiswa ? $row->application->mahasiswa->nim : '';
            });

            $table->editColumn('result', function ($row) {
                return $row->result ? $row->resultLabel() : '';
            });

            $table->addColumn('application_status', function ($row) {
                if (!$row->application) return '';
                
                $status = $row->application->status;
                $badges = [
                    'submitted' => '<span class="badge badge-info">Menunggu</span>',
                    'approved' => '<span class="badge badge-success">Disetujui</span>',
                    'rejected' => '<span class="badge badge-danger">Ditolak</span>',
                ];
                
                return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'application_status']);

            return $table->make(true);
        }

        return view('admin.applicationResultReviews.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_result_review_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationResultReviews.create', compact('applications'));
    }

    public function store(StoreApplicationResultReviewRequest $request)
    {
        $applicationResultReview = ApplicationResultReview::create($request->all());

        foreach ($request->input('form_document', []) as $file) {
            $applicationResultReview->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('form_document');
        }

        if ($request->input('latest_script', false)) {
            $applicationResultReview->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $applicationResultReview->id]);
        }

        return redirect()->route('admin.application-result-reviews.index');
    }

    public function edit(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationResultReview->load('application');

        return view('admin.applicationResultReviews.edit', compact('applicationResultReview', 'applications'));
    }

    public function update(UpdateApplicationResultReviewRequest $request, ApplicationResultReview $applicationResultReview)
    {
        $applicationResultReview->update($request->all());

        if (count($applicationResultReview->form_document) > 0) {
            foreach ($applicationResultReview->form_document as $media) {
                if (! in_array($media->file_name, $request->input('form_document', []))) {
                    $media->delete();
                }
            }
        }
        $media = $applicationResultReview->form_document->pluck('file_name')->toArray();
        foreach ($request->input('form_document', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $applicationResultReview->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('form_document');
            }
        }

        if ($request->input('latest_script', false)) {
            if (! $applicationResultReview->latest_script || $request->input('latest_script') !== $applicationResultReview->latest_script->file_name) {
                if ($applicationResultReview->latest_script) {
                    $applicationResultReview->latest_script->delete();
                }
                $applicationResultReview->addMedia(storage_path('tmp/uploads/' . basename($request->input('latest_script'))))->toMediaCollection('latest_script');
            }
        } elseif ($applicationResultReview->latest_script) {
            $applicationResultReview->latest_script->delete();
        }

        return redirect()->route('admin.application-result-reviews.index');
    }

    public function show(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReview->load('application.mahasiswa.prodi', 'application.mahasiswa.jenjang', 'application.actions');

        return view('admin.applicationResultReviews.show', compact('applicationResultReview'));
    }

    public function approve(Request $request, $id)
    {
        $resultReview = ApplicationResultReview::with('application')->findOrFail($id);

        if (! $resultReview->isEligibleOutcome()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi admin hanya untuk laporan dengan hasil disetujui.',
            ], 422);
        }

        $alreadyValidated = ApplicationAction::where('application_id', $resultReview->application_id)
            ->where('action_type', 'result_review_approved')
            ->exists();

        if ($alreadyValidated) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan hasil review ini sudah divalidasi.',
            ], 422);
        }

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($resultReview, $request) {
                ApplicationAction::create([
                    'application_id' => $resultReview->application_id,
                    'action_type' => 'result_review_approved',
                    'action_by' => auth()->id(),
                    'notes' => $request->notes ?? 'Hasil review disetujui',
                    'metadata' => [
                        'result_review_id' => $resultReview->id,
                        'result' => $resultReview->result,
                    ],
                ]);

                $resultReview->syncApplicationStatus();
            });

            return response()->json([
                'success' => true,
                'message' => 'Hasil review berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $resultReview = ApplicationResultReview::with('application')->findOrFail($id);

        $request->validate([
            'reason' => 'required|string|min:10',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi',
            'reason.min' => 'Alasan penolakan minimal 10 karakter',
        ]);

        try {
            DB::transaction(function () use ($resultReview, $request) {
                $resultReview->application->update([
                    'notes' => $request->reason,
                ]);

                ApplicationAction::create([
                    'application_id' => $resultReview->application_id,
                    'action_type' => 'result_review_rejected',
                    'action_by' => auth()->id(),
                    'notes' => $request->reason,
                    'metadata' => [
                        'result_review_id' => $resultReview->id,
                        'result' => $resultReview->result,
                    ],
                ]);

                $resultReview->syncApplicationStatus();
            });

            return response()->json([
                'success' => true,
                'message' => 'Hasil review ditolak'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ApplicationResultReview $applicationResultReview)
    {
        abort_if(Gate::denies('application_result_review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationResultReview->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationResultReviewRequest $request)
    {
        $applicationResultReviews = ApplicationResultReview::find(request('ids'));

        foreach ($applicationResultReviews as $applicationResultReview) {
            $applicationResultReview->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('application_result_review_create') && Gate::denies('application_result_review_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ApplicationResultReview();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}









