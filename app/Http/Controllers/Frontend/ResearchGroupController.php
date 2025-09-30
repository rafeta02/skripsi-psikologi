<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyResearchGroupRequest;
use App\Http\Requests\StoreResearchGroupRequest;
use App\Http\Requests\UpdateResearchGroupRequest;
use App\Models\ResearchGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResearchGroupController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('research_group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $researchGroups = ResearchGroup::all();

        return view('frontend.researchGroups.index', compact('researchGroups'));
    }

    public function create()
    {
        abort_if(Gate::denies('research_group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.researchGroups.create');
    }

    public function store(StoreResearchGroupRequest $request)
    {
        $researchGroup = ResearchGroup::create($request->all());

        return redirect()->route('frontend.research-groups.index');
    }

    public function edit(ResearchGroup $researchGroup)
    {
        abort_if(Gate::denies('research_group_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.researchGroups.edit', compact('researchGroup'));
    }

    public function update(UpdateResearchGroupRequest $request, ResearchGroup $researchGroup)
    {
        $researchGroup->update($request->all());

        return redirect()->route('frontend.research-groups.index');
    }

    public function show(ResearchGroup $researchGroup)
    {
        abort_if(Gate::denies('research_group_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.researchGroups.show', compact('researchGroup'));
    }

    public function destroy(ResearchGroup $researchGroup)
    {
        abort_if(Gate::denies('research_group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $researchGroup->delete();

        return back();
    }

    public function massDestroy(MassDestroyResearchGroupRequest $request)
    {
        $researchGroups = ResearchGroup::find(request('ids'));

        foreach ($researchGroups as $researchGroup) {
            $researchGroup->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
