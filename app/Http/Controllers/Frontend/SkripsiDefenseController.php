<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiDefenseRequest;
use App\Http\Requests\StoreSkripsiDefenseRequest;
use App\Http\Requests\UpdateSkripsiDefenseRequest;
use App\Models\Application;
use App\Models\SkripsiDefense;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SkripsiDefenseController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('skripsi_defense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiDefenses = SkripsiDefense::with(['application', 'created_by', 'media'])->get();

        return view('frontend.skripsiDefenses.index', compact('skripsiDefenses'));
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_defense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiDefense(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-defenses.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.skripsiDefenses.create', compact('activeApplication'));
    }

    public function store(StoreSkripsiDefenseRequest $request)
    {
        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiDefense(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-defenses.index')
                ->with('error', $access['message']);
        }

        $seminarApplication = $access['application'];

        // Create new Application for defense stage
        $defenseApplication = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => $seminarApplication->type, // Inherit type from seminar (skripsi or mbkm)
            'stage' => 'defense',
            'status' => 'submitted',
            'submitted_at' => now()->format('d-m-Y H:i:s'),
        ]);

        // Create Skripsi Defense with defense application
        $data = $request->all();
        $data['application_id'] = $defenseApplication->id;
        
        $skripsiDefense = SkripsiDefense::create($data);

        if ($request->input('defence_document', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('defence_document')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'defence_document');
        }

        if ($request->input('plagiarism_report', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('plagiarism_report')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'plagiarism_report');
        }

        foreach ($request->input('ethics_statement', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'ethics_statement');
        }

        foreach ($request->input('research_instruments', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'research_instruments');
        }

        foreach ($request->input('data_collection_letter', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'data_collection_letter');
        }

        foreach ($request->input('research_module', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'research_module');
        }

        if ($request->input('mbkm_recommendation_letter', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('mbkm_recommendation_letter')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'mbkm_recommendation_letter');
        }

        if ($request->input('publication_statement', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('publication_statement')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'publication_statement');
        }

        foreach ($request->input('defense_approval_page', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'defense_approval_page');
        }

        if ($request->input('spp_receipt', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('spp_receipt')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'spp_receipt');
        }

        if ($request->input('krs_latest', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('krs_latest')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'krs_latest');
        }

        if ($request->input('eap_certificate', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('eap_certificate')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'eap_certificate');
        }

        if ($request->input('transcript', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('transcript')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'transcript');
        }

        foreach ($request->input('mbkm_report', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'mbkm_report');
        }

        foreach ($request->input('research_poster', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'research_poster');
        }

        if ($request->input('siakad_supervisor_screenshot', false)) {
            $filePath = storage_path('tmp/uploads/' . basename($request->input('siakad_supervisor_screenshot')));
            $skripsiDefense->addMediaWithCustomName($filePath, 'siakad_supervisor_screenshot');
        }

        foreach ($request->input('supervision_logbook', []) as $file) {
            $filePath = storage_path('tmp/uploads/' . basename($file));
            $skripsiDefense->addMediaWithCustomName($filePath, 'supervision_logbook');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $skripsiDefense->id]);
        }

        return redirect()->route('frontend.skripsi-defenses.index');
    }

    public function edit(SkripsiDefense $skripsiDefense)
    {
        abort_if(Gate::denies('skripsi_defense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiDefense->load('application', 'created_by');

        return view('frontend.skripsiDefenses.edit', compact('skripsiDefense'));
    }

    public function update(UpdateSkripsiDefenseRequest $request, SkripsiDefense $skripsiDefense)
    {
        $skripsiDefense->update($request->all());

        if ($request->input('defence_document', false)) {
            if (! $skripsiDefense->defence_document || $request->input('defence_document') !== $skripsiDefense->defence_document->file_name) {
                if ($skripsiDefense->defence_document) {
                    $skripsiDefense->defence_document->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('defence_document')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'defence_document');
            }
        } elseif ($skripsiDefense->defence_document) {
            $skripsiDefense->defence_document->delete();
        }

        if ($request->input('plagiarism_report', false)) {
            if (! $skripsiDefense->plagiarism_report || $request->input('plagiarism_report') !== $skripsiDefense->plagiarism_report->file_name) {
                if ($skripsiDefense->plagiarism_report) {
                    $skripsiDefense->plagiarism_report->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('plagiarism_report')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'plagiarism_report');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'ethics_statement');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'research_instruments');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'data_collection_letter');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'research_module');('research_module');
            }
        }

        if ($request->input('mbkm_recommendation_letter', false)) {
            if (! $skripsiDefense->mbkm_recommendation_letter || $request->input('mbkm_recommendation_letter') !== $skripsiDefense->mbkm_recommendation_letter->file_name) {
                if ($skripsiDefense->mbkm_recommendation_letter) {
                    $skripsiDefense->mbkm_recommendation_letter->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('mbkm_recommendation_letter')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'mbkm_recommendation_letter');('mbkm_recommendation_letter');
            }
        } elseif ($skripsiDefense->mbkm_recommendation_letter) {
            $skripsiDefense->mbkm_recommendation_letter->delete();
        }

        if ($request->input('publication_statement', false)) {
            if (! $skripsiDefense->publication_statement || $request->input('publication_statement') !== $skripsiDefense->publication_statement->file_name) {
                if ($skripsiDefense->publication_statement) {
                    $skripsiDefense->publication_statement->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('publication_statement')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'publication_statement');('publication_statement');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'defense_approval_page');('defense_approval_page');
            }
        }

        if ($request->input('spp_receipt', false)) {
            if (! $skripsiDefense->spp_receipt || $request->input('spp_receipt') !== $skripsiDefense->spp_receipt->file_name) {
                if ($skripsiDefense->spp_receipt) {
                    $skripsiDefense->spp_receipt->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('spp_receipt')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'spp_receipt');('spp_receipt');
            }
        } elseif ($skripsiDefense->spp_receipt) {
            $skripsiDefense->spp_receipt->delete();
        }

        if ($request->input('krs_latest', false)) {
            if (! $skripsiDefense->krs_latest || $request->input('krs_latest') !== $skripsiDefense->krs_latest->file_name) {
                if ($skripsiDefense->krs_latest) {
                    $skripsiDefense->krs_latest->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('krs_latest')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'krs_latest');('krs_latest');
            }
        } elseif ($skripsiDefense->krs_latest) {
            $skripsiDefense->krs_latest->delete();
        }

        if ($request->input('eap_certificate', false)) {
            if (! $skripsiDefense->eap_certificate || $request->input('eap_certificate') !== $skripsiDefense->eap_certificate->file_name) {
                if ($skripsiDefense->eap_certificate) {
                    $skripsiDefense->eap_certificate->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('eap_certificate')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'eap_certificate');('eap_certificate');
            }
        } elseif ($skripsiDefense->eap_certificate) {
            $skripsiDefense->eap_certificate->delete();
        }

        if ($request->input('transcript', false)) {
            if (! $skripsiDefense->transcript || $request->input('transcript') !== $skripsiDefense->transcript->file_name) {
                if ($skripsiDefense->transcript) {
                    $skripsiDefense->transcript->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('transcript')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'transcript');('transcript');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'mbkm_report');('mbkm_report');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'research_poster');('research_poster');
            }
        }

        if ($request->input('siakad_supervisor_screenshot', false)) {
            if (! $skripsiDefense->siakad_supervisor_screenshot || $request->input('siakad_supervisor_screenshot') !== $skripsiDefense->siakad_supervisor_screenshot->file_name) {
                if ($skripsiDefense->siakad_supervisor_screenshot) {
                    $skripsiDefense->siakad_supervisor_screenshot->delete();
                }
                $filePath = storage_path('tmp/uploads/' . basename($request->input('siakad_supervisor_screenshot')));
                $skripsiDefense->addMediaWithCustomName($filePath, 'siakad_supervisor_screenshot');('siakad_supervisor_screenshot');
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
                $filePath = storage_path('tmp/uploads/' . basename($file));
                $skripsiDefense->addMediaWithCustomName($filePath, 'supervision_logbook');('supervision_logbook');
            }
        }

        return redirect()->route('frontend.skripsi-defenses.index');
    }

    public function show(SkripsiDefense $skripsiDefense)
    {
        abort_if(Gate::denies('skripsi_defense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiDefense->load('application', 'created_by');

        return view('frontend.skripsiDefenses.show', compact('skripsiDefense'));
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
