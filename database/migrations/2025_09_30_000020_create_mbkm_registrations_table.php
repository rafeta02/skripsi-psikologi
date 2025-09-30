<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMbkmRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('mbkm_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_mbkm')->nullable();
            $table->string('title')->nullable();
            $table->integer('total_sks_taken')->nullable();
            $table->string('nilai_mk_kuantitatif')->nullable();
            $table->string('nilai_mk_kualitatif')->nullable();
            $table->string('nilai_mk_statistika_dasar')->nullable();
            $table->string('nilai_mk_statistika_lanjutan')->nullable();
            $table->string('nilai_mk_konstruksi_tes')->nullable();
            $table->string('nilai_mk_tps')->nullable();
            $table->integer('sks_mkp_taken')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
