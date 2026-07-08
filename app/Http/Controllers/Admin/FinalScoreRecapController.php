<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationResultDefense;
use App\Services\FinalScoreRecapService;
use Illuminate\Http\Request;

class FinalScoreRecapController extends Controller
{
    public function __construct(private FinalScoreRecapService $recapService)
    {
    }

    public function index(Request $request)
    {
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
        $detail = $this->recapService->getDetail($applicationResultDefense);
        $componentLabels = $this->recapService->getComponentLabels();

        return view('admin.finalScoreRecap.partials.detail', compact('detail', 'componentLabels'));
    }
}
