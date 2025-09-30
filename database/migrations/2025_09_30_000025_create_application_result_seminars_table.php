<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationResultSeminarsTable extends Migration
{
    public function up()
    {
        Schema::create('application_result_seminars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('result')->nullable();
            $table->longText('note')->nullable();
            $table->date('revision_deadline')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
