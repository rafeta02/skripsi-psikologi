<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Laporan hasil Review Kelayakan Proposal (Reguler) disimpan di application_result_reviews.
 * MBKM menggunakan application_result_seminars (struktur & dokumen berbeda).
 *
 * Media collections Reguler (Spatie):
 * - reviewer_feedback_forms (multiple)
 * - application_letter
 * - minutes_document
 * - proposal_manuscript
 * - research_ethics_form
 */
return new class extends Migration
{
    public function up(): void
    {
        // Dokumentasi migrasi — tidak mengubah skema.
    }

    public function down(): void
    {
        //
    }
};
