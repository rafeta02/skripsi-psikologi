<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDosensTable extends Migration
{
    public function up()
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id', 'prodi_fk_10639013')->references('id')->on('prodis');
            $table->unsignedBigInteger('jenjang_id')->nullable();
            $table->foreign('jenjang_id', 'jenjang_fk_10639014')->references('id')->on('jenjangs');
            $table->unsignedBigInteger('fakultas_id')->nullable();
            $table->foreign('fakultas_id', 'fakultas_fk_10639015')->references('id')->on('faculties');
            $table->unsignedBigInteger('riset_grup_id')->nullable();
            $table->foreign('riset_grup_id', 'riset_grup_fk_10640314')->references('id')->on('research_groups');
        });
    }
}
