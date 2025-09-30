<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMbkmRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10729281')->references('id')->on('applications');
            $table->unsignedBigInteger('research_group_id')->nullable();
            $table->foreign('research_group_id', 'research_group_fk_10729282')->references('id')->on('research_groups');
            $table->unsignedBigInteger('preference_supervision_id')->nullable();
            $table->foreign('preference_supervision_id', 'preference_supervision_fk_10729283')->references('id')->on('dosens');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->foreign('theme_id', 'theme_fk_10729284')->references('id')->on('keilmuans');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10729304')->references('id')->on('users');
        });
    }
}
