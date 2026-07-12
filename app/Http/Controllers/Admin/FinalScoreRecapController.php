<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationResultDefense;
use App\Services\FinalScoreRecapService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinalScoreRecapController extends Controller
{
    public function __construct(private FinalScoreRecapService $recapService)
    {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('final_score_recap_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $filter = $request->get('filter', 'finalized');
        if (!in_array($filter, ['finalized', 'all'], true)) {
            $filter = 'finalized';
        }

        $recap = $this->recapService->getRecap($filter);
        $summary = $this->recapService->getSummary($recap);
        $componentLabels = $this->recapService->getComponentLabels();
        $componentKeys = $this->recapService->getComponentKeys();

        return view('admin.finalScoreRecap.index', compact(
            'recap',
            'summary',
            'componentLabels',
            'componentKeys',
            'filter'
        ));
    }

    public function detail(ApplicationResultDefense $applicationResultDefense)
    {
        abort_if(Gate::denies('final_score_recap_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $detail = $this->recapService->getDetail($applicationResultDefense);
        $componentLabels = $this->recapService->getComponentLabels();

        return view('admin.finalScoreRecap.partials.detail', compact('detail', 'componentLabels'));
    }
}
