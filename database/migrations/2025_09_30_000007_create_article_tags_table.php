<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagsTable extends Migration
{
    public function up()
    {
        Schema::create('article_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('featured')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
