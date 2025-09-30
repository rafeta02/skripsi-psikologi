<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationScoresTable extends Migration
{
    public function up()
    {
        Schema::table('application_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('application_result_defence_id')->nullable();
            $table->foreign('application_result_defence_id', 'application_result_defence_fk_10731130')->references('id')->on('application_result_defenses');
            $table->unsignedBigInteger('examiner_id')->nullable();
            $table->foreign('examiner_id', 'examiner_fk_10731131')->references('id')->on('dosens');
        });
    }
}
