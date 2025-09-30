<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationResultSeminarsTable extends Migration
{
    public function up()
    {
        Schema::table('application_result_seminars', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10731093')->references('id')->on('applications');
        });
    }
}
