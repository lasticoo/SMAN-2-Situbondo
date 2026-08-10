<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('skl_templates')->nullOnDelete();
            $table->foreignId('import_batch_id')->nullable()->constrained('import_batches')->nullOnDelete();
            $table->boolean('is_announced')->default(false);
            $table->dateTime('announced_at')->nullable();
            $table->string('nisn', 20);
            $table->foreign('nisn')->references('nisn')->on('students')->onDelete('cascade');
            $table->string('student_name', 150);
            $table->string('graduation_status', 50);
            $table->string('document_url', 255)->nullable();
            $table->boolean('is_downloadable')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduation_records');
    }
};
