<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah kolom sort_order ke tabel popups.
     * Kolom ini tidak ada di ERD awal, ditambahkan untuk memenuhi
     * Acceptance Criteria AD-02: "Admin dapat menentukan urutan tampil pop-up event".
     * Perubahan ini disebutkan eksplisit di deskripsi PR.
     */
    public function up(): void
    {
        Schema::table('popups', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('popups', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
