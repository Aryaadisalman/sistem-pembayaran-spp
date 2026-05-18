<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('angsuran_du')) {
            Schema::create('angsuran_du', function (Blueprint $table) {
                $table->id('angsuran_id');
                $table->unsignedBigInteger('pembayaran_id');
                $table->unsignedBigInteger('siswa_id');
                $table->unsignedBigInteger('spp_id');
                $table->unsignedTinyInteger('angsuran_ke');
                $table->decimal('nominal_angsuran', 10, 2);
                $table->decimal('jumlah_bayar', 10, 2)->default(0);
                $table->enum('status', ['belum_bayar', 'pending', 'lunas', 'ditolak'])->default('belum_bayar');
                $table->date('tanggal_bayar')->nullable();
                $table->string('bukti_bayar', 255)->nullable();
                $table->timestamps();

                $table->foreign('pembayaran_id')->references('pembayaran_id')->on('pembayaran')->onDelete('cascade');
                $table->foreign('siswa_id')->references('siswa_id')->on('siswa')->onDelete('cascade');
                $table->foreign('spp_id')->references('spp_id')->on('spp')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran_du');
    }
};