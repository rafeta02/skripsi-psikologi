<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skripsi_registrations', function (Blueprint $table) {
            $table->string('title_en', 500)->nullable()->after('title');
        });

        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->string('title_en', 500)->nullable()->after('title');
        });

        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->string('title_en', 500)->nullable()->after('title');
        });

        Schema::table('mbkm_seminars', function (Blueprint $table) {
            $table->string('title_en', 500)->nullable()->after('title');
        });

        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->string('title_en', 500)->nullable()->after('title');
        });

        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->string('final_title_en', 500)->nullable()->after('final_title');
        });
    }

    public function down(): void
    {
        Schema::table('skripsi_registrations', fn (Blueprint $table) => $table->dropColumn('title_en'));
        Schema::table('mbkm_registrations', fn (Blueprint $table) => $table->dropColumn('title_en'));
        Schema::table('skripsi_seminars', fn (Blueprint $table) => $table->dropColumn('title_en'));
        Schema::table('mbkm_seminars', fn (Blueprint $table) => $table->dropColumn('title_en'));
        Schema::table('skripsi_defenses', fn (Blueprint $table) => $table->dropColumn('title_en'));
        Schema::table('application_result_defenses', fn (Blueprint $table) => $table->dropColumn('final_title_en'));
    }
};
