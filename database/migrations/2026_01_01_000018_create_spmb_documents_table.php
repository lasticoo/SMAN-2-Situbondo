<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spmb_info_id')->constrained('spmb_info')->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('file_url', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_documents');
    }
};
