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
        Schema::create('application_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('action_type'); // approved, rejected, revision_requested, scheduled, etc.
            $table->foreignId('action_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable(); // Admin notes/reason
            $table->json('metadata')->nullable(); // Additional data (e.g., assigned_supervisor_id)
            $table->timestamps();
            
            $table->index(['application_id', 'action_type']);
            $table->index('action_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_actions');
    }
};
