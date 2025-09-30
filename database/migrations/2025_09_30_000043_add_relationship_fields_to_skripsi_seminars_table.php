<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSkripsiSeminarsTable extends Migration
{
    public function up()
    {
        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10727366')->references('id')->on('applications');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_10727374')->references('id')->on('users');
        });
    }
}
