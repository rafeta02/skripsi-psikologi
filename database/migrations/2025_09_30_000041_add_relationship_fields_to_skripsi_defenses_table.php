<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSkripsiDefensesTable extends Migration
{
    public function up()
    {
        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10727329')->references('id')->on('applications');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10727352')->references('id')->on('users');
        });
    }
}
