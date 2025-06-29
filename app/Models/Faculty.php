<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'slug',
        'code',
        'description',
        'type',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class)->orderBy('sort_order');
    }

    public function activeDivisions(): HasMany
    {
        return $this->divisions()->where('is_active', true);
    }

    // Through relationships
    public function departments()
    {
        return $this->hasManyThrough(Department::class, Division::class);
    }

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Department::class,
            'division_id', // Foreign key on departments table
            'department_id', // Foreign key on users table
            'id', // Local key on faculties table
            'id' // Local key on departments table
        )->join('divisions', 'departments.division_id', '=', 'divisions.id')
          ->where('divisions.faculty_id', $this->id);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFaculties(Builder $query): Builder
    {
        return $query->where('type', 'faculty');
    }

    public function scopeOffices(Builder $query): Builder
    {
        return $query->where('type', 'office');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->code ? "{$this->code} - {$this->name}" : $this->name;
    }

    public function getIsFacultyAttribute(): bool
    {
        return $this->type === 'faculty';
    }

    public function getIsOfficeAttribute(): bool
    {
        return $this->type === 'office';
    }

    public function getHierarchyPathAttribute(): string
    {
        return "{$this->university->name} > {$this->name}";
    }
}