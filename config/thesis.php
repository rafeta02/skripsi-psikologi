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
];
