<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\DosenPortalService;
use Symfony\Component\HttpFoundation\Response;

class AnnouncementController extends Controller
{
    public function index()
    {
        abort_if(! (new DosenPortalService())->resolveDosenId(), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcements = Announcement::query()
            ->visible()
            ->forAudience('dosen')
            ->ordered()
            ->paginate(10);

        return view('dosen.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        abort_if(! (new DosenPortalService())->resolveDosenId(), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if(! $this->canView($announcement), Response::HTTP_NOT_FOUND);

        return view('dosen.announcements.show', compact('announcement'));
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

        return in_array($announcement->audience, ['all', 'dosen'], true);
    }
}
