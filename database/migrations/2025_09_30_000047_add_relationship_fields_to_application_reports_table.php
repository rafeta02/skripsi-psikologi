<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationReportsTable extends Migration
{
    public function up()
    {
        Schema::table('application_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10729335')->references('id')->on('applications');
        });
    }
}
