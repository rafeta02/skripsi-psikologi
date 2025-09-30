<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nim')->nullable();
            $table->string('nama')->nullable();
            $table->integer('tahun_masuk')->nullable();
            $table->string('kelas')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('gender')->nullable();
            $table->longText('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
