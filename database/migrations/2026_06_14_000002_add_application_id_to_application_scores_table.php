<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->after('id');
            $table->foreign('application_id', 'application_scores_application_id_fk')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('application_scores', function (Blueprint $table) {
            $table->dropForeign('application_scores_application_id_fk');
            $table->dropColumn('application_id');
        });
    }
};
