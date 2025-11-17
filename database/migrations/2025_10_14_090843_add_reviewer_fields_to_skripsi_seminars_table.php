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
        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_1_id')->nullable()->after('application_id');
            $table->foreign('reviewer_1_id')->references('id')->on('dosens')->onDelete('set null');
            
            $table->unsignedBigInteger('reviewer_2_id')->nullable()->after('reviewer_1_id');
            $table->foreign('reviewer_2_id')->references('id')->on('dosens')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->dropForeign(['reviewer_1_id']);
            $table->dropColumn('reviewer_1_id');
            
            $table->dropForeign(['reviewer_2_id']);
            $table->dropColumn('reviewer_2_id');
        });
    }
};
