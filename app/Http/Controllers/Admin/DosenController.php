<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyDosenRequest;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Keilmuan;
use App\Models\Prodi;
use App\Models\ResearchGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('dosen_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Dosen::with(['prodi', 'jenjang', 'fakultas', 'keilmuans', 'riset_grup'])->select(sprintf('%s.*', (new Dosen)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'dosen_show';
                $editGate      = 'dosen_edit';
                $deleteGate    = 'dosen_delete';
                $crudRoutePart = 'dosens';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('nip', function ($row) {
                return $row->nip ? $row->nip : '';
            });
            $table->editColumn('nidn', function ($row) {
                return $row->nidn ? $row->nidn : '';
            });
            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->editColumn('tempat_lahir', function ($row) {
                return $row->tempat_lahir ? $row->tempat_lahir : '';
            });

            $table->editColumn('gender', function ($row) {
                return $row->gender ? Dosen::GENDER_SELECT[$row->gender] : '';
            });
            $table->addColumn('prodi_name', function ($row) {
                return $row->prodi ? $row->prodi->name : '';
            });

            $table->editColumn('keilmuan', function ($row) {
                $labels = [];
                foreach ($row->keilmuans as $keilmuan) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $keilmuan->name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('riset_grup_name', function ($row) {
                return $row->riset_grup ? $row->riset_grup->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'prodi', 'keilmuan', 'riset_grup']);

            return $table->make(true);
        }

        return view('admin.dosens.index');
    }

    public function create()
    {
        abort_if(Gate::denies('dosen_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $keilmuans = Keilmuan::pluck('name', 'id');

        $riset_grups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.dosens.create', compact('fakultas', 'jenjangs', 'keilmuans', 'prodis', 'riset_grups'));
    }

    public function store(StoreDosenRequest $request)
    {
        $dosen = Dosen::create($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));

        return redirect()->route('admin.dosens.index');
    }

    public function edit(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $keilmuans = Keilmuan::pluck('name', 'id');

        $riset_grups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $dosen->load('prodi', 'jenjang', 'fakultas', 'keilmuans', 'riset_grup');

        return view('admin.dosens.edit', compact('dosen', 'fakultas', 'jenjangs', 'keilmuans', 'prodis', 'riset_grups'));
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen)
    {
        $dosen->update($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));

        return redirect()->route('admin.dosens.index');
    }

    public function show(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosen->load('prodi', 'jenjang', 'fakultas', 'keilmuans', 'riset_grup');

        return view('admin.dosens.show', compact('dosen'));
    }

    public function destroy(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosen->delete();

        return back();
    }

    public function massDestroy(MassDestroyDosenRequest $request)
    {
        $dosens = Dosen::find(request('ids'));

        foreach ($dosens as $dosen) {
            $dosen->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
