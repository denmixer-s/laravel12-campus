<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'name',
        'slug',
        'code',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function activeUsers(): HasMany
    {
        return $this->users()->where('status', 'active');
    }

    // Accessor เพื่อเข้าถึง Faculty และ University
    public function getFacultyAttribute()
    {
        return $this->division->faculty;
    }

    public function getUniversityAttribute()
    {
        return $this->division->faculty->university;
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

    public function getHierarchyPathAttribute(): string
    {
        return "{$this->university->name} > {$this->faculty->name} > {$this->division->name} > {$this->name}";
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // Accessors & Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str()->slug($value);
    }

    // Methods// Comment หรือลบใน Department.php
        // public function getRouteKeyName()
        // {
        //     return 'slug';
        // }
}
