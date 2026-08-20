<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsRequest;
use App\Http\Requests\Admin\UpdateNewsRequest;
use App\Services\NewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of news items using Query Builder via NewsService.
     */
    public function index(Request $request): View
    {
        $categories = NewsService::CATEGORIES;
        $statuses = [
            'published' => 'Published',
            'draft' => 'Draft',
        ];

        $news = $this->newsService->getAllPaginated([
            'search' => $request->search,
            'category' => $request->category,
            'status' => $request->status,
        ], 10);

        $stats = $this->newsService->getStats();

        return view('admin.news.index', compact('news', 'statuses', 'stats', 'categories'));
    }

    /**
     * Store a newly created news item in storage via NewsService.
     */
    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');
        $adminId = (int) Auth::guard('admin')->id();

        $this->newsService->createNews($request->validated(), $file, $adminId);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Update the specified news item in storage via NewsService.
     */
    public function update(UpdateNewsRequest $request, int $id): RedirectResponse
    {
        $file = $request->file('thumbnail') ?? $request->file('thumbnail_url');

        $this->newsService->updateNews($id, $request->validated(), $file);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified news item from storage via NewsService.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->newsService->deleteNews($id);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}
