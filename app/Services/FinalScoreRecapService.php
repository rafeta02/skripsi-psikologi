<?php

namespace App\Services;

use App\Models\ApplicationAction;
use App\Models\ApplicationResultDefense;
use App\Models\ApplicationScore;
use Illuminate\Support\Collection;

class FinalScoreRecapService
{
    public function getComponentLabels(): array
    {
        return ApplicationScore::scoreComponentLabels();
    }

    public function getComponentKeys(): array
    {
        return ApplicationScore::scoreComponentKeys();
    }

    public function getRecap(string $filter = 'finalized'): Collection
    {
        $query = ApplicationResultDefense::with([
            'application.mahasiswa.prodi',
            'application.skripsiDefense',
            'application.skripsiRegistration',
            'application.mbkmRegistration',
            'scores.examiner',
        ])
            ->whereHas('scores', function ($q) {
                $q->whereNotNull('score');
            });

        if ($filter === 'finalized') {
            $finalizedIds = ApplicationAction::where('action_type', 'defense_finalized')
                ->pluck('application_id');
            $query->whereIn('application_id', $finalizedIds);
        }

        return $query->get()
            ->map(fn (ApplicationResultDefense $defense) => $this->formatRecapRow($defense))
            ->sortByDesc('final_score')
            ->values();
    }

    public function getSummary(Collection $recap): array
    {
        $keys = $this->getComponentKeys();
        $componentMeans = [];

        foreach ($keys as $key) {
            $values = $recap->pluck("component_averages.{$key}")->filter(fn ($v) => $v !== null);
            $componentMeans[$key] = $values->count() > 0 ? round($values->avg(), 2) : null;
        }

        $grades = $recap->groupBy('grade')->map->count();

        return [
            'total' => $recap->count(),
            'avg_final_score' => $recap->count() > 0 ? round($recap->avg('final_score'), 2) : 0,
            'component_means' => $componentMeans,
            'grade_distribution' => $grades,
            'finalized_count' => $recap->where('finalized', true)->count(),
        ];
    }

    public function getDetail(ApplicationResultDefense $defense): array
    {
        $defense->load([
            'application.mahasiswa.prodi',
            'application.skripsiDefense',
            'application.skripsiRegistration',
            'application.mbkmRegistration',
            'scores.examiner',
        ]);

        return $this->formatRecapRow($defense, true);
    }

    private function formatRecapRow(ApplicationResultDefense $defense, bool $includeScorers = false): array
    {
        $app = $defense->application;
        $scores = $defense->scores->filter(fn (ApplicationScore $s) => $s->score !== null);
        $keys = $this->getComponentKeys();

        $componentAverages = [];
        foreach ($keys as $key) {
            $values = $scores->pluck($key)->filter(fn ($v) => $v !== null);
            $componentAverages[$key] = $values->count() > 0 ? round($values->avg(), 2) : null;
        }

        $title = $defense->final_title
            ?? $app?->skripsiDefense?->title
            ?? $app?->skripsiRegistration?->title
            ?? $app?->mbkmRegistration?->title_mbkm
            ?? $app?->mbkmRegistration?->title
            ?? '-';

        $row = [
            'defense_id' => $defense->id,
            'application_id' => $defense->application_id,
            'mahasiswa' => $app?->mahasiswa?->nama ?? '-',
            'nim' => $app?->mahasiswa?->nim ?? '-',
            'prodi' => $app?->mahasiswa?->prodi?->name ?? '-',
            'type' => strtoupper($app?->type ?? '-'),
            'judul' => $title,
            'result' => $defense->result,
            'result_label' => ApplicationResultDefense::RESULT_SELECT[$defense->result] ?? ucfirst($defense->result ?? '-'),
            'final_score' => $defense->final_score,
            'grade' => ApplicationResultDefense::convertScoreToGrade($defense->final_score),
            'grade_description' => ApplicationResultDefense::getGradeDescription(
                ApplicationResultDefense::convertScoreToGrade($defense->final_score)
            ),
            'finalized' => $defense->isFinalizedByAdmin(),
            'scorer_count' => $scores->count(),
            'component_averages' => $componentAverages,
        ];

        if ($includeScorers) {
            $row['scorers'] = $scores->map(function (ApplicationScore $score) use ($keys) {
                $components = [];
                foreach ($keys as $key) {
                    $components[$key] = $score->{$key};
                }

                return [
                    'examiner' => $score->examiner?->nama ?? '-',
                    'nidn' => $score->examiner?->nidn ?? '-',
                    'components' => $components,
                    'score' => $score->score,
                    'note' => $score->note,
                ];
            })->values()->all();
        }

        return $row;
    }
}
