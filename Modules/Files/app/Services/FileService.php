<?php

namespace Modules\Files\Services;

use Illuminate\Http\UploadedFile;
use Modules\Files\Models\File;

class FileService
{
    /**
     * Store an uploaded file and create a File record.
     *
     * @param  UploadedFile  $upload
     * @param  string        $disk        e.g. 'local', 'public', 's3'
     * @param  string        $directory   Storage path prefix
     * @param  int|null      $organizationId
     * @param  string        $visibility  'private' or 'public'
     */
    public function store(
        UploadedFile $upload,
        string       $disk        = 'local',
        string       $directory   = 'uploads',
        ?int         $organizationId = null,
        string       $visibility  = 'private',
    ): File {
        $path = $upload->store($directory, $disk);

        return File::create([
            'organization_id' => $organizationId,
            'disk'            => $disk,
            'path'            => $path,
            'original_name'   => $upload->getClientOriginalName(),
            'mime_type'       => $upload->getMimeType(),
            'extension'       => $upload->getClientOriginalExtension(),
            'size'            => $upload->getSize(),
            'visibility'      => $visibility,
            'uploaded_by'     => auth()->id(),
        ]);
    }

    /**
     * Attach a stored file to an Eloquent model.
     */
    public function attachTo(File $file, \Illuminate\Database\Eloquent\Model $model): void
    {
        $file->update([
            'attachable_type' => get_class($model),
            'attachable_id'   => $model->getKey(),
        ]);
    }

    /**
     * Delete a file from storage and remove the record.
     */
    public function delete(File $file): void
    {
        \Illuminate\Support\Facades\Storage::disk($file->disk)->delete($file->path);
        $file->delete();
    }
}
