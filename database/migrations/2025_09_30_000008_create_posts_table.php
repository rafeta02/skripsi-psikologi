<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->longText('content');
            $table->longText('excerpt')->nullable();
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('status')->nullable();
            $table->boolean('featured')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
