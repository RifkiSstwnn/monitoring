<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_uptimes', function (Blueprint $table) {
            $table->id();
            $table->string('laptop_sn', 50);
            $table->foreign('laptop_sn')->references('sn')->on('laptops')->onDelete('cascade');
            $table->date('date'); // Tanggal penggunaan
            $table->time('time'); // Waktu penggunaan
            $table->integer('uptime'); // Uptime dalam detik
            $table->integer('idle_time'); // Idle time dalam detik
            $table->unique(['laptop_sn', 'date', 'time']);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_uptimes');
    }
};
