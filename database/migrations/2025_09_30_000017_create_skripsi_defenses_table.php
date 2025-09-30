<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkripsiDefensesTable extends Migration
{
    public function up()
    {
        Schema::create('skripsi_defenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('abstract')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
