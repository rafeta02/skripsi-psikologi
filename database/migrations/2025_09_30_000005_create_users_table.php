<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('username')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('whatshapp')->nullable();
            $table->string('level')->nullable();
            $table->string('identity_number')->nullable();
            $table->longText('alamat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
