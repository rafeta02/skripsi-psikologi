<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyMahasiswaRequest;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mahasiswa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mahasiswa::with(['prodi', 'jenjang', 'fakultas'])->select(sprintf('%s.*', (new Mahasiswa)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mahasiswa_show';
                $editGate      = 'mahasiswa_edit';
                $deleteGate    = 'mahasiswa_delete';
                $crudRoutePart = 'mahasiswas';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('nim', function ($row) {
                return $row->nim ? $row->nim : '';
            });
            $table->editColumn('nama', function ($row) {
                return $row->nama ? $row->nama : '';
            });
            $table->editColumn('tahun_masuk', function ($row) {
                return $row->tahun_masuk ? $row->tahun_masuk : '';
            });
            $table->editColumn('kelas', function ($row) {
                return $row->kelas ? Mahasiswa::KELAS_SELECT[$row->kelas] : '';
            });
            $table->addColumn('prodi_name', function ($row) {
                return $row->prodi ? $row->prodi->name : '';
            });

            $table->addColumn('jenjang_name', function ($row) {
                return $row->jenjang ? $row->jenjang->name : '';
            });

            $table->addColumn('fakultas_name', function ($row) {
                return $row->fakultas ? $row->fakultas->name : '';
            });

            $table->editColumn('tempat_lahir', function ($row) {
                return $row->tempat_lahir ? $row->tempat_lahir : '';
            });
            $table->editColumn('gender', function ($row) {
                return $row->gender ? Mahasiswa::GENDER_SELECT[$row->gender] : '';
            });
            $table->editColumn('alamat', function ($row) {
                return $row->alamat ? $row->alamat : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'prodi', 'jenjang', 'fakultas']);

            return $table->make(true);
        }

        return view('admin.mahasiswas.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mahasiswa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mahasiswas.create', compact('fakultas', 'jenjangs', 'prodis'));
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $mahasiswa = Mahasiswa::create($request->all());

        return redirect()->route('admin.mahasiswas.index');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswa->load('prodi', 'jenjang', 'fakultas');

        return view('admin.mahasiswas.edit', compact('fakultas', 'jenjangs', 'mahasiswa', 'prodis'));
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->all());

        return redirect()->route('admin.mahasiswas.index');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswa->load('prodi', 'jenjang', 'fakultas');

        return view('admin.mahasiswas.show', compact('mahasiswa'));
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswa->delete();

        return back();
    }

    public function massDestroy(MassDestroyMahasiswaRequest $request)
    {
        $mahasiswas = Mahasiswa::find(request('ids'));

        foreach ($mahasiswas as $mahasiswa) {
            $mahasiswa->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
