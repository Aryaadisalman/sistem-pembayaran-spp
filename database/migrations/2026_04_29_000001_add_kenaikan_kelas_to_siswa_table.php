<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable()->after('kelas');          // contoh: "2026/2027"
            $table->boolean('tidak_naik_kelas')->default(false)->after('tahun_ajaran');  // ditandai admin
            $table->boolean('sudah_dinaikkan')->default(false)->after('tidak_naik_kelas'); // pencegah double proses
            $table->boolean('lulus')->default(false)->after('sudah_dinaikkan');  // siswa kelas XII yang naik
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['tahun_ajaran', 'tidak_naik_kelas', 'sudah_dinaikkan', 'lulus']);
        });
    }
};
