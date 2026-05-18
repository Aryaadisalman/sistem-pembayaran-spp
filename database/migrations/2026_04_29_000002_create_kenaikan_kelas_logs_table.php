<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kenaikan_kelas_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->string('nama_siswa');
            $table->string('kelas_lama');
            $table->string('kelas_baru');               // "LULUS" jika kelas XII naik
            $table->string('tahun_ajaran');             // contoh: "2026/2027"
            $table->enum('status', ['naik', 'lulus', 'tidak_naik']);
            $table->timestamps();

            $table->foreign('siswa_id')->references('siswa_id')->on('siswa')->onDelete('cascade');
            $table->index(['tahun_ajaran', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kenaikan_kelas_logs');
    }
};
