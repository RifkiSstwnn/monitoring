<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laptops', function (Blueprint $table) {
            $table->string('OWNER', 50);
            $table->string('PHASE', 50);
            $table->string('SITE', 20);
            $table->string('COMP NAME', 25);
            $table->string('COMP NAME REV', 25);
            $table->string('SN', 50)->unique();
            $table->string('TYPE UNIT', 100);
            $table->string('CLASSIFICATION UNIT', 25);
            $table->string('CATEGORY UNIT', 25);
            $table->string('OS', 20);
            $table->string('USER NAME', 50);
            $table->string('NRP', 25); // Menyimpan NRP sebagai string
            $table->string('DIVISI', 15);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};
