<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Services\ImageOptimizerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of the galleries ordered by sort_order.
     */
    public function index(Request $request): View
    {
        $galleries = Gallery::search($request->search)
            ->ordered()
            ->with('creator')
            ->paginate(12)
            ->withQueryString();

        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Store a newly created gallery item in storage.
     */
    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $sortOrder = $request->filled('sort_order')
            ? (int) $request->sort_order
            : ((Gallery::max('sort_order') ?? 0) + 1);

        $gallery = Gallery::create([
            'activity_name' => $request->activity_name,
            'activity_date' => $request->activity_date,
            'sort_order' => $sortOrder,
            'created_by' => Auth::guard('admin')->id(),
            'photo_url' => '',
        ]);

        $file = $request->file('photo') ?? $request->file('photo_url');
        if ($file) {
            $photoPath = ImageOptimizerService::compressAndLog(
                $file,
                'galleries',
                $gallery->id,
                'galleries'
            );
            $gallery->update(['photo_url' => $photoPath]);
        }

        Cache::forget('user_media_galleries');
        Cache::forget('landing_galleries');

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Galeri foto berhasil ditambahkan.');
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = [
            'activity_name' => $request->activity_name,
            'activity_date' => $request->activity_date,
            'sort_order' => (int) $request->sort_order,
        ];

        $file = $request->file('photo') ?? $request->file('photo_url');
        if ($file) {
            ImageOptimizerService::deleteAndClean($gallery->photo_url, 'galleries', $gallery->id);

            $data['photo_url'] = ImageOptimizerService::compressAndLog(
                $file,
                'galleries',
                $gallery->id,
                'galleries'
            );
        }

        $gallery->update($data);

        Cache::forget('user_media_galleries');
        Cache::forget('landing_galleries');

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Data galeri foto berhasil diperbarui.');
    }

    /**
     * Remove the specified gallery item from storage.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        ImageOptimizerService::deleteAndClean($gallery->photo_url, 'galleries', $gallery->id);
        $gallery->delete();

        Cache::forget('user_media_galleries');
        Cache::forget('landing_galleries');

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Data galeri foto berhasil dihapus.');
    }
}
