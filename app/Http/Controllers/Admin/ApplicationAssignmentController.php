<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class ApplicationAssignmentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_assignment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationAssignment::with(['application', 'lecturer'])->select(sprintf('%s.*', (new ApplicationAssignment)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_assignment_show';
                $editGate      = 'application_assignment_edit';
                $deleteGate    = 'application_assignment_delete';
                $crudRoutePart = 'application-assignments';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('application_status', function ($row) {
                return $row->application ? $row->application->status : '';
            });

            $table->addColumn('lecturer_nidn', function ($row) {
                return $row->lecturer ? $row->lecturer->nidn : '';
            });

            $table->editColumn('role', function ($row) {
                return $row->role ? ApplicationAssignment::ROLE_SELECT[$row->role] : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? ApplicationAssignment::STATUS_SELECT[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application', 'lecturer']);

            return $table->make(true);
        }

        return view('admin.applicationAssignments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_assignment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lecturers = Dosen::pluck('nidn', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationAssignments.create', compact('applications', 'lecturers'));
    }

    public function store(StoreApplicationAssignmentRequest $request)
    {
        $applicationAssignment = ApplicationAssignment::create($request->all());

        return redirect()->route('admin.application-assignments.index');
    }

    public function edit(ApplicationAssignment $applicationAssignment)
    {
        abort_if(Gate::denies('application_assignment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = Application::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lecturers = Dosen::pluck('nidn', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationAssignment->load('application', 'lecturer');

        return view('admin.applicationAssignments.edit', compact('applicationAssignment', 'applications', 'lecturers'));
    }

    public function update(UpdateApplicationAssignmentRequest $request, ApplicationAssignment $applicationAssignment)
    {
        $applicationAssignment->update($request->all());

        return redirect()->route('admin.application-assignments.index');
    }

    public function show(ApplicationAssignment $applicationAssignment)
    {
        abort_if(Gate::denies('application_assignment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationAssignment->load('application', 'lecturer');

        return view('admin.applicationAssignments.show', compact('applicationAssignment'));
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
