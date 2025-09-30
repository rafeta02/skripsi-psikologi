<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyJenjangRequest;
use App\Http\Requests\StoreJenjangRequest;
use App\Http\Requests\UpdateJenjangRequest;
use App\Models\Jenjang;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JenjangController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('jenjang_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjangs = Jenjang::all();

        return view('frontend.jenjangs.index', compact('jenjangs'));
    }

    public function create()
    {
        abort_if(Gate::denies('jenjang_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.jenjangs.create');
    }

    public function store(StoreJenjangRequest $request)
    {
        $jenjang = Jenjang::create($request->all());

        return redirect()->route('frontend.jenjangs.index');
    }

    public function edit(Jenjang $jenjang)
    {
        abort_if(Gate::denies('jenjang_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.jenjangs.edit', compact('jenjang'));
    }

    public function update(UpdateJenjangRequest $request, Jenjang $jenjang)
    {
        $jenjang->update($request->all());

        return redirect()->route('frontend.jenjangs.index');
    }

    public function show(Jenjang $jenjang)
    {
        abort_if(Gate::denies('jenjang_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.jenjangs.show', compact('jenjang'));
    }

    public function destroy(Jenjang $jenjang)
    {
        abort_if(Gate::denies('jenjang_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $jenjang->delete();

        return back();
    }

    public function massDestroy(MassDestroyJenjangRequest $request)
    {
        $jenjangs = Jenjang::find(request('ids'));

        foreach ($jenjangs as $jenjang) {
            $jenjang->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
