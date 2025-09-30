<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosenKeilmuanPivotTable extends Migration
{
    public function up()
    {
        Schema::create('dosen_keilmuan', function (Blueprint $table) {
            $table->unsignedBigInteger('dosen_id');
            $table->foreign('dosen_id', 'dosen_id_fk_10639035')->references('id')->on('dosens')->onDelete('cascade');
            $table->unsignedBigInteger('keilmuan_id');
            $table->foreign('keilmuan_id', 'keilmuan_id_fk_10639035')->references('id')->on('keilmuans')->onDelete('cascade');
        });
    }
}
