<?php

namespace App\Http\Controllers\Frontend;

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

class MbkmGroupMemberController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('mbkm_group_member_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmGroupMembers = MbkmGroupMember::with(['mbkm_registration', 'mahasiswa'])->get();

        return view('frontend.mbkmGroupMembers.index', compact('mbkmGroupMembers'));
    }

    public function create()
    {
        abort_if(Gate::denies('mbkm_group_member_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkm_registrations = MbkmRegistration::pluck('title_mbkm', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.mbkmGroupMembers.create', compact('mahasiswas', 'mbkm_registrations'));
    }

    public function store(StoreMbkmGroupMemberRequest $request)
    {
        $mbkmGroupMember = MbkmGroupMember::create($request->all());

        return redirect()->route('frontend.mbkm-group-members.index');
    }

    public function edit(MbkmGroupMember $mbkmGroupMember)
    {
        abort_if(Gate::denies('mbkm_group_member_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkm_registrations = MbkmRegistration::pluck('title_mbkm', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mahasiswas = Mahasiswa::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mbkmGroupMember->load('mbkm_registration', 'mahasiswa');

        return view('frontend.mbkmGroupMembers.edit', compact('mahasiswas', 'mbkmGroupMember', 'mbkm_registrations'));
    }

    public function update(UpdateMbkmGroupMemberRequest $request, MbkmGroupMember $mbkmGroupMember)
    {
        $mbkmGroupMember->update($request->all());

        return redirect()->route('frontend.mbkm-group-members.index');
    }

    public function show(MbkmGroupMember $mbkmGroupMember)
    {
        abort_if(Gate::denies('mbkm_group_member_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mbkmGroupMember->load('mbkm_registration', 'mahasiswa');

        return view('frontend.mbkmGroupMembers.show', compact('mbkmGroupMember'));
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
