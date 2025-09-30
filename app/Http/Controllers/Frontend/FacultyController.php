<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyFacultyRequest;
use App\Http\Requests\StoreFacultyRequest;
use App\Http\Requests\UpdateFacultyRequest;
use App\Models\Faculty;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FacultyController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('faculty_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faculties = Faculty::all();

        return view('frontend.faculties.index', compact('faculties'));
    }

    public function create()
    {
        abort_if(Gate::denies('faculty_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.faculties.create');
    }

    public function store(StoreFacultyRequest $request)
    {
        $faculty = Faculty::create($request->all());

        return redirect()->route('frontend.faculties.index');
    }

    public function edit(Faculty $faculty)
    {
        abort_if(Gate::denies('faculty_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.faculties.edit', compact('faculty'));
    }

    public function update(UpdateFacultyRequest $request, Faculty $faculty)
    {
        $faculty->update($request->all());

        return redirect()->route('frontend.faculties.index');
    }

    public function show(Faculty $faculty)
    {
        abort_if(Gate::denies('faculty_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.faculties.show', compact('faculty'));
    }

    public function destroy(Faculty $faculty)
    {
        abort_if(Gate::denies('faculty_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faculty->delete();

        return back();
    }

    public function massDestroy(MassDestroyFacultyRequest $request)
    {
        $faculties = Faculty::find(request('ids'));

        foreach ($faculties as $faculty) {
            $faculty->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
