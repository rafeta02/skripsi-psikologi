<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCategoryPostPivotTable extends Migration
{
    public function up()
    {
        Schema::create('article_category_post', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id', 'post_id_fk_9964775')->references('id')->on('posts')->onDelete('cascade');
            $table->unsignedBigInteger('article_category_id');
            $table->foreign('article_category_id', 'article_category_id_fk_9964775')->references('id')->on('article_categories')->onDelete('cascade');
        });
    }
}
