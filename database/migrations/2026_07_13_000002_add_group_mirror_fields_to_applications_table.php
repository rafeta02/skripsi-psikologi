<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->boolean('is_group_mirror')->default(false)->after('notes');
            $table->unsignedBigInteger('parent_application_id')->nullable()->after('is_group_mirror');

            $table->foreign('parent_application_id', 'applications_parent_fk')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade');

            $table->index(['is_group_mirror', 'parent_application_id'], 'applications_group_mirror_idx');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign('applications_parent_fk');
            $table->dropIndex('applications_group_mirror_idx');
            $table->dropColumn(['is_group_mirror', 'parent_application_id']);
        });
    }
};
