<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToMbkmGroupMembersTable extends Migration
{
    public function up()
    {
        Schema::table('mbkm_group_members', function (Blueprint $table) {
            $table->unsignedBigInteger('mbkm_registration_id')->nullable();
            $table->foreign('mbkm_registration_id', 'mbkm_registration_fk_10729306')->references('id')->on('mbkm_registrations');
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->foreign('mahasiswa_id', 'mahasiswa_fk_10729307')->references('id')->on('mahasiswas');
        });
    }
}
