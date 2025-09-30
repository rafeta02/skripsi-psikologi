<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMbkmSeminarsTable extends Migration
{
    public function up()
    {
        Schema::create('mbkm_seminars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
