<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_result_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('reviewer_1_assignment_id')->nullable()->after('application_id');
            $table->unsignedBigInteger('reviewer_2_assignment_id')->nullable()->after('reviewer_1_assignment_id');
            $table->foreign('reviewer_1_assignment_id', 'arr_reviewer1_aa_fk')
                ->references('id')->on('application_assignments')->nullOnDelete();
            $table->foreign('reviewer_2_assignment_id', 'arr_reviewer2_aa_fk')
                ->references('id')->on('application_assignments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('application_result_reviews', function (Blueprint $table) {
            $table->dropForeign('arr_reviewer1_aa_fk');
            $table->dropForeign('arr_reviewer2_aa_fk');
            $table->dropColumn(['reviewer_1_assignment_id', 'reviewer_2_assignment_id']);
        });
    }
};
