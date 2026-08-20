<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminAccountRequest;
use App\Http\Requests\Admin\UpdateAdminAccountRequest;
use App\Services\AdminAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    protected AdminAccountService $adminAccountService;

    public function __construct(AdminAccountService $adminAccountService)
    {
        $this->adminAccountService = $adminAccountService;
    }

    /**
     * Display a listing of admin accounts.
     */
    public function index(Request $request): View
    {
        $admins = $this->adminAccountService->getAllPaginated([
            'search' => $request->search,
            'role' => $request->role,
        ], 10);

        $stats = $this->adminAccountService->getStats();
        $roles = AdminAccountService::ROLES;

        return view('admin.admin_account.index', compact('admins', 'stats', 'roles'));
    }

    /**
     * Store a newly created admin account.
     */
    public function store(StoreAdminAccountRequest $request): RedirectResponse
    {
        $avatar = $request->file('avatar') ?? $request->file('avatar_url');

        $this->adminAccountService->createAccount($request->validated(), $avatar);

        return redirect()
            ->route('admin.admin_accounts.index')
            ->with('success', 'Akun admin baru berhasil ditambahkan.');
    }

    /**
     * Update the specified admin account in storage.
     */
    public function update(UpdateAdminAccountRequest $request, int $id): RedirectResponse
    {
        $currentAdminId = Auth::guard('admin')->id();
        $avatar = $request->file('avatar') ?? $request->file('avatar_url');

        $this->adminAccountService->updateAccount($id, $request->validated(), $avatar, $currentAdminId);

        return redirect()
            ->route('admin.admin_accounts.index')
            ->with('success', 'Data akun admin berhasil diperbarui.');
    }

    /**
     * Toggle the active status of an admin account.
     */
    public function toggleActive(int $id): RedirectResponse
    {
        $currentAdminId = Auth::guard('admin')->id();

        $this->adminAccountService->toggleActive($id, $currentAdminId);

        return redirect()
            ->route('admin.admin_accounts.index')
            ->with('success', 'Status aktif akun admin berhasil diperbarui.');
    }

    /**
     * Remove the specified admin account from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $currentAdminId = Auth::guard('admin')->id();

        $this->adminAccountService->deleteAccount($id, $currentAdminId);

        return redirect()
            ->route('admin.admin_accounts.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }
}
