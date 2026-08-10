<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_info', function (Blueprint $table) {
            $table->id();
            $table->string('banner_url', 255)->nullable();
            $table->text('schedule_info')->nullable();
            $table->text('requirements_info')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_info');
    }
};
