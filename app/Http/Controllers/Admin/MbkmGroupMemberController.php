<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMbkmGroupMemberRequest;
use App\Http\Requests\StoreMbkmGroupMemberRequest;
use App\Http\Requests\UpdateMbkmGroupMemberRequest;
use App\Models\Mahasiswa;
use App\Models\MbkmGroupMember;
use App\Models\MbkmRegistration;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MbkmGroupMemberController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('mbkm_group_member_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = MbkmGroupMember::with(['mbkm_registration', 'mahasiswa'])->select(sprintf('%s.*', (new MbkmGroupMember)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mbkm_group_member_show';
                $editGate      = 'mbkm_group_member_edit';
                $deleteGate    = 'mbkm_group_member_delete';
                $crudRoutePart = 'mbkm-group-members';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('mbkm_registration_title_mbkm', function ($row) {
                return $row->mbkm_registration ? $row->mbkm_registration->title_mbkm : '';
            });

            $table->addColumn('mahasiswa_nama', function ($row) {
                return $row->mahasiswa ? $row->mahasiswa->nama : '';
            });

            $table->editColumn('role', function ($row) {
                return $row->role ? MbkmGroupMember::ROLE_SELECT[$row->role] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'mbkm_registration', 'mahasiswa']);

            return $table->make(true);
        }

        return view('admin.mbkmGroupMembers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_group_member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkm_registrations = MbkmRegistration::pluck('title_mbkm', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mbkmGroupMembers.create', compact('mahasiswas', 'mbkm_registrations'));
    }

    public function store(StoreMbkmGroupMemberRequest $request)
    {
        $mbkmGroupMember = MbkmGroupMember::create($request->all());

        return redirect()->route('admin.mbkm-group-members.index');
    }

    public function edit(MbkmGroupMember $mbkmGroupMember)
    {
        abort_if(Gate::denies('mbkm_group_member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkm_registrations = MbkmRegistration::pluck('title_mbkm', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmGroupMember->load('mbkm_registration', 'mahasiswa');

        return view('admin.mbkmGroupMembers.edit', compact('mahasiswas', 'mbkmGroupMember', 'mbkm_registrations'));
    }

    public function update(UpdateMbkmGroupMemberRequest $request, MbkmGroupMember $mbkmGroupMember)
    {
        $mbkmGroupMember->update($request->all());

        return redirect()->route('admin.mbkm-group-members.index');
    }

    public function show(MbkmGroupMember $mbkmGroupMember)
    {
        abort_if(Gate::denies('mbkm_group_member_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmGroupMember->load('mbkm_registration', 'mahasiswa');

        return view('admin.mbkmGroupMembers.show', compact('mbkmGroupMember'));
    }

    public function destroy(MbkmGroupMember $mbkmGroupMember)
    {
        abort_if(Gate::denies('mbkm_group_member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmGroupMember->delete();

        return back();
    }

    public function massDestroy(MassDestroyMbkmGroupMemberRequest $request)
    {
        $mbkmGroupMembers = MbkmGroupMember::find(request('ids'));

        foreach ($mbkmGroupMembers as $mbkmGroupMember) {
            $mbkmGroupMember->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
