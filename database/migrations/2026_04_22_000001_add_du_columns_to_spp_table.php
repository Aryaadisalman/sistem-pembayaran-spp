<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spp', function (Blueprint $table) {
            if (!Schema::hasColumn('spp', 'jenis')) {
                $table->enum('jenis', ['spp', 'ppdb', 'du'])->default('spp')->after('nama');
            }
            if (!Schema::hasColumn('spp', 'max_angsuran')) {
                $table->unsignedTinyInteger('max_angsuran')->nullable()->after('nominal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spp', function (Blueprint $table) {
            if (Schema::hasColumn('spp', 'jenis')) {
                $table->dropColumn('jenis');
            }
            if (Schema::hasColumn('spp', 'max_angsuran')) {
                $table->dropColumn('max_angsuran');
            }
        });
    }
};
