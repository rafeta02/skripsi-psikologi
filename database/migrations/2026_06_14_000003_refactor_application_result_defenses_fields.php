<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('transcript_document_number', 32)->nullable()->unique()->after('notes');
        });

        if (Schema::hasColumn('application_result_defenses', 'transcript_document_number')) {
            DB::table('application_result_defenses')
                ->whereNotNull('transcript_document_number')
                ->orderBy('id')
                ->each(function ($row) {
                    DB::table('applications')
                        ->where('id', $row->application_id)
                        ->whereNull('transcript_document_number')
                        ->update(['transcript_document_number' => $row->transcript_document_number]);
                });
        }

        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->string('final_title')->nullable()->after('application_id');

            if (Schema::hasColumn('application_result_defenses', 'transcript_document_number')) {
                $table->dropUnique(['transcript_document_number']);
                $table->dropColumn('transcript_document_number');
            }

            if (Schema::hasColumn('application_result_defenses', 'final_grade_letter')) {
                $table->dropColumn('final_grade_letter');
            }

            if (Schema::hasColumn('application_result_defenses', 'final_grade')) {
                $table->dropColumn('final_grade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->float('final_grade', 4, 2)->nullable();
            $table->string('final_grade_letter', 2)->nullable();
            $table->string('transcript_document_number', 32)->nullable()->unique();
            $table->dropColumn('final_title');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['transcript_document_number']);
            $table->dropColumn('transcript_document_number');
        });
    }
};
