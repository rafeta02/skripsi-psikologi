<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('alert_type');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->string('severity')->default('warning');
            $table->text('message');
            $table->json('metadata')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications')->nullOnDelete();
            $table->foreign('assignment_id')->references('id')->on('application_assignments')->nullOnDelete();
            $table->foreign('dosen_id')->references('id')->on('dosens')->nullOnDelete();
            $table->foreign('resolved_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['alert_type', 'resolved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_alerts');
    }
};
