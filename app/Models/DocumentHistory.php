<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'action',
        'old_values',
        'new_values',
        'notes',
        'performed_by',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('performed_by', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'สร้างเอกสาร',
            'updated' => 'แก้ไขเอกสาร',
            'published' => 'เผยแพร่เอกสาร',
            'archived' => 'เก็บถาวรเอกสาร',
            'downloaded' => 'ดาวน์โหลดเอกสาร',
            'viewed' => 'เปิดดูเอกสาร',
            default => $this->action,
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'fas fa-plus-circle text-green-500',
            'updated' => 'fas fa-edit text-blue-500',
            'published' => 'fas fa-share text-purple-500',
            'archived' => 'fas fa-archive text-yellow-500',
            'downloaded' => 'fas fa-download text-gray-500',
            'viewed' => 'fas fa-eye text-indigo-500',
            default => 'fas fa-info-circle text-gray-500',
        };
    }

    public function getChangesFormattedAttribute(): array
    {
        if (empty($this->new_values) || empty($this->old_values)) {
            return [];
        }

        $changes = [];
        $fieldsLabels = [
            'title' => 'ชื่อเอกสาร',
            'description' => 'คำอธิบาย',
            'status' => 'สถานะ',
            'access_level' => 'ระดับการเข้าถึง',
            'document_category_id' => 'หมวดหมู่',
            'document_type_id' => 'ประเภท',
            'is_featured' => 'เอกสารแนะนำ',
            'is_new' => 'เอกสารใหม่',
        ];

        foreach ($this->new_values as $field => $newValue) {
            if (isset($this->old_values[$field]) && $this->old_values[$field] != $newValue) {
                $changes[] = [
                    'field' => $fieldsLabels[$field] ?? $field,
                    'old_value' => $this->old_values[$field],
                    'new_value' => $newValue,
                ];
            }
        }

        return $changes;
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            $history->created_at = now();
        });
    }
}
