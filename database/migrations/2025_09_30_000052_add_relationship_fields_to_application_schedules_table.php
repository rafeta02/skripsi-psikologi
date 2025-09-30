<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationSchedulesTable extends Migration
{
    public function up()
    {
        Schema::table('application_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10731392')->references('id')->on('applications');
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id', 'ruang_fk_10731469')->references('id')->on('ruangs');
        });
    }
}
