<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Document extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'document_number',
        'title',
        'description',
        'document_category_id',
        'document_type_id',
        'created_by',
        'department_id',
        'status',
        'access_level',
        'document_date',
        'published_at',
        'download_count',
        'view_count',
        'version',
        'parent_document_id',
        'tags',
        'keywords',
        'reference_number',
        'is_featured',
        'is_new',
        'file_size',
        'mime_type',
        'original_filename',
    ];

    protected $casts = [
        'document_date'  => 'date',
        'published_at'   => 'datetime',
        'tags'           => 'array',
        'is_featured'    => 'boolean',
        'is_new'         => 'boolean',
        'download_count' => 'integer',
        'view_count'     => 'integer',
        'file_size'      => 'integer',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_document_id')->orderBy('version');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(DocumentDownload::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopePublic($query)
    {
        return $query->where('access_level', 'public');
    }

    public function scopeRegistered($query)
    {
        return $query->where('access_level', 'registered');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('document_category_id', $categoryId);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('document_type_id', $typeId);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('keywords', 'LIKE', "%{$search}%")
                ->orWhere('document_number', 'LIKE', "%{$search}%");
        });
    }

    // Accessors
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft' => ['class' => 'bg-gray-100 text-gray-800', 'text' => 'แบบร่าง'],
            'published'  => ['class' => 'bg-green-100 text-green-800', 'text' => 'เผยแพร่'],
            'archived'   => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'เก็บถาวร'],
            default      => ['class' => 'bg-gray-100 text-gray-800', 'text' => $this->status],
        };
    }

    public function getAccessLevelBadgeAttribute(): array
    {
        return match ($this->access_level) {
            'public'     => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'สาธารณะ'],
            'registered' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'สมาชิก'],
            default      => ['class' => 'bg-gray-100 text-gray-800', 'text' => $this->access_level],
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (! $this->file_size) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    public function getIsPublicAttribute(): bool
    {
        return $this->access_level === 'public';
    }

    // Methods
    public function generateDocumentNumber(): string
    {
        $prefix = 'DOC';
        $year   = date('Y');
        $month  = date('m');

        $lastDocument = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastDocument ? (int) substr($lastDocument->document_number, -4) + 1 : 1;

        return sprintf('%s%s%s%04d', $prefix, $year, $month, $number);
    }

    public function publish(): bool
    {
        return $this->update([
            'status'       => 'published',
            'published_at' => now(),
        ]);
    }

    public function archive(): bool
    {
        return $this->update(['status' => 'archived']);
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function canBeAccessedBy(?User $user = null): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        if ($this->access_level === 'public') {
            return true;
        }

        return $user !== null;
    }

    // Media Library
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Add conversions if needed (thumbnails, etc.)
    }

    // 🔧 เอา getRouteKeyName() ออก - ใช้ ID แทน
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    // 🔧 ปิด Boot method ชั่วคราว
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($document) {
    //         if (empty($document->document_number)) {
    //             $document->document_number = $document->generateDocumentNumber();
    //         }
    //     });

    //     static::created(function ($document) {
    //         DocumentHistory::create([
    //             'document_id' => $document->id,
    //             'action' => 'created',
    //             'performed_by' => auth()->id() ?? $document->created_by,
    //             'new_values' => $document->toArray(),
    //         ]);
    //     });

    //     static::updated(function ($document) {
    //         if ($document->wasChanged()) {
    //             DocumentHistory::create([
    //                 'document_id' => $document->id,
    //                 'action' => 'updated',
    //                 'performed_by' => auth()->id(),
    //                 'old_values' => $document->getOriginal(),
    //                 'new_values' => $document->getChanges(),
    //             ]);
    //         }
    //     });
    // }

    // เพิ่มใน Document.php
    public function getOriginalFilenameAttribute()
    {
        $media = $this->getFirstMedia('documents');
        if ($media) {
            // ลองเอาจาก custom properties ก่อน
            $originalName = $media->getCustomProperty('original_filename');
            if ($originalName) {
                return $originalName;
            }

            // ถ้าไม่มีให้ใช้จาก database
            return $this->attributes['original_filename'] ?? $media->name;
        }

        return $this->attributes['original_filename'] ?? null;
    }

    public function getFileUrlAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->getUrl() : null;
    }

    public function getHashFilenameAttribute()
    {
        $media = $this->getFirstMedia('documents');
        return $media ? $media->file_name : null;
    }
}
