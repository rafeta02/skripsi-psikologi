<?php

namespace App\Http\Controllers\Frontend;

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

class ProdiController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('prodi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodis = Prodi::with(['jenjang', 'fakultas'])->get();

        return view('frontend.prodis.index', compact('prodis'));
    }

    public function create()
    {
        abort_if(Gate::denies('prodi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.prodis.create', compact('fakultas', 'jenjangs'));
    }

    public function store(StoreProdiRequest $request)
    {
        $prodi = Prodi::create($request->all());

        return redirect()->route('frontend.prodis.index');
    }

    public function edit(Prodi $prodi)
    {
        abort_if(Gate::denies('prodi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $prodi->load('jenjang', 'fakultas');

        return view('frontend.prodis.edit', compact('fakultas', 'jenjangs', 'prodi'));
    }

    public function update(UpdateProdiRequest $request, Prodi $prodi)
    {
        $prodi->update($request->all());

        return redirect()->route('frontend.prodis.index');
    }

    public function show(Prodi $prodi)
    {
        abort_if(Gate::denies('prodi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prodi->load('jenjang', 'fakultas');

        return view('frontend.prodis.show', compact('prodi'));
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
