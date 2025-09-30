<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdisTable extends Migration
{
    public function up()
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
