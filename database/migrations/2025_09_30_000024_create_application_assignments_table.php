<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('application_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role')->nullable();
            $table->string('status')->nullable();
            $table->datetime('assigned_at')->nullable();
            $table->datetime('responded_at')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
