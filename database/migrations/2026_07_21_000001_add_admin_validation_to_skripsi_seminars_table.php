<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->dateTime('admin_validated_at')->nullable()->after('reviewer_2_id');
            $table->unsignedBigInteger('admin_validated_by')->nullable()->after('admin_validated_at');
            $table->foreign('admin_validated_by', 'ss_admin_validated_by_fk')
                ->references('id')->on('users')->nullOnDelete();
            $table->text('admin_revision_notes')->nullable()->after('admin_validated_by');
        });
    }

    public function down(): void
    {
        Schema::table('skripsi_seminars', function (Blueprint $table) {
            $table->dropForeign('ss_admin_validated_by_fk');
            $table->dropColumn(['admin_validated_at', 'admin_validated_by', 'admin_revision_notes']);
        });
    }
};
