<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAnnouncementRequest;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('announcement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Announcement::with('created_by')->select(sprintf('%s.*', (new Announcement)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'announcement_show';
                $editGate      = 'announcement_edit';
                $deleteGate    = 'announcement_delete';
                $crudRoutePart = 'announcements';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('title', fn ($row) => $row->title ?? '');
            $table->editColumn('audience', fn ($row) => $row->audienceLabel());
            $table->editColumn('status', fn ($row) => $row->statusLabel());
            $table->editColumn('is_pinned', fn ($row) => $row->is_pinned ? 'Ya' : 'Tidak');
            $table->editColumn('published_at', fn ($row) => $row->published_at?->format('d M Y H:i') ?? '-');
            $table->editColumn('expires_at', fn ($row) => $row->expires_at?->format('d M Y H:i') ?? '-');

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.announcements.index');
    }

    public function create()
    {
        abort_if(Gate::denies('announcement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.announcements.create');
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $data = $this->preparePayload($request->validated());
        $data['created_by_id'] = Auth::id();

        Announcement::create($data);

        return redirect()->route('admin.announcements.index');
    }

    public function edit(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $announcement->update($this->preparePayload($request->validated()));

        return redirect()->route('admin.announcements.index');
    }

    public function show(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcement->load('created_by');

        return view('admin.announcements.show', compact('announcement'));
    }

    public function destroy(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcement->delete();

        return back();
    }

    public function massDestroy(MassDestroyAnnouncementRequest $request)
    {
        Announcement::whereIn('id', $request->input('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function preparePayload(array $data): array
    {
        $data['is_pinned'] = (bool) ($data['is_pinned'] ?? false);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        return $data;
    }
}
