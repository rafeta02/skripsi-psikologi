<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id', 'mahasiswa_fk_10719517')->references('id')->on('mahasiswas');
        });
    }
}
