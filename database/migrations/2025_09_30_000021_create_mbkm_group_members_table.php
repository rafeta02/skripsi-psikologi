<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMbkmGroupMembersTable extends Migration
{
    public function up()
    {
        Schema::create('mbkm_group_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
