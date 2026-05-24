<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySkripsiSeminarRequest;
use App\Http\Requests\StoreSkripsiSeminarRequest;
use App\Http\Requests\UpdateSkripsiSeminarRequest;
use App\Models\Application;
use App\Models\SkripsiSeminar;
use App\Services\FormAccessService;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SkripsiSeminarController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('skripsi_seminar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminars = SkripsiSeminar::with(['application', 'created_by', 'media'])->get();

        return view('frontend.skripsiSeminars.index', compact('skripsiSeminars'));
    }

    public function create()
    {
        abort_if(Gate::denies('skripsi_seminar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.skripsiSeminars.create', compact('activeApplication'));
    }

    public function store(StoreSkripsiSeminarRequest $request)
    {
        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessSkripsiSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.skripsi-seminars.index')
                ->with('error', $access['message']);
        }

        // Create new Application for seminar stage
        $seminarApplication = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => 'skripsi',
            'stage' => 'seminar',
            'status' => 'submitted',
            'submitted_at' => now()->format('d-m-Y H:i:s'),
        ]);

        // Create Skripsi Seminar with seminar application
        $data = $request->all();
        $data['application_id'] = $seminarApplication->id;
        
        $skripsiSeminar = SkripsiSeminar::create($data);

        // Handle file uploads - Direct upload (not via Dropzone temp)
        if ($request->hasFile('proposal_document')) {
            $skripsiSeminar->addMedia($request->file('proposal_document'))
                ->toMediaCollection('proposal_document');
        }

        if ($request->hasFile('approval_document')) {
            $skripsiSeminar->addMedia($request->file('approval_document'))
                ->toMediaCollection('approval_document');
        }

        if ($request->hasFile('plagiarism_document')) {
            $skripsiSeminar->addMedia($request->file('plagiarism_document'))
                ->toMediaCollection('plagiarism_document');
        }

        return redirect()->route('frontend.skripsi-seminars.index')
            ->with('success', 'Pendaftaran seminar berhasil dikirim!');
    }

    public function edit(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->load('application', 'created_by');

        return view('frontend.skripsiSeminars.edit', compact('skripsiSeminar'));
    }

    public function update(UpdateSkripsiSeminarRequest $request, SkripsiSeminar $skripsiSeminar)
    {
        $skripsiSeminar->update($request->all());

        // Handle proposal document
        if ($request->hasFile('proposal_document')) {
            // Delete old document if exists
            $skripsiSeminar->clearMediaCollection('proposal_document');
            // Add new document
            $skripsiSeminar->addMedia($request->file('proposal_document'))
                ->toMediaCollection('proposal_document');
        }

        // Handle approval document
        if ($request->hasFile('approval_document')) {
            $skripsiSeminar->clearMediaCollection('approval_document');
            $skripsiSeminar->addMedia($request->file('approval_document'))
                ->toMediaCollection('approval_document');
        }

        // Handle plagiarism document
        if ($request->hasFile('plagiarism_document')) {
            $skripsiSeminar->clearMediaCollection('plagiarism_document');
            $skripsiSeminar->addMedia($request->file('plagiarism_document'))
                ->toMediaCollection('plagiarism_document');
        }

        return redirect()->route('frontend.skripsi-seminars.index')
            ->with('success', 'Pendaftaran seminar berhasil diupdate!');
    }

    public function show(SkripsiSeminar $skripsiSeminar)
    {
        abort_if(Gate::denies('skripsi_seminar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skripsiSeminar->load('application', 'created_by');

        return view('frontend.skripsiSeminars.show', compact('skripsiSeminar'));
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
