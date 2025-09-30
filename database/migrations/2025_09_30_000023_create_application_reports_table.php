<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationReportsTable extends Migration
{
    public function up()
    {
        Schema::create('application_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('report_text')->nullable();
            $table->string('period')->nullable();
            $table->string('status')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
