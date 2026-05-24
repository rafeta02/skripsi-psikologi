<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMbkmSeminarRequest;
use App\Http\Requests\StoreMbkmSeminarRequest;
use App\Http\Requests\UpdateMbkmSeminarRequest;
use App\Models\Application;
use App\Models\MbkmSeminar;
use App\Services\FormAccessService;
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

        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessMbkmSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.mbkm-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        return view('frontend.mbkmSeminars.create', compact('activeApplication'));
    }

    public function store(StoreMbkmSeminarRequest $request)
    {
        // Check if student can access this form
        $formAccessService = new FormAccessService();
        $access = $formAccessService->canAccessMbkmSeminar(auth()->user()->mahasiswa_id);

        if (!$access['allowed']) {
            return redirect()->route('frontend.mbkm-seminars.index')
                ->with('error', $access['message']);
        }

        $activeApplication = $access['application'];

        // Create new Application for seminar stage
        $seminarApplication = Application::create([
            'mahasiswa_id' => auth()->user()->mahasiswa_id,
            'type' => 'mbkm',
            'stage' => 'seminar',
            'status' => 'submitted',
            'submitted_at' => now()->format('d-m-Y H:i:s'),
        ]);

        // Create MBKM Seminar with seminar application
        $data = $request->all();
        $data['application_id'] = $seminarApplication->id;
        
        $mbkmSeminar = MbkmSeminar::create($data);

        // Handle file uploads - Direct upload (not via Dropzone temp)
        if ($request->hasFile('proposal_document')) {
            $mbkmSeminar->addMedia($request->file('proposal_document'))
                ->toMediaCollection('proposal_document');
        }

        if ($request->hasFile('approval_document')) {
            $mbkmSeminar->addMedia($request->file('approval_document'))
                ->toMediaCollection('approval_document');
        }

        if ($request->hasFile('plagiarism_document')) {
            $mbkmSeminar->addMedia($request->file('plagiarism_document'))
                ->toMediaCollection('plagiarism_document');
        }

        return redirect()->route('frontend.mbkm-seminars.index')
            ->with('success', 'Pendaftaran seminar MBKM berhasil dikirim!');
    }

    public function edit(MbkmSeminar $mbkmSeminar)
    {
        abort_if(Gate::denies('mbkm_seminar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmSeminar->load('application', 'created_by');

        return view('frontend.mbkmSeminars.edit', compact('mbkmSeminar'));
    }

    public function update(UpdateMbkmSeminarRequest $request, MbkmSeminar $mbkmSeminar)
    {
        $mbkmSeminar->update($request->all());

        // Handle proposal document
        if ($request->hasFile('proposal_document')) {
            $mbkmSeminar->clearMediaCollection('proposal_document');
            $mbkmSeminar->addMedia($request->file('proposal_document'))
                ->toMediaCollection('proposal_document');
        }

        // Handle approval document
        if ($request->hasFile('approval_document')) {
            $mbkmSeminar->clearMediaCollection('approval_document');
            $mbkmSeminar->addMedia($request->file('approval_document'))
                ->toMediaCollection('approval_document');
        }

        // Handle plagiarism document
        if ($request->hasFile('plagiarism_document')) {
            $mbkmSeminar->clearMediaCollection('plagiarism_document');
            $mbkmSeminar->addMedia($request->file('plagiarism_document'))
                ->toMediaCollection('plagiarism_document');
        }

        return redirect()->route('frontend.mbkm-seminars.index')
            ->with('success', 'Pendaftaran seminar MBKM berhasil diupdate!');
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
