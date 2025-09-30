<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangsTable extends Migration
{
    public function up()
    {
        Schema::create('ruangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->longText('facility')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
