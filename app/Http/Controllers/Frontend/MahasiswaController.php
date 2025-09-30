<?php

namespace App\Http\Controllers\Frontend;

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

class MahasiswaController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('mahasiswa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswas = Mahasiswa::with(['prodi', 'jenjang', 'fakultas'])->get();

        return view('frontend.mahasiswas.index', compact('mahasiswas'));
    }

    public function create()
    {
        abort_if(Gate::denies('mahasiswa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.mahasiswas.create', compact('fakultas', 'jenjangs', 'prodis'));
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $mahasiswa = Mahasiswa::create($request->all());

        return redirect()->route('frontend.mahasiswas.index');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswa->load('prodi', 'jenjang', 'fakultas');

        return view('frontend.mahasiswas.edit', compact('fakultas', 'jenjangs', 'mahasiswa', 'prodis'));
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->all());

        return redirect()->route('frontend.mahasiswas.index');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswa->load('prodi', 'jenjang', 'fakultas');

        return view('frontend.mahasiswas.show', compact('mahasiswa'));
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
