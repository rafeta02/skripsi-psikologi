<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyKeilmuanRequest;
use App\Http\Requests\StoreKeilmuanRequest;
use App\Http\Requests\UpdateKeilmuanRequest;
use App\Models\Keilmuan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class KeilmuanController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('keilmuan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Keilmuan::query()->select(sprintf('%s.*', (new Keilmuan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'keilmuan_show';
                $editGate      = 'keilmuan_edit';
                $deleteGate    = 'keilmuan_delete';
                $crudRoutePart = 'keilmuans';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.keilmuans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('keilmuan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.keilmuans.create');
    }

    public function store(StoreKeilmuanRequest $request)
    {
        $keilmuan = Keilmuan::create($request->all());

        return redirect()->route('admin.keilmuans.index');
    }

    public function edit(Keilmuan $keilmuan)
    {
        abort_if(Gate::denies('keilmuan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.keilmuans.edit', compact('keilmuan'));
    }

    public function update(UpdateKeilmuanRequest $request, Keilmuan $keilmuan)
    {
        $keilmuan->update($request->all());

        return redirect()->route('admin.keilmuans.index');
    }

    public function show(Keilmuan $keilmuan)
    {
        abort_if(Gate::denies('keilmuan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.keilmuans.show', compact('keilmuan'));
    }

    public function destroy(Keilmuan $keilmuan)
    {
        abort_if(Gate::denies('keilmuan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $keilmuan->delete();

        return back();
    }

    public function massDestroy(MassDestroyKeilmuanRequest $request)
    {
        $keilmuans = Keilmuan::find(request('ids'));

        foreach ($keilmuans as $keilmuan) {
            $keilmuan->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
