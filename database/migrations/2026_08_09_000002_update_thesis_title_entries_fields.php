<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('thesis_title_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('thesis_title_entries', 'nama')) {
                $table->string('nama')->nullable()->after('id');
            }
            if (! Schema::hasColumn('thesis_title_entries', 'angkatan')) {
                $table->string('angkatan', 10)->nullable()->after('nim');
            }
            if (! Schema::hasColumn('thesis_title_entries', 'pembimbing')) {
                $table->string('pembimbing')->nullable()->after('angkatan');
            }
            if (! Schema::hasColumn('thesis_title_entries', 'penguji_1')) {
                $table->string('penguji_1')->nullable()->after('title_en');
            }
            if (! Schema::hasColumn('thesis_title_entries', 'penguji_2')) {
                $table->string('penguji_2')->nullable()->after('penguji_1');
            }
            if (! Schema::hasColumn('thesis_title_entries', 'tanggal_sidang')) {
                $table->date('tanggal_sidang')->nullable()->after('penguji_2');
            }
        });

        if (Schema::hasColumn('thesis_title_entries', 'mahasiswa_nama')) {
            foreach (DB::table('thesis_title_entries')->select('id', 'mahasiswa_nama', 'nama')->get() as $row) {
                if (empty($row->nama) && ! empty($row->mahasiswa_nama)) {
                    DB::table('thesis_title_entries')->where('id', $row->id)->update(['nama' => $row->mahasiswa_nama]);
                }
            }
        }

        if (Schema::hasColumn('thesis_title_entries', 'year')) {
            foreach (DB::table('thesis_title_entries')->select('id', 'year', 'angkatan')->get() as $row) {
                if (empty($row->angkatan) && ! empty($row->year)) {
                    DB::table('thesis_title_entries')->where('id', $row->id)->update(['angkatan' => $row->year]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('thesis_title_entries', function (Blueprint $table) {
            $columns = ['nama', 'angkatan', 'pembimbing', 'penguji_1', 'penguji_2', 'tanggal_sidang'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('thesis_title_entries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
