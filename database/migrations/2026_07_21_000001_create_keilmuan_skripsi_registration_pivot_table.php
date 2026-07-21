<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keilmuan_skripsi_registration', function (Blueprint $table) {
            $table->unsignedBigInteger('skripsi_registration_id');
            $table->unsignedBigInteger('keilmuan_id');

            $table->foreign('skripsi_registration_id', 'ksr_skripsi_reg_fk')
                ->references('id')
                ->on('skripsi_registrations')
                ->onDelete('cascade');

            $table->foreign('keilmuan_id', 'ksr_keilmuan_fk')
                ->references('id')
                ->on('keilmuans')
                ->onDelete('cascade');

            $table->primary(['skripsi_registration_id', 'keilmuan_id'], 'ksr_primary');
        });

        if (Schema::hasColumn('skripsi_registrations', 'theme_id')) {
            $rows = DB::table('skripsi_registrations')
                ->whereNotNull('theme_id')
                ->select('id', 'theme_id')
                ->get();

            foreach ($rows as $row) {
                DB::table('keilmuan_skripsi_registration')->insertOrIgnore([
                    'skripsi_registration_id' => $row->id,
                    'keilmuan_id' => $row->theme_id,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('keilmuan_skripsi_registration');
    }
};
