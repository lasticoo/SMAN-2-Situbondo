<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ContactMessageService
{
    public const STATUS_UNREAD = 'unread';

    public const STATUS_READ = 'read';

    /**
     * Get paginated list of contact messages with search and status filters via Query Builder.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('contact_messages');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['status']) && in_array($filters['status'], [self::STATUS_UNREAD, self::STATUS_READ])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get message statistics (total, unread, read).
     */
    public function getStats(): array
    {
        return [
            'total' => DB::table('contact_messages')->count(),
            'unread' => DB::table('contact_messages')->where('status', self::STATUS_UNREAD)->count(),
            'read' => DB::table('contact_messages')->where('status', self::STATUS_READ)->count(),
        ];
    }

    /**
     * Find a contact message by ID using Query Builder.
     */
    public function find(int $id): ?object
    {
        return DB::table('contact_messages')->where('id', $id)->first();
    }

    /**
     * Mark a contact message as read using Query Builder.
     */
    public function markAsRead(int $id): bool
    {
        return (bool) DB::table('contact_messages')
            ->where('id', $id)
            ->update(['status' => self::STATUS_READ]);
    }

    /**
     * Mark a contact message as unread using Query Builder.
     */
    public function markAsUnread(int $id): bool
    {
        return (bool) DB::table('contact_messages')
            ->where('id', $id)
            ->update(['status' => self::STATUS_UNREAD]);
    }

    /**
     * Toggle status between read and unread using Query Builder.
     */
    public function toggleStatus(int $id): bool
    {
        $message = $this->find($id);

        if (! $message) {
            return false;
        }

        $newStatus = ($message->status === self::STATUS_UNREAD) ? self::STATUS_READ : self::STATUS_UNREAD;

        return (bool) DB::table('contact_messages')
            ->where('id', $id)
            ->update(['status' => $newStatus]);
    }

    /**
     * Delete a contact message using Query Builder.
     */
    public function delete(int $id): bool
    {
        return (bool) DB::table('contact_messages')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Store a new contact message submitted by User via Query Builder.
     */
    public function storeUserMessage(array $data): int
    {
        return DB::table('contact_messages')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => self::STATUS_UNREAD,
            'created_at' => now(),
        ]);
    }

    /**
     * Count unread messages (reusable for sidebar/dashboard).
     */
    public function countUnread(): int
    {
        return DB::table('contact_messages')->where('status', self::STATUS_UNREAD)->count();
    }

    /**
     * Mask name keeping the first character of each word, replacing the rest with '*'.
     * Example: "rizal" -> "r****", "budi santoso" -> "b*** s******"
     */
    public function maskName(?string $name): string
    {
        if (empty($name)) {
            return '-';
        }

        $words = explode(' ', trim($name));
        $masked = array_map(function ($word) {
            $len = mb_strlen($word);
            if ($len <= 1) {
                return $word;
            }

            return mb_substr($word, 0, 1).str_repeat('*', $len - 1);
        }, $words);

        return implode(' ', $masked);
    }

    /**
     * Mask email username part after the first character.
     *
     * Example: "rizal@gmail.com" -> "r****@gmail.com"
     */
    public function maskEmail(?string $email): string
    {
        if (empty($email) || ! str_contains($email, '@')) {
            return '-';
        }

        [$user, $domain] = explode('@', $email, 2);
        $userLen = mb_strlen($user);

        $maskedUser = ($userLen <= 1) ? $user : mb_substr($user, 0, 1).str_repeat('*', $userLen - 1);

        return $maskedUser.'@'.$domain;
    }

    /**
     * Mask phone number keeping first 3 digits and last 2 digits.
     * Example: "08123456789" -> "081******89"
     */
    public function maskPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '-';
        }

        $clean = preg_replace('/[^\d+]/', '', $phone);
        $len = mb_strlen($clean);

        if ($len <= 4) {
            return mb_substr($clean, 0, 1).str_repeat('*', max(0, $len - 1));
        }

        return mb_substr($clean, 0, 3).str_repeat('*', max(1, $len - 5)).mb_substr($clean, -2);
    }

    /**
     * Get all contact messages for CSV export with masked sensitive data.
     */
    public function getExportData(): array
    {
        $messages = DB::table('contact_messages')
            ->orderBy('created_at', 'desc')
            ->get();

        $rows = [];
        foreach ($messages as $index => $msg) {
            $rows[] = [
                'no' => $index + 1,
                'name' => $this->maskName($msg->name),
                'email' => $this->maskEmail($msg->email),
                'phone' => $this->maskPhone($msg->phone),
                'subject' => $msg->subject,
                'message' => $msg->message,
                'status' => strtoupper($msg->status),
                'created_at' => $msg->created_at ? Carbon::parse($msg->created_at)->format('d-m-Y H:i') : '-',
            ];
        }

        return $rows;
    }
}
