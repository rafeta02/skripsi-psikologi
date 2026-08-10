<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->string('lokasi_kkn', 255)->nullable()->after('title_en');
        });

        Schema::table('mbkm_seminars', function (Blueprint $table) {
            $table->string('lokasi_kkn', 255)->nullable()->after('title_en');
        });
    }

    public function down(): void
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->dropColumn('lokasi_kkn');
        });

        Schema::table('mbkm_seminars', function (Blueprint $table) {
            $table->dropColumn('lokasi_kkn');
        });
    }
};
