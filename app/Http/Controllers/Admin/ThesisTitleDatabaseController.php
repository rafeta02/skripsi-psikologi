<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThesisTitleEntry;
use App\Services\ThesisTitleDatabaseService;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ThesisTitleDatabaseController extends Controller
{
    public function __construct(private ThesisTitleDatabaseService $titleService)
    {
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('thesis_title_database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = trim($request->get('q', ''));

        $entries = $this->titleService->getAllEntries();

        if ($query !== '') {
            $entries = $this->titleService->filterByKeywords($entries, $query);
        }

        $entries = $this->titleService->markDuplicates($entries);
        $summary = $this->titleService->getSummary($entries);

        return view('admin.thesisTitleDatabase.index', compact('entries', 'summary', 'query'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('thesis_title_database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'title' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'nama' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:30',
            'angkatan' => 'nullable|string|max:10',
            'pembimbing' => 'nullable|string|max:255',
            'penguji_1' => 'nullable|string|max:255',
            'penguji_2' => 'nullable|string|max:255',
            'tanggal_sidang' => 'nullable|date',
        ]);

        $this->titleService->createManualEntry($validated, (int) auth()->id());

        return redirect()
            ->route('admin.thesis-title-database.index')
            ->with('success', 'Judul berhasil ditambahkan ke database.');
    }

    public function destroy(ThesisTitleEntry $thesisTitleEntry)
    {
        abort_if(Gate::denies('thesis_title_database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $thesisTitleEntry->delete();

        return redirect()
            ->route('admin.thesis-title-database.index')
            ->with('success', 'Entri manual berhasil dihapus.');
    }

    public function import(Request $request)
    {
        abort_if(Gate::denies('thesis_title_database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        try {
            $result = $this->titleService->importFromCsv(
                $request->file('csv_file'),
                (int) auth()->id()
            );
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.thesis-title-database.index')
                ->with('error', $e->getMessage());
        }

        $message = "Import selesai: {$result['imported']} judul ditambahkan";
        if ($result['skipped'] > 0) {
            $message .= ", {$result['skipped']} baris dilewati.";
        }

        return redirect()
            ->route('admin.thesis-title-database.index')
            ->with('success', $message)
            ->with('import_errors', $result['errors']);
    }

    public function downloadTemplate(): StreamedResponse
    {
        abort_if(Gate::denies('thesis_title_database_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $content = $this->titleService->getCsvTemplateContent();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'template-database-judul.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
