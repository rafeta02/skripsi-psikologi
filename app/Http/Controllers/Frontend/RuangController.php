<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRuangRequest;
use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Models\Ruang;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class RuangController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ruang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangs = Ruang::with(['media'])->get();

        return view('frontend.ruangs.index', compact('ruangs'));
    }

    public function create()
    {
        abort_if(Gate::denies('ruang_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.ruangs.create');
    }

    public function store(StoreRuangRequest $request)
    {
        $ruang = Ruang::create($request->all());

        if ($request->input('image', false)) {
            $ruang->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ruang->id]);
        }

        return redirect()->route('frontend.ruangs.index');
    }

    public function edit(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.ruangs.edit', compact('ruang'));
    }

    public function update(UpdateRuangRequest $request, Ruang $ruang)
    {
        $ruang->update($request->all());

        if ($request->input('image', false)) {
            if (! $ruang->image || $request->input('image') !== $ruang->image->file_name) {
                if ($ruang->image) {
                    $ruang->image->delete();
                }
                $ruang->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($ruang->image) {
            $ruang->image->delete();
        }

        return redirect()->route('frontend.ruangs.index');
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.ruangs.show', compact('ruang'));
    }

    public function destroy(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruang->delete();

        return back();
    }

    public function massDestroy(MassDestroyRuangRequest $request)
    {
        $ruangs = Ruang::find(request('ids'));

        foreach ($ruangs as $ruang) {
            $ruang->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ruang_create') && Gate::denies('ruang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Ruang();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
