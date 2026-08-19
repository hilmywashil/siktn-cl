<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Throwable;

class GoogleDriveService
{
    protected ?Client $client = null;
    protected ?Drive $service = null;
    protected ?string $folderId = null;
    protected bool $isConfigured = false;
    protected array $subfolderCache = [];

    public function __construct()
    {
        $clientId     = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_DRIVE_REFRESH_TOKEN');
        $this->folderId = env('GOOGLE_DRIVE_FOLDER_ID', '1kj_Duf1cShnzUUA6Z64GQ2VbWKEd99KB');

        if (!empty($clientId) && !empty($clientSecret) && !empty($refreshToken) && !empty($this->folderId)) {
            try {
                $this->client = new Client();
                $this->client->setClientId($clientId);
                $this->client->setClientSecret($clientSecret);
                $this->client->refreshToken($refreshToken);
                $this->client->addScope(Drive::DRIVE);

                $this->service = new Drive($this->client);
                $this->isConfigured = true;
            } catch (Throwable $e) {
                Log::error('GoogleDriveService init error: ' . $e->getMessage());
                $this->isConfigured = false;
            }
        }
    }

    public function isConfigured(): bool
    {
        return $this->isConfigured;
    }

    /**
     * Get or create a subfolder inside the main parent folder
     */
    public function getOrCreateSubfolder(string $subfolderName): string
    {
        if (isset($this->subfolderCache[$subfolderName])) {
            return $this->subfolderCache[$subfolderName];
        }

        if (!$this->isConfigured || !$this->service) {
            return $this->folderId;
        }

        try {
            // Search if subfolder already exists in main parent folder
            $query = sprintf(
                "name = '%s' and '%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                addslashes($subfolderName),
                $this->folderId
            );

            $response = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name)',
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
            ]);

            if (count($response->files) > 0) {
                $folderId = $response->files[0]->id;
                $this->subfolderCache[$subfolderName] = $folderId;
                return $folderId;
            }

            // Create new subfolder if not found
            $folderMetadata = new DriveFile([
                'name' => $subfolderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$this->folderId],
            ]);

            $folder = $this->service->files->create($folderMetadata, [
                'fields' => 'id',
                'supportsAllDrives' => true,
            ]);

            // Set public permission for subfolder
            try {
                $permission = new Permission();
                $permission->setRole('reader');
                $permission->setType('anyone');
                $this->service->permissions->create($folder->id, $permission, ['supportsAllDrives' => true]);
            } catch (Throwable $e) {
                // Ignore permission error if any
            }

            $this->subfolderCache[$subfolderName] = $folder->id;
            return $folder->id;
        } catch (Throwable $e) {
            Log::error('GoogleDriveService getOrCreateSubfolder error: ' . $e->getMessage());
            return $this->folderId;
        }
    }

    /**
     * Upload an UploadedFile or local path to Google Drive with Subfolder option
     *
     * @param UploadedFile|string $file
     * @param string|null $customFileName
     * @param string|null $subfolderName (e.g. 'Surat Masuk', 'Surat Keluar', 'Surat Keputusan', 'Notulensi Rapat')
     * @return array|null Returns ['file_id' => string, 'link' => string, 'name' => string]
     */
    public function uploadFile(UploadedFile|string $file, ?string $customFileName = null, ?string $subfolderName = null): ?array
    {
        if (!$this->isConfigured || !$this->service) {
            Log::warning('GoogleDriveService is not properly configured.');
            return null;
        }

        try {
            $filePath = ($file instanceof UploadedFile) ? $file->getRealPath() : $file;
            $fileName = $customFileName ?: (($file instanceof UploadedFile) ? $file->getClientOriginalName() : basename($file));
            $mimeType = ($file instanceof UploadedFile) ? $file->getClientMimeType() : (mime_content_type($filePath) ?: 'application/octet-stream');

            $targetFolderId = $subfolderName ? $this->getOrCreateSubfolder($subfolderName) : $this->folderId;

            $driveFile = new DriveFile();
            $driveFile->setName($fileName);
            if ($targetFolderId) {
                $driveFile->setParents([$targetFolderId]);
            }

            $content = file_get_contents($filePath);

            $result = $this->service->files->create($driveFile, [
                'data'       => $content,
                'mimeType'   => $mimeType,
                'uploadType' => 'multipart',
                'fields'     => 'id, name, webViewLink, webContentLink',
                'supportsAllDrives' => true,
            ]);

            // Set public read permission for the uploaded file
            try {
                $permission = new Permission();
                $permission->setRole('reader');
                $permission->setType('anyone');
                $this->service->permissions->create($result->id, $permission, ['supportsAllDrives' => true]);
            } catch (Throwable $pe) {
                Log::warning('GoogleDriveService permission set error: ' . $pe->getMessage());
            }

            $link = $result->webViewLink ?: "https://drive.google.com/file/d/{$result->id}/view";

            return [
                'file_id' => $result->id,
                'link'    => $link,
                'name'    => $result->name,
            ];
        } catch (Throwable $e) {
            Log::error('GoogleDriveService upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file from Google Drive by File ID or Link
     */
    public function deleteFile(string $fileIdOrUrl): bool
    {
        if (!$this->isConfigured || !$this->service) {
            return false;
        }

        try {
            $fileId = $this->extractFileId($fileIdOrUrl);
            if ($fileId) {
                $this->service->files->delete($fileId, ['supportsAllDrives' => true]);
                return true;
            }
        } catch (Throwable $e) {
            Log::error('GoogleDriveService delete error: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Extract File ID from Google Drive URL if URL is given
     */
    protected function extractFileId(string $fileIdOrUrl): ?string
    {
        if (preg_match('/[-\w]{25,}/', $fileIdOrUrl, $matches)) {
            return $matches[0];
        }
        return $fileIdOrUrl;
    }
}
