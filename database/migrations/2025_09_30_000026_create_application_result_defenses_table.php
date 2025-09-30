<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationResultDefensesTable extends Migration
{
    public function up()
    {
        Schema::create('application_result_defenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('result')->nullable();
            $table->longText('note')->nullable();
            $table->date('revision_deadline')->nullable();
            $table->float('final_grade', 4, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
