<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('stage')->nullable();
            $table->string('status')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
