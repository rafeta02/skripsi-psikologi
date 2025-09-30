<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyKeilmuanRequest;
use App\Http\Requests\StoreKeilmuanRequest;
use App\Http\Requests\UpdateKeilmuanRequest;
use App\Models\Keilmuan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KeilmuanController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('keilmuan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $keilmuans = Keilmuan::all();

        return view('frontend.keilmuans.index', compact('keilmuans'));
    }

    public function create()
    {
        abort_if(Gate::denies('keilmuan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.keilmuans.create');
    }

    public function store(StoreKeilmuanRequest $request)
    {
        $keilmuan = Keilmuan::create($request->all());

        return redirect()->route('frontend.keilmuans.index');
    }

    public function edit(Keilmuan $keilmuan)
    {
        abort_if(Gate::denies('keilmuan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.keilmuans.edit', compact('keilmuan'));
    }

    public function update(UpdateKeilmuanRequest $request, Keilmuan $keilmuan)
    {
        $keilmuan->update($request->all());

        return redirect()->route('frontend.keilmuans.index');
    }

    public function show(Keilmuan $keilmuan)
    {
        abort_if(Gate::denies('keilmuan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.keilmuans.show', compact('keilmuan'));
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
