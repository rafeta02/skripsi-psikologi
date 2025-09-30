<?php

namespace App\Http\Controllers\Frontend;

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

class DosenController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('dosen_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosens = Dosen::with(['prodi', 'jenjang', 'fakultas', 'keilmuans', 'riset_grup'])->get();

        return view('frontend.dosens.index', compact('dosens'));
    }

    public function create()
    {
        abort_if(Gate::denies('dosen_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $keilmuans = Keilmuan::pluck('name', 'id');

        $riset_grups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.dosens.create', compact('fakultas', 'jenjangs', 'keilmuans', 'prodis', 'riset_grups'));
    }

    public function store(StoreDosenRequest $request)
    {
        $dosen = Dosen::create($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));

        return redirect()->route('frontend.dosens.index');
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

        return view('frontend.dosens.edit', compact('dosen', 'fakultas', 'jenjangs', 'keilmuans', 'prodis', 'riset_grups'));
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen)
    {
        $dosen->update($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));

        return redirect()->route('frontend.dosens.index');
    }

    public function show(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosen->load('prodi', 'jenjang', 'fakultas', 'keilmuans', 'riset_grup');

        return view('frontend.dosens.show', compact('dosen'));
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
