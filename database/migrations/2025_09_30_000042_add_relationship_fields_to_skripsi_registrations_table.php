<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSkripsiRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('skripsi_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10727354')->references('id')->on('applications');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->foreign('theme_id', 'theme_fk_10727355')->references('id')->on('keilmuans');
            $table->unsignedBigInteger('tps_lecturer_id')->nullable();
            $table->foreign('tps_lecturer_id', 'tps_lecturer_fk_10727358')->references('id')->on('dosens');
            $table->unsignedBigInteger('preference_supervision_id')->nullable();
            $table->foreign('preference_supervision_id', 'preference_supervision_fk_10729164')->references('id')->on('dosens');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10727364')->references('id')->on('users');
        });
    }
}
