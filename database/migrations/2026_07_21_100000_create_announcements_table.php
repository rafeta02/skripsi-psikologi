<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('body');
            $table->string('audience')->default('all');
            $table->string('status')->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
