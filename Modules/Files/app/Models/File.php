<?php

namespace Modules\Files\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Modules\Organizations\Models\Organization;

class File extends Model
{
    protected $fillable = [
        'organization_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'extension',
        'size',
        'visibility',
        'uploaded_by',
        'attachable_type',
        'attachable_id',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // ---------------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------------

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    // ---------------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------------

    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('original_name', 'like', "%{$term}%")
              ->orWhere('mime_type', 'like', "%{$term}%")
              ->orWhere('extension', 'like', "%{$term}%");
        });
    }

    // ---------------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------------

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function humanSize(): string
    {
        $bytes = $this->size ?? 0;

        if ($bytes < 1024) {
            return "{$bytes} B";
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / 1048576, 1) . ' MB';
    }
}
