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
        Schema::table('application_assignments', function (Blueprint $table) {
            $table->string('reviewer_slot')->nullable()->after('role');
            $table->unsignedBigInteger('skripsi_seminar_id')->nullable()->after('application_id');
            $table->foreign('skripsi_seminar_id', 'aa_skripsi_seminar_fk')
                ->references('id')->on('skripsi_seminars')->nullOnDelete();

            $table->dateTime('response_deadline')->nullable()->after('responded_at');
            $table->dateTime('feedback_deadline')->nullable()->after('response_deadline');

            $table->text('rejection_reason')->nullable()->after('note');
            $table->string('feedback_result')->nullable()->after('rejection_reason');
            $table->text('feedback_note')->nullable()->after('feedback_result');
            $table->dateTime('feedback_submitted_at')->nullable()->after('feedback_note');

            $table->unsignedBigInteger('replaced_by_assignment_id')->nullable()->after('feedback_submitted_at');
            $table->foreign('replaced_by_assignment_id', 'aa_replaced_by_fk')
                ->references('id')->on('application_assignments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('application_assignments', function (Blueprint $table) {
            $table->dropForeign('aa_replaced_by_fk');
            $table->dropForeign('aa_skripsi_seminar_fk');
            $table->dropColumn([
                'reviewer_slot',
                'skripsi_seminar_id',
                'response_deadline',
                'feedback_deadline',
                'rejection_reason',
                'feedback_result',
                'feedback_note',
                'feedback_submitted_at',
                'replaced_by_assignment_id',
            ]);
        });
    }
};
