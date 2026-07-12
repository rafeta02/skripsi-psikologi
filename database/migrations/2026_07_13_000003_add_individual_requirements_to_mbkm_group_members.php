<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->string('group_status')->default('draft')->after('note');
            // draft = menunggu syarat individu anggota; submitted = diajukan ke admin
        });

        Schema::table('mbkm_group_members', function (Blueprint $table) {
            $table->string('title')->nullable()->after('role');
            $table->string('title_en')->nullable()->after('title');
            $table->integer('total_sks_taken')->nullable()->after('title_en');
            $table->integer('sks_mkp_taken')->nullable()->after('total_sks_taken');
            $table->string('nilai_mk_kuantitatif')->nullable()->after('sks_mkp_taken');
            $table->string('nilai_mk_kualitatif')->nullable()->after('nilai_mk_kuantitatif');
            $table->string('nilai_mk_statistika_dasar')->nullable()->after('nilai_mk_kualitatif');
            $table->string('nilai_mk_statistika_lanjutan')->nullable()->after('nilai_mk_statistika_dasar');
            $table->string('nilai_mk_konstruksi_tes')->nullable()->after('nilai_mk_statistika_lanjutan');
            $table->string('nilai_mk_tps')->nullable()->after('nilai_mk_konstruksi_tes');
            $table->string('requirements_status')->default('incomplete')->after('nilai_mk_tps');
            $table->timestamp('requirements_completed_at')->nullable()->after('requirements_status');
        });

        // Migrasi data lama: field individu ketua dari registration → group member ketua
        $registrations = DB::table('mbkm_registrations')->whereNull('deleted_at')->get();

        foreach ($registrations as $reg) {
            $app = DB::table('applications')->where('id', $reg->application_id)->first();
            if (!$app) {
                continue;
            }

            $ketuaId = $app->mahasiswa_id;

            $memberId = DB::table('mbkm_group_members')
                ->where('mbkm_registration_id', $reg->id)
                ->where('mahasiswa_id', $ketuaId)
                ->whereNull('deleted_at')
                ->value('id');

            if (!$memberId) {
                $memberId = DB::table('mbkm_group_members')->insertGetId([
                    'mbkm_registration_id' => $reg->id,
                    'mahasiswa_id' => $ketuaId,
                    'role' => 'ketua',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $hasIndividual = !empty($reg->title) || !empty($reg->total_sks_taken);

            DB::table('mbkm_group_members')->where('id', $memberId)->update([
                'title' => $reg->title,
                'title_en' => $reg->title_en ?? null,
                'total_sks_taken' => $reg->total_sks_taken,
                'sks_mkp_taken' => $reg->sks_mkp_taken,
                'nilai_mk_kuantitatif' => $reg->nilai_mk_kuantitatif,
                'nilai_mk_kualitatif' => $reg->nilai_mk_kualitatif,
                'nilai_mk_statistika_dasar' => $reg->nilai_mk_statistika_dasar,
                'nilai_mk_statistika_lanjutan' => $reg->nilai_mk_statistika_lanjutan,
                'nilai_mk_konstruksi_tes' => $reg->nilai_mk_konstruksi_tes,
                'nilai_mk_tps' => $reg->nilai_mk_tps,
                'requirements_status' => $hasIndividual ? 'complete' : 'incomplete',
                'requirements_completed_at' => $hasIndividual ? now() : null,
                'updated_at' => now(),
            ]);

            // Registration lama yang sudah ada dianggap sudah submit kelompok
            DB::table('mbkm_registrations')->where('id', $reg->id)->update([
                'group_status' => 'submitted',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('mbkm_group_members', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'title_en',
                'total_sks_taken',
                'sks_mkp_taken',
                'nilai_mk_kuantitatif',
                'nilai_mk_kualitatif',
                'nilai_mk_statistika_dasar',
                'nilai_mk_statistika_lanjutan',
                'nilai_mk_konstruksi_tes',
                'nilai_mk_tps',
                'requirements_status',
                'requirements_completed_at',
            ]);
        });

        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->dropColumn('group_status');
        });
    }
};
