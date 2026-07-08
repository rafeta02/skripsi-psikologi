<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_title_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mahasiswa_nama')->nullable();
            $table->string('nim', 30)->nullable();
            $table->string('prodi')->nullable();
            $table->string('type', 20)->nullable()->comment('skripsi atau mbkm');
            $table->string('title', 500);
            $table->string('title_en', 500)->nullable();
            $table->string('year', 4)->nullable();
            $table->text('note')->nullable();
            $table->string('source', 30)->default('manual')->comment('manual atau import');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_title_entries');
    }
};
