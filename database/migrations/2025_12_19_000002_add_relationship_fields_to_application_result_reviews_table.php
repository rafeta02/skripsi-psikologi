<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToApplicationResultReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('application_result_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable();
            $table->foreign('application_id', 'application_fk_10001')->references('id')->on('applications');
        });
    }

    public function down()
    {
        Schema::table('application_result_reviews', function (Blueprint $table) {
            $table->dropForeign('application_fk_10001');
            $table->dropColumn('application_id');
        });
    }
}









