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
            $table->integer('penulisan')->nullable();
            $table->integer('isi')->nullable();
            $table->integer('analisis')->nullable();
            $table->integer('teoritis')->nullable();
            $table->integer('faktual')->nullable();
            $table->integer('pemecahan_masalah')->nullable();
            $table->integer('penyampaian')->nullable();
            $table->float('sum', 4, 1)->nullable();
            $table->float('score', 4, 1)->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
