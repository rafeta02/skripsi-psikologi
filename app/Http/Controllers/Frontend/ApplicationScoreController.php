<?php

namespace App\Http\Controllers\Frontend;

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

class ApplicationScoreController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_score_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationScores = ApplicationScore::with(['application_result_defence', 'examiner'])->get();

        return view('frontend.applicationScores.index', compact('applicationScores'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_score_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_result_defences = ApplicationResultDefense::pluck('result', 'id')->prepend(trans('global.pleaseSelect'), '');

        $examiners = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.applicationScores.create', compact('application_result_defences', 'examiners'));
    }

    public function store(StoreApplicationScoreRequest $request)
    {
        $applicationScore = ApplicationScore::create($request->all());

        return redirect()->route('frontend.application-scores.index');
    }

    public function edit(ApplicationScore $applicationScore)
    {
        abort_if(Gate::denies('application_score_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application_result_defences = ApplicationResultDefense::pluck('result', 'id')->prepend(trans('global.pleaseSelect'), '');

        $examiners = Dosen::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $applicationScore->load('application_result_defence', 'examiner');

        return view('frontend.applicationScores.edit', compact('applicationScore', 'application_result_defences', 'examiners'));
    }

    public function update(UpdateApplicationScoreRequest $request, ApplicationScore $applicationScore)
    {
        $applicationScore->update($request->all());

        return redirect()->route('frontend.application-scores.index');
    }

    public function show(ApplicationScore $applicationScore)
    {
        abort_if(Gate::denies('application_score_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationScore->load('application_result_defence', 'examiner');

        return view('frontend.applicationScores.show', compact('applicationScore'));
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
