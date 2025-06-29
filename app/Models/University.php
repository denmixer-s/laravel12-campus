<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class)->orderBy('sort_order');
    }

    public function activeFaculties(): HasMany
    {
        return $this->faculties()->where('is_active', true);
    }

    public function facultiesOnly(): HasMany
    {
        return $this->faculties()->where('type', 'faculty');
    }

    public function offices(): HasMany
    {
        return $this->faculties()->where('type', 'office');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->code ? "{$this->code} - {$this->name}" : $this->name;
    }

    // Methods
    public function getTotalUsersCount(): int
    {
        return $this->faculties()
            ->withCount(['divisions.departments.users'])
            ->get()
            ->sum('divisions_count');
    }
}