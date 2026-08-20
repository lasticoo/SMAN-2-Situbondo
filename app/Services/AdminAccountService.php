<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAccountService
{
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super Admin',
        self::ROLE_ADMIN => 'Admin',
    ];

    public const MODULE_NAME = 'admins';

    public const STORAGE_FOLDER = 'admins';

    /**
     * Get paginated list of admin accounts with search and role filters.
     */
    public function getAllPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Admin::query();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['role']) && array_key_exists($filters['role'], self::ROLES)) {
            $query->where('role', $filters['role']);
        }

        return $query->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get admin account statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Admin::count(),
            'super_admin' => Admin::where('role', self::ROLE_SUPER_ADMIN)->count(),
            'admin' => Admin::where('role', self::ROLE_ADMIN)->count(),
            'active' => Admin::where('is_active', true)->count(),
            'inactive' => Admin::where('is_active', false)->count(),
        ];
    }

    /**
     * Create a new admin account.
     */
    public function createAccount(array $data, ?UploadedFile $avatar): Admin
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? self::ROLE_SUPER_ADMIN,
            'avatar_url' => null,
            'is_active' => true,
        ]);

        if ($avatar) {
            $avatarPath = ImageOptimizerService::compressAndLog(
                $avatar,
                self::MODULE_NAME,
                $admin->id,
                self::STORAGE_FOLDER
            );

            $admin->update(['avatar_url' => $avatarPath]);
        }

        return $admin;
    }

    /**
     * Update an existing admin account.
     */
    public function updateAccount(int $id, array $data, ?UploadedFile $avatar, int $currentAdminId): Admin
    {
        $admin = Admin::findOrFail($id);

        if ($id === $currentAdminId && isset($data['is_active']) && ! $data['is_active']) {
            throw ValidationException::withMessages([
                'is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang digunakan.',
            ]);
        }

        $updatePayload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'] ?? $admin->role,
        ];

        if (! empty($data['password'])) {
            $updatePayload['password'] = Hash::make($data['password']);
        }

        if (isset($data['is_active']) && $id !== $currentAdminId) {
            $updatePayload['is_active'] = (bool) $data['is_active'];
        }

        if ($avatar) {
            ImageOptimizerService::deleteAndClean($admin->avatar_url, self::MODULE_NAME, $id);

            $updatePayload['avatar_url'] = ImageOptimizerService::compressAndLog(
                $avatar,
                self::MODULE_NAME,
                $id,
                self::STORAGE_FOLDER
            );
        }

        $admin->update($updatePayload);

        return $admin;
    }

    /**
     * Toggle active status of an admin account with self-deactivation protection.
     */
    public function toggleActive(int $id, int $currentAdminId): Admin
    {
        if ($id === $currentAdminId) {
            throw ValidationException::withMessages([
                'is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang digunakan.',
            ]);
        }

        $admin = Admin::findOrFail($id);
        $admin->update(['is_active' => ! $admin->is_active]);

        return $admin;
    }

    /**
     * Delete an admin account with self-delete protection and avatar cleanup.
     */
    public function deleteAccount(int $id, int $currentAdminId): bool
    {
        if ($id === $currentAdminId) {
            throw ValidationException::withMessages([
                'id' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.',
            ]);
        }

        $admin = Admin::findOrFail($id);

        ImageOptimizerService::deleteAndClean($admin->avatar_url, self::MODULE_NAME, $id);

        return (bool) $admin->delete();
    }
}
