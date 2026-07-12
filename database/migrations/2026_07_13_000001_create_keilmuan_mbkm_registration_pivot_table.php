<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keilmuan_mbkm_registration', function (Blueprint $table) {
            $table->unsignedBigInteger('mbkm_registration_id');
            $table->unsignedBigInteger('keilmuan_id');

            $table->foreign('mbkm_registration_id', 'kmr_mbkm_reg_fk')
                ->references('id')
                ->on('mbkm_registrations')
                ->onDelete('cascade');

            $table->foreign('keilmuan_id', 'kmr_keilmuan_fk')
                ->references('id')
                ->on('keilmuans')
                ->onDelete('cascade');

            $table->primary(['mbkm_registration_id', 'keilmuan_id'], 'kmr_primary');
        });

        // Migrasi data lama: theme_id tunggal → pivot
        if (Schema::hasColumn('mbkm_registrations', 'theme_id')) {
            $rows = DB::table('mbkm_registrations')
                ->whereNotNull('theme_id')
                ->select('id', 'theme_id')
                ->get();

            foreach ($rows as $row) {
                DB::table('keilmuan_mbkm_registration')->insertOrIgnore([
                    'mbkm_registration_id' => $row->id,
                    'keilmuan_id' => $row->theme_id,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('keilmuan_mbkm_registration');
    }
};
