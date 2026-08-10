<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('color', function (Blueprint $table) {
            $table->id();
            $table->string('primary_color', 50)->default('#1e3a8a');
            $table->string('secondary_color', 50)->default('#0284c7');
            $table->foreignId('updated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('color');
    }
};
