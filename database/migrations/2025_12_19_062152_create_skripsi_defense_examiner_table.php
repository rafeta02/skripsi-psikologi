<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skripsi_defense_examiner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skripsi_defense_id');
            $table->unsignedBigInteger('dosen_id');
            $table->enum('role', ['examiner_1', 'examiner_2'])->comment('Penguji 1 or Penguji 2');
            $table->timestamps();

            $table->foreign('skripsi_defense_id')->references('id')->on('skripsi_defenses')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
            
            // Ensure unique examiner per defense
            $table->unique(['skripsi_defense_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skripsi_defense_examiner');
    }
};
