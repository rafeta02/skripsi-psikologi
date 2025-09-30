<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class RuangController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ruang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ruang::query()->select(sprintf('%s.*', (new Ruang)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ruang_show';
                $editGate      = 'ruang_edit';
                $deleteGate    = 'ruang_delete';
                $crudRoutePart = 'ruangs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });
            $table->editColumn('capacity', function ($row) {
                return $row->capacity ? $row->capacity : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.ruangs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('ruang_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruangs.create');
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

        return redirect()->route('admin.ruangs.index');
    }

    public function edit(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruangs.edit', compact('ruang'));
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

        return redirect()->route('admin.ruangs.index');
    }

    public function show(Ruang $ruang)
    {
        abort_if(Gate::denies('ruang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruangs.show', compact('ruang'));
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
