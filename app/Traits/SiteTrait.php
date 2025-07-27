<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait SiteTrait
{
    /**
     * Upload a file to storage
     *
     * @param mixed $file
     * @param string $folder
     * @param string $disk
     * @param string|null $fileName
     * @return string|null
     */
    public function uploadFile($file, string $folder = 'uploads', string $disk = 'public', ?string $fileName = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $extension = $file->getClientOriginalExtension();
        $name = $fileName ?? Str::random(25);
        $fullName = $name . '.' . $extension;
        $filePath = $file->storeAs($folder, $fullName, $disk);

        return $filePath;
    }

    /**
     * Upload multiple files
     *
     * @param array $files
     * @param string $folder
     * @param string $disk
     * @return array
     */
    public function uploadMultipleFiles(array $files, string $folder = 'uploads', string $disk = 'public'): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFiles[] = $this->uploadFile($file, $folder, $disk);
        }

        return array_filter($uploadedFiles);
    }

    /**
     * Delete a file from storage
     *
     * @param string $path
     * @param string $disk
     * @return bool
     */
    public function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple files
     *
     * @param array $paths
     * @param string $disk
     * @return bool
     */
    public function deleteMultipleFiles(array $paths, string $disk = 'public'): bool
    {
        $success = true;

        foreach ($paths as $path) {
            if (!$this->deleteFile($path, $disk)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Get file URL
     *
     * @param string|null $path
     * @param string $disk
     * @return string|null
     */
    public function getFileUrl(?string $path, string $disk = 'public'): ?string
    {
        if (!$path) {
            return null;
        }

        return Storage::disk($disk)->url($path);
    }
}
