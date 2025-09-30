<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationAssignmentRequest;
use App\Http\Requests\StoreApplicationAssignmentRequest;
use App\Http\Requests\UpdateApplicationAssignmentRequest;
use App\Models\Application;
use App\Models\ApplicationAssignment;
use App\Models\Dosen;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAssignmentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_assignment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationAssignments = ApplicationAssignment::with(['application', 'lecturer'])->get();

        return view('frontend.applicationAssignments.index', compact('applicationAssignments'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_assignment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lecturers = Dosen::pluck('nidn', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.applicationAssignments.create', compact('applications', 'lecturers'));
    }

    public function store(StoreApplicationAssignmentRequest $request)
    {
        $applicationAssignment = ApplicationAssignment::create($request->all());

        return redirect()->route('frontend.application-assignments.index');
    }

    public function edit(ApplicationAssignment $applicationAssignment)
    {
        abort_if(Gate::denies('application_assignment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lecturers = Dosen::pluck('nidn', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationAssignment->load('application', 'lecturer');

        return view('frontend.applicationAssignments.edit', compact('applicationAssignment', 'applications', 'lecturers'));
    }

    public function update(UpdateApplicationAssignmentRequest $request, ApplicationAssignment $applicationAssignment)
    {
        $applicationAssignment->update($request->all());

        return redirect()->route('frontend.application-assignments.index');
    }

    public function show(ApplicationAssignment $applicationAssignment)
    {
        abort_if(Gate::denies('application_assignment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationAssignment->load('application', 'lecturer');

        return view('frontend.applicationAssignments.show', compact('applicationAssignment'));
    }

    public function destroy(ApplicationAssignment $applicationAssignment)
    {
        abort_if(Gate::denies('application_assignment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationAssignment->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationAssignmentRequest $request)
    {
        $applicationAssignments = ApplicationAssignment::find(request('ids'));

        foreach ($applicationAssignments as $applicationAssignment) {
            $applicationAssignment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
