<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationScoresTable extends Migration
{
    public function up()
    {
        Schema::create('application_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('score', 4, 2)->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
