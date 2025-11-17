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
        Schema::table('skripsi_registrations', function (Blueprint $table) {
            $table->foreignId('assigned_supervisor_id')->nullable()->after('preference_supervision_id')->constrained('dosens')->onDelete('set null');
            $table->datetime('approval_date')->nullable()->after('assigned_supervisor_id');
            $table->text('rejection_reason')->nullable()->after('approval_date');
            $table->text('revision_notes')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skripsi_registrations', function (Blueprint $table) {
            $table->dropForeign(['assigned_supervisor_id']);
            $table->dropColumn(['assigned_supervisor_id', 'approval_date', 'rejection_reason', 'revision_notes']);
        });
    }
};
