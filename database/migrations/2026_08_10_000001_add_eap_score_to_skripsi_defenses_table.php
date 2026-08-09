<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->unsignedTinyInteger('eap_score')->nullable()->after('eap_grade');
        });
    }

    public function down(): void
    {
        Schema::table('skripsi_defenses', function (Blueprint $table) {
            $table->dropColumn('eap_score');
        });
    }
};
