<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagPostPivotTable extends Migration
{
    public function up()
    {
        Schema::create('article_tag_post', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id', 'post_id_fk_9964776')->references('id')->on('posts')->onDelete('cascade');
            $table->unsignedBigInteger('article_tag_id');
            $table->foreign('article_tag_id', 'article_tag_id_fk_9964776')->references('id')->on('article_tags')->onDelete('cascade');
        });
    }
}
