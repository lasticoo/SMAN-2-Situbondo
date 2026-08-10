<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('module', 50);
            $table->unsignedBigInteger('reference_id');
            $table->string('file_url', 255);
            $table->integer('original_size_kb')->nullable();
            $table->integer('optimized_size_kb')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
