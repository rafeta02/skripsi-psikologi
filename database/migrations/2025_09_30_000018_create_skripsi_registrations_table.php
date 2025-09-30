<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkripsiRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('skripsi_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('abstract')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
