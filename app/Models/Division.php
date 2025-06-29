<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'name',
        'slug',
        'code',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class)->orderBy('sort_order');
    }

    public function activeDepartments(): HasMany
    {
        return $this->departments()->where('is_active', true);
    }

    // Through relationships
    public function users()
    {
        return $this->hasManyThrough(User::class, Department::class);
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

    public function getUniversityAttribute()
    {
        return $this->faculty->university;
    }

    public function getHierarchyPathAttribute(): string
    {
        return "{$this->faculty->university->name} > {$this->faculty->name} > {$this->name}";
    }
}