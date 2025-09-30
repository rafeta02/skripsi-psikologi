<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeilmuansTable extends Migration
{
    public function up()
    {
        Schema::create('keilmuans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
