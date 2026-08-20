<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NewsService
{
    public const CATEGORIES = [
        'Umum',
        'Akademik',
        'Kesiswaan',
        'Kegiatan',
        'Prestasi',
        'Informasi',
    ];

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const MODULE_NAME = 'news';

    public const STORAGE_FOLDER = 'news';

    /**
     * Get paginated list of news with optional search and status filters.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('news')
            ->leftJoin('admins', 'news.created_by', '=', 'admins.id')
            ->select('news.*', 'admins.name as author_name');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('news.title', 'like', "%{$search}%")
                    ->orWhere('news.summary', 'like', "%{$search}%")
                    ->orWhere('news.content', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('news.category', $filters['category']);
        }

        if (! empty($filters['status']) && in_array($filters['status'], [self::STATUS_DRAFT, self::STATUS_PUBLISHED])) {
            $query->where('news.status', $filters['status']);
        }

        return $query->latest('news.published_at')
            ->latest('news.id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get statistics for news items.
     */
    public function getStats(): array
    {
        return [
            'total' => DB::table('news')->count(),
            'published' => DB::table('news')->where('status', self::STATUS_PUBLISHED)->count(),
            'draft' => DB::table('news')->where('status', self::STATUS_DRAFT)->count(),
        ];
    }

    /**
     * Create a new news record using Query Builder.
     */
    public function createNews(array $data, ?UploadedFile $thumbnail, int $adminId): int
    {
        return DB::transaction(function () use ($data, $thumbnail, $adminId) {
            $now = now();

            $newsId = DB::table('news')->insertGetId([
                'title' => $data['title'],
                'category' => $data['category'] ?? 'Umum',
                'thumbnail_url' => '',
                'summary' => $data['summary'],
                'content' => $data['content'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($thumbnail) {
                $thumbnailPath = ImageOptimizerService::compressAndLog(
                    $thumbnail,
                    self::MODULE_NAME,
                    $newsId,
                    self::STORAGE_FOLDER
                );

                DB::table('news')
                    ->where('id', $newsId)
                    ->update(['thumbnail_url' => $thumbnailPath]);
            }

            Cache::forget('landing_news_published');

            return $newsId;
        });
    }

    /**
     * Update an existing news record using Query Builder.
     */
    public function updateNews(int $id, array $data, ?UploadedFile $thumbnail): bool
    {
        return DB::transaction(function () use ($id, $data, $thumbnail) {
            $news = DB::table('news')->where('id', $id)->first();

            if (! $news) {
                return false;
            }

            $updateData = [
                'title' => $data['title'],
                'category' => $data['category'] ?? 'Umum',
                'summary' => $data['summary'],
                'content' => $data['content'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'updated_at' => now(),
            ];

            if ($thumbnail) {
                ImageOptimizerService::deleteAndClean($news->thumbnail_url, self::MODULE_NAME, $id);

                $updateData['thumbnail_url'] = ImageOptimizerService::compressAndLog(
                    $thumbnail,
                    self::MODULE_NAME,
                    $id,
                    self::STORAGE_FOLDER
                );
            }

            $updated = DB::table('news')->where('id', $id)->update($updateData);

            Cache::forget('landing_news_published');

            return (bool) $updated;
        });
    }

    /**
     * Delete a news record using Query Builder.
     */
    public function deleteNews(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $news = DB::table('news')->where('id', $id)->first();

            if (! $news) {
                return false;
            }

            ImageOptimizerService::deleteAndClean($news->thumbnail_url, self::MODULE_NAME, $id);

            $deleted = DB::table('news')->where('id', $id)->delete();

            Cache::forget('landing_news_published');

            return (bool) $deleted;
        });
    }

    /**
     * Get published news items that are ready to display for User/Landing page.
     */
    public function getPublished(?int $limit = null)
    {
        $query = DB::table('news')
            ->leftJoin('admins', 'news.created_by', '=', 'admins.id')
            ->select('news.*', 'admins.name as author_name')
            ->where('news.status', self::STATUS_PUBLISHED)
            ->where('news.published_at', '<=', now())
            ->latest('news.published_at')
            ->latest('news.id');

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }
}
