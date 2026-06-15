<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->string('transcript_document_number', 32)->nullable()->unique()->after('final_grade_letter');
        });

        Schema::create('thesis_transcript_number_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_transcript_number_sequences');

        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->dropColumn('transcript_document_number');
        });
    }
};
