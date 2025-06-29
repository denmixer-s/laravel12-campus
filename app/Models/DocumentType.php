<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'allowed_extensions',
        'is_active',
    ];

    protected $casts = [
        'allowed_extensions' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getAllowedExtensionsStringAttribute(): string
    {
        return implode(', ', $this->allowed_extensions ?? []);
    }

    public function getIconClassAttribute(): string
    {
        return match($this->slug) {
            'pdf' => 'fas fa-file-pdf text-red-500',
            'word' => 'fas fa-file-word text-blue-500',
            'excel' => 'fas fa-file-excel text-green-500',
            'powerpoint' => 'fas fa-file-powerpoint text-orange-500',
            'image' => 'fas fa-file-image text-purple-500',
            default => 'fas fa-file text-gray-500',
        };
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = str()->slug($value);
        }
    }

    // Methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function isExtensionAllowed(string $extension): bool
    {
        if (empty($this->allowed_extensions)) {
            return true;
        }

        return in_array(strtolower($extension), $this->allowed_extensions);
    }

    public function getValidationRule(): string
    {
        if (empty($this->allowed_extensions)) {
            return 'file';
        }

        return 'file|mimes:' . implode(',', $this->allowed_extensions);
    }
}
