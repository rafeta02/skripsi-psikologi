<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Review Kelayakan Proposal (Reguler) — reviewer deadlines (days)
    |--------------------------------------------------------------------------
    */
    'reviewer_response_days'         => (int) env('THESIS_REVIEWER_RESPONSE_DAYS', 5),
    'reviewer_feedback_warning_days' => (int) env('THESIS_REVIEWER_FEEDBACK_WARNING_DAYS', 10),
    'reviewer_feedback_deadline_days'=> (int) env('THESIS_REVIEWER_FEEDBACK_DEADLINE_DAYS', 14),

    'reviewer_feedback_mimes' => 'pdf,doc,docx',
    'reviewer_feedback_max_kb' => 10240,

    /*
    |--------------------------------------------------------------------------
    | Batas waktu daftar sidang setelah validasi admin laporan hasil (Reguler)
    |--------------------------------------------------------------------------
    */
    'defense_registration_grace_days' => (int) env('THESIS_DEFENSE_REGISTRATION_GRACE_DAYS', 30),

    /*
    |--------------------------------------------------------------------------
    | Watchlist lapor hasil sidang — mulai muncul setelah N hari sejak sidang
    | (tetap tampil setelah 30 hari jika belum lapor; tanpa batas deadline)
    |--------------------------------------------------------------------------
    */
    'defense_result_submission_warning_start_days' => (int) env('THESIS_DEFENSE_RESULT_SUBMISSION_WARNING_START_DAYS', 14),
];
