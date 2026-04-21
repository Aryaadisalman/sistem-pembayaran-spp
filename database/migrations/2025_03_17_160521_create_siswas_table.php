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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('siswa_id');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('nis', 10)->unique();
            $table->string('kelas', 20);
            $table->date('tanggal_masuk')->nullable();
            // $table->string('tahun_ajaran', 10)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
