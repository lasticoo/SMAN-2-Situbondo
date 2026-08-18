<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use App\Services\ImageOptimizerService;
use App\Services\YoutubeUrlParser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class VideoController extends Controller
{
    /**
     * Tampilkan daftar video YouTube terurut.
     */
    public function index(Request $request): View
    {
        $videos = Video::search($request->search)
            ->ordered()
            ->with('creator')
            ->paginate(12)
            ->withQueryString();

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Simpan data video baru ke database.
     */
    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $youtubeId = $request->filled('youtube_id')
            ? trim($request->youtube_id)
            : YoutubeUrlParser::parseId($request->youtube_url);

        $sortOrder = $request->filled('sort_order')
            ? (int) $request->sort_order
            : ((Video::max('sort_order') ?? 0) + 1);

        $video = Video::create([
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId ?? '',
            'title' => $request->title,
            'sort_order' => $sortOrder,
            'created_by' => Auth::guard('admin')->id(),
            'thumbnail_url' => null,
        ]);

        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');
        if ($file) {
            $thumbnailPath = ImageOptimizerService::compressAndLog(
                $file,
                'videos',
                $video->id,
                'videos'
            );
            $video->update(['thumbnail_url' => $thumbnailPath]);
        }

        $this->clearVideoCaches();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Video YouTube berhasil ditambahkan.');
    }

    /**
     * Perbarui data video YouTube.
     */
    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $youtubeId = $request->filled('youtube_id')
            ? trim($request->youtube_id)
            : (YoutubeUrlParser::parseId($request->youtube_url) ?? $video->youtube_id);

        $data = [
            'youtube_url' => $request->youtube_url,
            'youtube_id' => $youtubeId,
            'title' => $request->title,
            'sort_order' => (int) $request->sort_order,
        ];

        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');
        if ($file) {
            ImageOptimizerService::deleteAndClean($video->thumbnail_url, 'videos', $video->id);

            $data['thumbnail_url'] = ImageOptimizerService::compressAndLog(
                $file,
                'videos',
                $video->id,
                'videos'
            );
        }

        $video->update($data);

        $this->clearVideoCaches();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Data video YouTube berhasil diperbarui.');
    }

    /**
     * Hapus data video beserta file thumbnail jika ada.
     */
    public function destroy(Video $video): RedirectResponse
    {
        ImageOptimizerService::deleteAndClean($video->thumbnail_url, 'videos', $video->id);
        $video->delete();

        $this->clearVideoCaches();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Data video YouTube berhasil dihapus.');
    }

    /**
     * Helper untuk membersihkan cache data video publik.
     */
    private function clearVideoCaches(): void
    {
        Cache::forget('landing_active_videos');
        Cache::forget('user_media_videos');
    }
}
