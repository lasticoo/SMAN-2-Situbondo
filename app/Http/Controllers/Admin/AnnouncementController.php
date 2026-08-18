<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Http\Requests\Admin\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Categories list for dropdown selection.
     */
    public const CATEGORIES = [
        'Umum',
        'Akademik',
        'Kesiswaan',
        'Kegiatan',
        'Sarpras',
        'Informasi',
    ];

    /**
     * Display a listing of announcements.
     */
    public function index(Request $request): View
    {
        $categories = self::CATEGORIES;
        $statuses = [
            'published' => 'Published',
            'draft' => 'Draft',
        ];

        $announcements = Announcement::query()
            ->search($request->search)
            ->filterCategory($request->category)
            ->when($request->filled('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest('published_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Announcement::count(),
            'published' => Announcement::where('status', 'published')->count(),
            'draft' => Announcement::where('status', 'draft')->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'categories', 'statuses', 'stats'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $announcement = Announcement::create([
            'title' => $request->title,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->input('content'),
            'status' => $request->status,
            'published_at' => $request->published_at,
            'created_by' => Auth::guard('admin')->id(),
            'thumbnail_url' => '',
        ]);

        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');
        if ($file) {
            $thumbnailPath = ImageOptimizerService::compressAndLog(
                $file,
                'announcements',
                $announcement->id,
                'announcement'
            );
            $announcement->update(['thumbnail_url' => $thumbnailPath]);
        }

        Cache::forget('landing_announcements_top5');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->input('content'),
            'status' => $request->status,
            'published_at' => $request->published_at,
        ];

        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');
        if ($file) {
            ImageOptimizerService::deleteAndClean($announcement->thumbnail_url, 'announcements', $announcement->id);

            $data['thumbnail_url'] = ImageOptimizerService::compressAndLog(
                $file,
                'announcements',
                $announcement->id,
                'announcement'
            );
        }

        $announcement->update($data);

        Cache::forget('landing_announcements_top5');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        ImageOptimizerService::deleteAndClean($announcement->thumbnail_url, 'announcements', $announcement->id);
        $announcement->delete();

        Cache::forget('landing_announcements_top5');

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
