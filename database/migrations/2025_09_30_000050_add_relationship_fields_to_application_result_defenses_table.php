<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationResultDefensesTable extends Migration
{
    public function up()
    {
        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10731107')->references('id')->on('applications');
        });
    }
}
