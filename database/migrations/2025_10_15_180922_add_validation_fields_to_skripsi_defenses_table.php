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
        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('abstract');
            $table->text('admin_note')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_note']);
        });
    }
};
