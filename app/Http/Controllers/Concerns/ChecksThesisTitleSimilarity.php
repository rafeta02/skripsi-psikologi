<?php

namespace App\Http\Controllers\Concerns;

use App\Services\ThesisTitleDatabaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait ChecksThesisTitleSimilarity
{
    protected function redirectIfSimilarTitleUnacknowledged(
        Request $request,
        string $title,
        ?string $titleEn = null
    ): ?RedirectResponse {
        if ($request->boolean('acknowledge_similar_title')) {
            return null;
        }

        $matches = app(ThesisTitleDatabaseService::class)->findSimilarTitles($title, $titleEn);

        if ($matches === []) {
            return null;
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('title_similarity_warning', true)
            ->with('title_similar_matches', $matches);
    }
}
