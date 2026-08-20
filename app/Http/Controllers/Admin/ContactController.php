<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ContactMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    protected ContactMessageService $contactMessageService;

    public function __construct(ContactMessageService $contactMessageService)
    {
        $this->contactMessageService = $contactMessageService;
    }

    /**
     * Display a listing of contact messages using Query Builder via ContactMessageService.
     */
    public function index(Request $request): View
    {
        $messages = $this->contactMessageService->getAllPaginated([
            'search' => $request->search,
            'status' => $request->status,
        ], 10);

        $stats = $this->contactMessageService->getStats();

        return view('admin.contact.index', compact('messages', 'stats'));
    }

    /**
     * Fetch a specific message detail and automatically mark it as read.
     */
    public function show(int $id): JsonResponse
    {
        $message = $this->contactMessageService->find($id);

        if (! $message) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan tidak ditemukan.',
            ], 404);
        }

        if ($message->status === ContactMessageService::STATUS_UNREAD) {
            $this->contactMessageService->markAsRead($id);
            $message->status = ContactMessageService::STATUS_READ;
        }

        return response()->json([
            'success' => true,
            'data' => $message,
        ]);
    }

    /**
     * Toggle status of a message between read and unread.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $updated = $this->contactMessageService->toggleStatus($id);

        if (! $updated) {
            return redirect()
                ->route('admin.contact.index')
                ->with('error', 'Pesan tidak ditemukan.');
        }

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Status pesan berhasil diperbarui.');
    }

    /**
     * Remove a message from storage via ContactMessageService.
     */
    public function destroy(int $id): RedirectResponse
    {
        $deleted = $this->contactMessageService->delete($id);

        if (! $deleted) {
            return redirect()
                ->route('admin.contact.index')
                ->with('error', 'Pesan tidak ditemukan.');
        }

        return redirect()
            ->route('admin.contact.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Download CSV recap of contact messages with masked sender names, emails, and phone numbers.
     */
    public function exportCsv(): StreamedResponse
    {
        $exportData = $this->contactMessageService->getExportData();
        $filename = 'rekap_pesan_contact_masked_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($exportData) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No',
                'Nama Pengirim (Masked)',
                'Email (Masked)',
                'No Telepon (Masked)',
                'Subjek Pesan',
                'Isi Pesan',
                'Status',
                'Waktu Masuk',
            ]);

            foreach ($exportData as $row) {
                fputcsv($file, [
                    $row['no'],
                    $row['name'],
                    $row['email'],
                    $row['phone'],
                    $row['subject'],
                    $row['message'],
                    $row['status'],
                    $row['created_at'],
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
