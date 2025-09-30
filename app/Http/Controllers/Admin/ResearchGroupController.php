<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyResearchGroupRequest;
use App\Http\Requests\StoreResearchGroupRequest;
use App\Http\Requests\UpdateResearchGroupRequest;
use App\Models\ResearchGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ResearchGroupController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('research_group_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ResearchGroup::query()->select(sprintf('%s.*', (new ResearchGroup)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'research_group_show';
                $editGate      = 'research_group_edit';
                $deleteGate    = 'research_group_delete';
                $crudRoutePart = 'research-groups';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.researchGroups.index');
    }

    public function create()
    {
        abort_if(Gate::denies('research_group_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.researchGroups.create');
    }

    public function store(StoreResearchGroupRequest $request)
    {
        $researchGroup = ResearchGroup::create($request->all());

        return redirect()->route('admin.research-groups.index');
    }

    public function edit(ResearchGroup $researchGroup)
    {
        abort_if(Gate::denies('research_group_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.researchGroups.edit', compact('researchGroup'));
    }

    public function update(UpdateResearchGroupRequest $request, ResearchGroup $researchGroup)
    {
        $researchGroup->update($request->all());

        return redirect()->route('admin.research-groups.index');
    }

    public function show(ResearchGroup $researchGroup)
    {
        abort_if(Gate::denies('research_group_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.researchGroups.show', compact('researchGroup'));
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
