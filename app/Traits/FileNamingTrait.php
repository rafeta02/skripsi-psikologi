<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

trait FileNamingTrait
{
    /**
     * Generate custom file name for uploaded files
     * Format: {application_id}_{column_name}_{uniqid}
     * Models may override for richer naming (e.g. MBKM_KHS_NIM_...).
     */
    protected function generateFileName($collectionName, ?int $index = null)
    {
        $applicationId = $this->application_id ?? 'no_app';
        $uniqueId = uniqid();
        $suffix = $index !== null ? "_{$index}" : '';

        return "{$applicationId}_{$collectionName}{$suffix}_{$uniqueId}";
    }

    /**
     * Add media with custom file naming
     */
    public function addMediaWithCustomName($file, $collectionName, ?int $index = null, bool $preservingOriginal = false)
    {
        $adder = $this->addMedia($file)
            ->usingFileName($this->generateCustomFileName($file, $collectionName, $index));

        if ($preservingOriginal) {
            $adder->preservingOriginal();
        }

        return $adder->toMediaCollection($collectionName);
    }

    /**
     * Generate complete custom file name with extension
     */
    public function generateCustomFileName($file, $collectionName, ?int $index = null)
    {
        $extension = $this->resolveFileExtension($file);
        $baseName = $this->generateFileName($collectionName, $index);

        return "{$baseName}.{$extension}";
    }

    /**
     * Add multiple media files with custom naming
     */
    public function addMultipleMediaWithCustomName(array $files, $collectionName)
    {
        $index = 1;
        foreach ($files as $file) {
            if (!$file) {
                continue;
            }
            $this->addMediaWithCustomName($file, $collectionName, $index);
            $index++;
        }
    }

    /**
     * Get sanitized column name for file naming
     */
    protected function sanitizeCollectionName($collectionName)
    {
        return Str::slug($collectionName, '_');
    }

    protected function resolveFileExtension($file): string
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
        }

        if ($file instanceof SymfonyFile) {
            return $file->getExtension() ?: pathinfo($file->getFilename(), PATHINFO_EXTENSION) ?: 'bin';
        }

        $extension = pathinfo((string) $file, PATHINFO_EXTENSION);

        return $extension !== '' ? $extension : 'bin';
    }
}
