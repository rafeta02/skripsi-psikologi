<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradeToApplicationResultDefensesTable extends Migration
{
    public function up()
    {
        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->string('final_grade_letter', 2)->nullable()->after('final_grade');
        });
    }

    public function down()
    {
        Schema::table('application_result_defenses', function (Blueprint $table) {
            $table->dropColumn('final_grade_letter');
        });
    }
}

