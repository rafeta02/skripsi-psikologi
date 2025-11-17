<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminFieldsToMbkmRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->datetime('approval_date')->nullable()->after('note');
            $table->text('rejection_reason')->nullable()->after('approval_date');
            $table->text('revision_notes')->nullable()->after('rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mbkm_registrations', function (Blueprint $table) {
            $table->dropColumn(['approval_date', 'rejection_reason', 'revision_notes']);
        });
    }
}
