<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::table('application_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10730261')->references('id')->on('applications');
            $table->unsignedBigInteger('lecturer_id')->nullable();
            $table->foreign('lecturer_id', 'lecturer_fk_10730262')->references('id')->on('dosens');
        });
    }
}
