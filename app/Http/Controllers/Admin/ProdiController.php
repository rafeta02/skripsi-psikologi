<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyProdiRequest;
use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Prodi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProdiController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('prodi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Prodi::with(['jenjang', 'fakultas'])->select(sprintf('%s.*', (new Prodi)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'prodi_show';
                $editGate      = 'prodi_edit';
                $deleteGate    = 'prodi_delete';
                $crudRoutePart = 'prodis';

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
            $table->addColumn('jenjang_name', function ($row) {
                return $row->jenjang ? $row->jenjang->name : '';
            });

            $table->addColumn('fakultas_name', function ($row) {
                return $row->fakultas ? $row->fakultas->name : '';
            });

            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->status ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'jenjang', 'fakultas', 'status']);

            return $table->make(true);
        }

        return view('admin.prodis.index');
    }

    public function create()
    {
        abort_if(Gate::denies('prodi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.prodis.create', compact('fakultas', 'jenjangs'));
    }

    public function store(StoreProdiRequest $request)
    {
        $prodi = Prodi::create($request->all());

        return redirect()->route('admin.prodis.index');
    }

    public function edit(Prodi $prodi)
    {
        abort_if(Gate::denies('prodi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prodi->load('jenjang', 'fakultas');

        return view('admin.prodis.edit', compact('fakultas', 'jenjangs', 'prodi'));
    }

    public function update(UpdateProdiRequest $request, Prodi $prodi)
    {
        $prodi->update($request->all());

        return redirect()->route('admin.prodis.index');
    }

    public function show(Prodi $prodi)
    {
        abort_if(Gate::denies('prodi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodi->load('jenjang', 'fakultas');

        return view('admin.prodis.show', compact('prodi'));
    }

    public function destroy(Prodi $prodi)
    {
        abort_if(Gate::denies('prodi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodi->delete();

        return back();
    }

    public function massDestroy(MassDestroyProdiRequest $request)
    {
        $prodis = Prodi::find(request('ids'));

        foreach ($prodis as $prodi) {
            $prodi->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
