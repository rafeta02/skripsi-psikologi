<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMahasiswasTable extends Migration
{
    public function up()
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id', 'prodi_fk_10638922')->references('id')->on('prodis');
            $table->unsignedBigInteger('jenjang_id')->nullable();
            $table->foreign('jenjang_id', 'jenjang_fk_10638923')->references('id')->on('jenjangs');
            $table->unsignedBigInteger('fakultas_id')->nullable();
            $table->foreign('fakultas_id', 'fakultas_fk_10638924')->references('id')->on('faculties');
        });
    }
}
