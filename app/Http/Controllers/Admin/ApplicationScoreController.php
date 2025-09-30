<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationScoreRequest;
use App\Http\Requests\StoreApplicationScoreRequest;
use App\Http\Requests\UpdateApplicationScoreRequest;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationScore;
use App\Models\Dosen;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ApplicationScoreController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('application_score_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ApplicationScore::with(['application_result_defence', 'examiner'])->select(sprintf('%s.*', (new ApplicationScore)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'application_score_show';
                $editGate      = 'application_score_edit';
                $deleteGate    = 'application_score_delete';
                $crudRoutePart = 'application-scores';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('application_result_defence_result', function ($row) {
                return $row->application_result_defence ? $row->application_result_defence->result : '';
            });

            $table->addColumn('examiner_nama', function ($row) {
                return $row->examiner ? $row->examiner->nama : '';
            });

            $table->editColumn('score', function ($row) {
                return $row->score ? $row->score : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'application_result_defence', 'examiner']);

            return $table->make(true);
        }

        return view('admin.applicationScores.index');
    }

    public function create()
    {
        abort_if(Gate::denies('application_score_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_result_defences = ApplicationResultDefense::pluck('result', 'id')->prepend(trans('global.pleaseSelect'), '');

        $examiners = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.applicationScores.create', compact('application_result_defences', 'examiners'));
    }

    public function store(StoreApplicationScoreRequest $request)
    {
        $applicationScore = ApplicationScore::create($request->all());

        return redirect()->route('admin.application-scores.index');
    }

    public function edit(ApplicationScore $applicationScore)
    {
        abort_if(Gate::denies('application_score_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_result_defences = ApplicationResultDefense::pluck('result', 'id')->prepend(trans('global.pleaseSelect'), '');

        $examiners = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationScore->load('application_result_defence', 'examiner');

        return view('admin.applicationScores.edit', compact('applicationScore', 'application_result_defences', 'examiners'));
    }

    public function update(UpdateApplicationScoreRequest $request, ApplicationScore $applicationScore)
    {
        $applicationScore->update($request->all());

        return redirect()->route('admin.application-scores.index');
    }

    public function show(ApplicationScore $applicationScore)
    {
        abort_if(Gate::denies('application_score_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationScore->load('application_result_defence', 'examiner');

        return view('admin.applicationScores.show', compact('applicationScore'));
    }

    public function destroy(ApplicationScore $applicationScore)
    {
        abort_if(Gate::denies('application_score_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationScore->delete();

        return back();
    }

    public function massDestroy(MassDestroyApplicationScoreRequest $request)
    {
        $applicationScores = ApplicationScore::find(request('ids'));

        foreach ($applicationScores as $applicationScore) {
            $applicationScore->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
