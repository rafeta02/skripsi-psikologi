<?php

namespace App\Services;

use App\Models\AdminAlert;
use App\Models\Application;
use App\Models\ApplicationAction;
use App\Models\ApplicationAssignment;
use App\Models\SkripsiSeminar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReviewerAssignmentService
{
    public function responseDays(): int
    {
        return (int) config('thesis.reviewer_response_days', 5);
    }

    public function feedbackWarningDays(): int
    {
        return (int) config('thesis.reviewer_feedback_warning_days', 10);
    }

    public function feedbackDeadlineDays(): int
    {
        return (int) config('thesis.reviewer_feedback_deadline_days', 14);
    }

    /**
     * Create reviewer assignments when admin approves SkripsiSeminar.
     */
    public function assignReviewers(SkripsiSeminar $seminar, int $reviewer1Id, int $reviewer2Id, ?string $notes = null): void
    {
        $supervisorId = $seminar->application?->resolveSupervisorLecturerId();
        if ($supervisorId && in_array($supervisorId, [$reviewer1Id, $reviewer2Id], true)) {
            throw new \InvalidArgumentException('Dosen pembimbing tidak dapat ditugaskan sebagai reviewer.');
        }

        $now = now();
        $responseDeadline = $now->copy()->addDays($this->responseDays());
        $feedbackDeadline = $now->copy()->addDays($this->feedbackDeadlineDays());

        DB::transaction(function () use ($seminar, $reviewer1Id, $reviewer2Id, $notes, $now, $responseDeadline, $feedbackDeadline, $supervisorId) {
            $seminar->update([
                'reviewer_1_id' => $reviewer1Id,
                'reviewer_2_id' => $reviewer2Id,
                'admin_validated_at' => $now,
                'admin_validated_by' => auth()->id(),
            ]);

            $seminar->application->update([
                'status' => 'submitted',
            ]);

            foreach ([
                ['slot' => 'reviewer_1', 'lecturer_id' => $reviewer1Id],
                ['slot' => 'reviewer_2', 'lecturer_id' => $reviewer2Id],
            ] as $item) {
                ApplicationAssignment::create([
                    'application_id' => $seminar->application_id,
                    'skripsi_seminar_id' => $seminar->id,
                    'lecturer_id' => $item['lecturer_id'],
                    'role' => 'reviewer',
                    'reviewer_slot' => $item['slot'],
                    'status' => 'assigned',
                    'assigned_at' => $now,
                    'response_deadline' => $responseDeadline,
                    'feedback_deadline' => $feedbackDeadline,
                    'note' => $notes,
                ]);
            }

            if ($supervisorId) {
                $this->ensureSupervisorInformant($seminar, $supervisorId, $now, $notes);
            }

            ApplicationAction::create([
                'application_id' => $seminar->application_id,
                'action_type' => 'seminar_approved',
                'action_by' => auth()->id(),
                'notes' => $notes ?? 'Review Kelayakan Proposal (Reguler) disetujui',
                'metadata' => [
                    'reviewer_1_id' => $reviewer1Id,
                    'reviewer_2_id' => $reviewer2Id,
                    'skripsi_seminar_id' => $seminar->id,
                    'supervisor_informed_id' => $supervisorId,
                ],
            ]);
        });
    }

    /**
     * Replace an expired/rejected reviewer with a new dosen.
     */
    public function reassignReviewer(ApplicationAssignment $oldAssignment, int $newLecturerId, ?string $note = null): ApplicationAssignment
    {
        $seminar = SkripsiSeminar::with('application')->find($oldAssignment->skripsi_seminar_id);
        $supervisorId = $seminar?->application?->resolveSupervisorLecturerId();
        if ($supervisorId && $supervisorId === $newLecturerId) {
            throw new \InvalidArgumentException('Dosen pembimbing tidak dapat ditugaskan sebagai reviewer.');
        }

        return DB::transaction(function () use ($oldAssignment, $newLecturerId, $note) {
            $now = now();
            $oldAssignment->update(['status' => 'replaced']);

            $newAssignment = ApplicationAssignment::create([
                'application_id' => $oldAssignment->application_id,
                'skripsi_seminar_id' => $oldAssignment->skripsi_seminar_id,
                'lecturer_id' => $newLecturerId,
                'role' => 'reviewer',
                'reviewer_slot' => $oldAssignment->reviewer_slot,
                'status' => 'assigned',
                'assigned_at' => $now,
                'response_deadline' => $now->copy()->addDays($this->responseDays()),
                'feedback_deadline' => $now->copy()->addDays($this->feedbackDeadlineDays()),
                'note' => $note,
            ]);

            $oldAssignment->update(['replaced_by_assignment_id' => $newAssignment->id]);

            $seminar = SkripsiSeminar::find($oldAssignment->skripsi_seminar_id);
            if ($seminar) {
                if ($oldAssignment->reviewer_slot === 'reviewer_1') {
                    $seminar->update(['reviewer_1_id' => $newLecturerId]);
                } elseif ($oldAssignment->reviewer_slot === 'reviewer_2') {
                    $seminar->update(['reviewer_2_id' => $newLecturerId]);
                }
            }

            AdminAlert::where('assignment_id', $oldAssignment->id)->unresolved()->get()->each->resolve();

            ApplicationAction::create([
                'application_id' => $oldAssignment->application_id,
                'action_type' => 'reviewer_reassigned',
                'action_by' => auth()->id(),
                'notes' => $note ?? 'Reviewer diganti oleh admin',
                'metadata' => [
                    'old_assignment_id' => $oldAssignment->id,
                    'new_assignment_id' => $newAssignment->id,
                    'new_lecturer_id' => $newLecturerId,
                ],
            ]);

            return $newAssignment;
        });
    }

    /**
     * Active reviewer assignments for a skripsi seminar application.
     */
    public function activeReviewerAssignments(int $applicationId): Collection
    {
        return ApplicationAssignment::where('application_id', $applicationId)
            ->where('role', 'reviewer')
            ->whereNotIn('status', ['replaced', 'expired'])
            ->orderBy('reviewer_slot')
            ->get();
    }

    public function bothReviewersFeedbackSubmitted(int $applicationId): bool
    {
        foreach (['reviewer_1', 'reviewer_2'] as $slot) {
            $hasFeedback = ApplicationAssignment::where('application_id', $applicationId)
                ->where('role', 'reviewer')
                ->where('reviewer_slot', $slot)
                ->where('status', 'feedback_submitted')
                ->exists();

            if (!$hasFeedback) {
                return false;
            }
        }

        return true;
    }

    public function syncApplicationReviewStatus(Application $application): void
    {
        $assignments = $this->activeReviewerAssignments($application->id);

        if ($assignments->isEmpty()) {
            return;
        }

        if ($this->bothReviewersFeedbackSubmitted($application->id)) {
            if ($application->status !== 'approved') {
                $application->update(['status' => 'approved']);
            }

            return;
        }

        $pendingAccept = $assignments->where('status', 'assigned')->isNotEmpty();
        $pendingFeedback = $assignments->where('status', 'accepted')->isNotEmpty();

        if ($pendingAccept || $pendingFeedback) {
            if ($application->status !== 'submitted') {
                $application->update(['status' => 'submitted']);
            }
        }
    }

    /**
     * Jumlah reviewer Reguler yang terlambat respons atau feedback (real-time, untuk badge menu admin).
     */
    public function countOverdueRegulerReviewers(): int
    {
        $lateResponse = ApplicationAssignment::query()
            ->where('role', 'reviewer')
            ->whereNotNull('skripsi_seminar_id')
            ->whereNotIn('status', ['replaced'])
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->where('status', 'assigned')
                        ->whereNotNull('response_deadline')
                        ->where('response_deadline', '<', now());
                })->orWhere('status', 'expired');
            })
            ->count();

        $lateFeedback = ApplicationAssignment::query()
            ->where('role', 'reviewer')
            ->whereNotNull('skripsi_seminar_id')
            ->where('status', 'accepted')
            ->whereNull('feedback_submitted_at')
            ->where('assigned_at', '<=', now()->subDays($this->feedbackWarningDays()))
            ->count();

        return $lateResponse + $lateFeedback;
    }

    /**
     * Daily job: expire assignments without response, warn overdue feedback.
     */
    public function processDeadlines(): array
    {
        $stats = ['expired' => 0, 'warnings' => 0, 'overdue' => 0];

        ApplicationAssignment::where('role', 'reviewer')
            ->where('status', 'assigned')
            ->whereNotNull('response_deadline')
            ->where('response_deadline', '<', now())
            ->each(function (ApplicationAssignment $assignment) use (&$stats) {
                $assignment->update(['status' => 'expired']);
                $stats['expired']++;

                $this->createAlertIfMissing(
                    AdminAlert::TYPE_REVIEWER_NO_RESPONSE,
                    $assignment,
                    'warning',
                    sprintf(
                        'Reviewer %s belum merespons penugasan review proposal (Reguler) dalam %d hari. Silakan assign reviewer pengganti.',
                        $assignment->lecturer?->nama ?? '#'.$assignment->lecturer_id,
                        $this->responseDays()
                    )
                );
            });

        ApplicationAssignment::where('role', 'reviewer')
            ->where('status', 'accepted')
            ->whereNull('feedback_submitted_at')
            ->each(function (ApplicationAssignment $assignment) use (&$stats) {
                $assignedAt = Carbon::parse($assignment->getRawOriginal('assigned_at'));
                $daysSince = $assignedAt->diffInDays(now());

                if ($daysSince >= $this->feedbackWarningDays()) {
                    $isOverdue = $daysSince >= $this->feedbackDeadlineDays();
                    $type = $isOverdue
                        ? AdminAlert::TYPE_REVIEWER_FEEDBACK_OVERDUE
                        : AdminAlert::TYPE_REVIEWER_FEEDBACK_WARNING;
                    $severity = $isOverdue ? 'critical' : 'warning';

                    if ($isOverdue) {
                        $stats['overdue']++;
                    } else {
                        $stats['warnings']++;
                    }

                    $this->createAlertIfMissing(
                        $type,
                        $assignment,
                        $severity,
                        $isOverdue
                            ? sprintf(
                                'Reviewer %s belum mengirim feedback review proposal (Reguler) setelah %d hari. Berikan peringatan atau ganti reviewer.',
                                $assignment->lecturer?->nama ?? '#'.$assignment->lecturer_id,
                                $this->feedbackDeadlineDays()
                            )
                            : sprintf(
                                'Reviewer %s belum mengirim feedback review proposal (Reguler) setelah %d hari. Pertimbangkan memberi peringatan.',
                                $assignment->lecturer?->nama ?? '#'.$assignment->lecturer_id,
                                $this->feedbackWarningDays()
                            )
                    );
                }
            });

        return $stats;
    }

    /**
     * Buat / perbarui penugasan informasi untuk dosen pembimbing.
     */
    public function ensureSupervisorInformant(
        SkripsiSeminar $seminar,
        ?int $supervisorId = null,
        ?Carbon $assignedAt = null,
        ?string $note = null
    ): ?ApplicationAssignment {
        $seminar->loadMissing('application');

        if (! $seminar->admin_validated_at || ! $seminar->application_id) {
            return null;
        }

        $supervisorId ??= $seminar->application?->resolveSupervisorLecturerId();
        if (! $supervisorId) {
            return null;
        }

        $assignedAt ??= $seminar->admin_validated_at ?? now();
        $note ??= 'Informasi Review Kelayakan Proposal (Reguler) mahasiswa bimbingan Anda.';

        $existing = ApplicationAssignment::query()
            ->where('application_id', $seminar->application_id)
            ->where('skripsi_seminar_id', $seminar->id)
            ->where('role', 'supervisor_informant')
            ->where('lecturer_id', $supervisorId)
            ->first();

        if ($existing) {
            if ($existing->status !== 'informed') {
                $existing->update([
                    'status' => 'informed',
                    'assigned_at' => $assignedAt,
                    'note' => $note,
                ]);
            }

            return $existing->fresh();
        }

        return ApplicationAssignment::create([
            'application_id' => $seminar->application_id,
            'skripsi_seminar_id' => $seminar->id,
            'lecturer_id' => $supervisorId,
            'role' => 'supervisor_informant',
            'status' => 'informed',
            'assigned_at' => $assignedAt,
            'note' => $note,
        ]);
    }

    /**
     * Backfill penugasan informasi pembimbing untuk seminar yang sudah di-approve sebelumnya.
     */
    public function syncSupervisorInformantsForDosen(int $dosenId): int
    {
        $created = 0;

        SkripsiSeminar::query()
            ->whereNotNull('admin_validated_at')
            ->with('application')
            ->orderBy('id')
            ->each(function (SkripsiSeminar $seminar) use ($dosenId, &$created) {
                if ((int) $seminar->application?->resolveSupervisorLecturerId() !== $dosenId) {
                    return;
                }

                $before = ApplicationAssignment::query()
                    ->where('application_id', $seminar->application_id)
                    ->where('skripsi_seminar_id', $seminar->id)
                    ->where('role', 'supervisor_informant')
                    ->where('lecturer_id', $dosenId)
                    ->exists();

                $assignment = $this->ensureSupervisorInformant($seminar, $dosenId);

                if ($assignment && ! $before) {
                    $created++;
                }
            });

        return $created;
    }

    public function resolveAssignmentAlerts(ApplicationAssignment $assignment, ?array $types = null): void
    {
        $query = AdminAlert::where('assignment_id', $assignment->id)->unresolved();

        if ($types !== null) {
            $query->whereIn('alert_type', $types);
        }

        $query->get()->each->resolve();
    }

    public function resolveStaleAlerts(): int
    {
        $resolved = 0;

        AdminAlert::unresolved()
            ->with('assignment')
            ->get()
            ->each(function (AdminAlert $alert) use (&$resolved) {
                $assignment = $alert->assignment;

                if (! $assignment || $this->shouldResolveAlert($alert, $assignment)) {
                    $alert->resolve();
                    $resolved++;
                }
            });

        return $resolved;
    }

    private function shouldResolveAlert(AdminAlert $alert, ApplicationAssignment $assignment): bool
    {
        if (in_array($alert->alert_type, [
            AdminAlert::TYPE_REVIEWER_FEEDBACK_WARNING,
            AdminAlert::TYPE_REVIEWER_FEEDBACK_OVERDUE,
        ], true)) {
            return $assignment->getRawOriginal('feedback_submitted_at') !== null
                || $assignment->status === 'feedback_submitted';
        }

        if ($alert->alert_type === AdminAlert::TYPE_REVIEWER_NO_RESPONSE) {
            return ! in_array($assignment->status, ['assigned', 'expired'], true);
        }

        return false;
    }

    private function createAlertIfMissing(string $type, ApplicationAssignment $assignment, string $severity, string $message): void
    {
        $exists = AdminAlert::where('assignment_id', $assignment->id)
            ->where('alert_type', $type)
            ->unresolved()
            ->exists();

        if ($exists) {
            return;
        }

        AdminAlert::create([
            'alert_type' => $type,
            'application_id' => $assignment->application_id,
            'assignment_id' => $assignment->id,
            'dosen_id' => $assignment->lecturer_id,
            'severity' => $severity,
            'message' => $message,
            'metadata' => [
                'reviewer_slot' => $assignment->reviewer_slot,
                'skripsi_seminar_id' => $assignment->skripsi_seminar_id,
            ],
        ]);
    }
}
