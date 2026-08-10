<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skl_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('content')->nullable();
            $table->string('nomor_surat_format', 255)->nullable();
            $table->string('kop_surat_url', 255)->nullable();
            $table->string('ttd_name', 150)->nullable();
            $table->string('ttd_position', 100)->nullable();
            $table->string('ttd_signature_url', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skl_templates');
    }
};
