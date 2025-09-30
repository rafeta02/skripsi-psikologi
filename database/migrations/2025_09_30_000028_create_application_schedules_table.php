<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('application_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('schedule_type')->nullable();
            $table->datetime('waktu')->nullable();
            $table->string('custom_place')->nullable();
            $table->string('online_meeting')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
