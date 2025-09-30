<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id', 'mahasiswa_fk_10639045')->references('id')->on('mahasiswas');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->foreign('dosen_id', 'dosen_fk_10639046')->references('id')->on('dosens');
        });
    }
}
