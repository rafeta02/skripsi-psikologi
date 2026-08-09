<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementController extends Controller
{
    public function index()
    {
        abort_if(! Auth::user()?->mahasiswa_id, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcements = Announcement::query()
            ->visible()
            ->forAudience('mahasiswa')
            ->ordered()
            ->paginate(10);

        return view('mahasiswa.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        abort_if(! Auth::user()?->mahasiswa_id, Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(! $this->canView($announcement), Response::HTTP_NOT_FOUND);

        return view('mahasiswa.announcements.show', compact('announcement'));
    }

    private function canView(Announcement $announcement): bool
    {
        if ($announcement->status !== 'published') {
            return false;
        }

        if ($announcement->published_at && $announcement->published_at->isFuture()) {
            return false;
        }

        if ($announcement->expires_at && $announcement->expires_at->isPast()) {
            return false;
        }

        return in_array($announcement->audience, ['all', 'mahasiswa'], true);
    }
}
