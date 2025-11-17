<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait FileNamingTrait
{
    /**
     * Generate custom file name for uploaded files
     * Format: {application_id}_{column_name}_{uniqid}.{extension}
     *
     * @param string $collectionName
     * @return string
     */
    protected function generateFileName($collectionName)
    {
        $applicationId = $this->application_id ?? 'no_app';
        $uniqueId = uniqid();
        
        return "{$applicationId}_{$collectionName}_{$uniqueId}";
    }

    /**
     * Add media with custom file naming
     *
     * @param string $file
     * @param string $collectionName
     * @return \Spatie\MediaLibrary\MediaCollections\Models\Media
     */
    public function addMediaWithCustomName($file, $collectionName)
    {
        return $this->addMedia($file)
            ->usingFileName($this->generateCustomFileName($file, $collectionName))
            ->toMediaCollection($collectionName);
    }

    /**
     * Generate complete custom file name with extension
     *
     * @param string $file
     * @param string $collectionName
     * @return string
     */
    protected function generateCustomFileName($file, $collectionName)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $baseName = $this->generateFileName($collectionName);
        
        return "{$baseName}.{$extension}";
    }

    /**
     * Add multiple media files with custom naming
     *
     * @param array $files
     * @param string $collectionName
     * @return void
     */
    public function addMultipleMediaWithCustomName(array $files, $collectionName)
    {
        foreach ($files as $file) {
            $this->addMediaWithCustomName($file, $collectionName);
        }
    }

    /**
     * Get sanitized column name for file naming
     *
     * @param string $collectionName
     * @return string
     */
    protected function sanitizeCollectionName($collectionName)
    {
        return Str::slug($collectionName, '_');
    }
}

