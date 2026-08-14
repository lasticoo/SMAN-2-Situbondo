<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\News;
use App\Models\SpmbDocument;
use App\Models\SpmbInfo;
use App\Models\Student;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real-time summary statistics.
     */
    public function index()
    {
        $currentAdmin = Auth::guard('admin')->user();

        // 1. Berita Stats
        $newsTotal = News::count();
        $newsPublished = News::where('status', 'published')->count();
        $newsDraft = News::where('status', 'draft')->count();

        // 2. Pengumuman Stats
        $announcementTotal = Announcement::count();
        $announcementPublished = Announcement::where('status', 'published')->count();
        $announcementDraft = Announcement::where('status', 'draft')->count();

        // 3. Siswa Stats
        $studentTotal = Student::count();

        // 4. Pegawai Stats
        $employeeTotal = Employee::count();
        $employeeActive = Employee::where('is_active', true)->count();
        $employeeInactive = Employee::where('is_active', false)->count();

        // 5. Galeri Stats
        $galleryTotal = Gallery::count();

        // 6. Video Stats
        $videoTotal = Video::count();

        // 7. Contact Messages Stats
        $contactMessageTotal = ContactMessage::count();
        $contactMessageUnread = ContactMessage::where('status', 'unread')->count();
        $contactMessageRead = ContactMessage::where('status', 'read')->count();

        // 8. SPMB Summary
        $activeSpmbInfo = SpmbInfo::latest()->first();
        $spmbDocumentTotal = SpmbDocument::count();

        // 9. Recent Activity & Messages
        $latestNews = News::latest()->take(3)->get();
        $latestAnnouncements = Announcement::latest()->take(3)->get();
        $recentContactMessages = ContactMessage::orderBy('id', 'desc')->take(4)->get();

        $stats = [
            'news' => [
                'total' => $newsTotal,
                'published' => $newsPublished,
                'draft' => $newsDraft,
            ],
            'announcements' => [
                'total' => $announcementTotal,
                'published' => $announcementPublished,
                'draft' => $announcementDraft,
            ],
            'students' => [
                'total' => $studentTotal,
            ],
            'employees' => [
                'total' => $employeeTotal,
                'active' => $employeeActive,
                'inactive' => $employeeInactive,
            ],
            'galleries' => [
                'total' => $galleryTotal,
            ],
            'videos' => [
                'total' => $videoTotal,
            ],
            'contact_messages' => [
                'total' => $contactMessageTotal,
                'unread' => $contactMessageUnread,
                'read' => $contactMessageRead,
            ],
            'spmb' => [
                'info' => $activeSpmbInfo,
                'document_total' => $spmbDocumentTotal,
            ],
        ];

        return view('admin.dashboard', compact(
            'currentAdmin',
            'stats',
            'latestNews',
            'latestAnnouncements',
            'recentContactMessages'
        ));
    }
}

