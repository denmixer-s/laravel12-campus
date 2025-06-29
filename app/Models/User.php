<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'status',
        'user_type',
        'department_id',
        'email_verified_at',
        'last_login_at',
        'password_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at'   => 'datetime',
        'last_login_at'       => 'datetime',
        'password_updated_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Organization Relationships
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Blog Relations
     */
    public function blogPosts()
    {
        return $this->hasMany(\App\Models\BlogPost::class, 'user_id');
    }

    public function publishedBlogPosts()
    {
        return $this->blogPosts()->where('status', 'published');
    }

    public function draftBlogPosts()
    {
        return $this->blogPosts()->where('status', 'draft');
    }

    public function blogComments()
    {
        return $this->hasMany(\App\Models\BlogComment::class, 'user_id');
    }

    public function blogPostLikes()
    {
        return $this->hasMany(\App\Models\BlogPostLike::class, 'user_id');
    }

    public function blogPostViews()
    {
        return $this->hasMany(\App\Models\BlogPostView::class, 'user_id');
    }

    /**
     * Document Relations
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function documentHistories(): HasMany
    {
        return $this->hasMany(DocumentHistory::class, 'performed_by');
    }

    public function documentDownloads(): HasMany
    {
        return $this->hasMany(DocumentDownload::class);
    }

    // =========================================================================
    // ACCESSORS & ATTRIBUTES
    // =========================================================================

    /**
     * Organization Accessors
     */
    public function getDivisionAttribute()
    {
        return $this->department?->division;
    }

    public function getFacultyAttribute()
    {
        return $this->department?->division?->faculty;
    }

    public function getUniversityAttribute()
    {
        return $this->department?->division?->faculty?->university;
    }

    public function getOrganizationHierarchyAttribute(): string
    {
        if (!$this->department) {
            return 'ไม่ได้ระบุหน่วยงาน';
        }

        return $this->department->hierarchy_path;
    }

    /**
     * User Type Accessors
     */
    public function getIsStaffAttribute(): bool
    {
        return $this->user_type === 'staff';
    }

    public function getIsPublicAttribute(): bool
    {
        return $this->user_type === 'public';
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Blog Statistics
     */
    public function getBlogStatsAttribute()
    {
        return [
            'total_posts'     => $this->blogPosts()->count(),
            'published_posts' => $this->publishedBlogPosts()->count(),
            'draft_posts'     => $this->draftBlogPosts()->count(),
            'total_views'     => $this->blogPosts()->sum('views_count'),
            'total_likes'     => $this->blogPosts()->withCount('likes')->get()->sum('likes_count'),
            'total_comments'  => $this->blogPosts()->withCount('comments')->get()->sum('comments_count'),
        ];
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeStaff($query)
    {
        return $query->where('user_type', 'staff');
    }

    public function scopePublic($query)
    {
        return $query->where('user_type', 'public');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByFaculty($query, $facultyId)
    {
        return $query->whereHas('department.division', function ($q) use ($facultyId) {
            $q->where('faculty_id', $facultyId);
        });
    }

    public function scopeByDivision($query, $divisionId)
    {
        return $query->whereHas('department', function ($q) use ($divisionId) {
            $q->where('division_id', $divisionId);
        });
    }

    public function scopeByUniversity($query, $universityId)
    {
        return $query->whereHas('department.division.faculty', function ($q) use ($universityId) {
            $q->where('university_id', $universityId);
        });
    }

    // =========================================================================
    // ORGANIZATION MANAGEMENT METHODS
    // =========================================================================

    /**
     * Role Checking Methods
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    public function isSystemAdmin(): bool
    {
        return $this->hasRole('System Admin');
    }

    public function isOrganizationAdmin(): bool
    {
        return $this->hasRole('Organization Admin');
    }

    public function isFacultyAdmin(): bool
    {
        return $this->hasRole('Faculty Admin');
    }

    public function isDivisionAdmin(): bool
    {
        return $this->hasRole('Division Admin');
    }

    public function isDepartmentAdmin(): bool
    {
        return $this->hasRole('Department Admin');
    }

    public function isStaffMember(): bool
    {
        return $this->hasRole('Staff');
    }

    public function isAnyAdmin(): bool
    {
        return $this->hasAnyRole([
            'Super Admin',
            'System Admin',
            'Organization Admin',
            'Faculty Admin',
            'Division Admin',
            'Department Admin'
        ]);
    }

    /**
     * Permission Checking Methods
     */
    public function canManageOrganizations(): bool
    {
        return $this->hasAnyPermission([
            'organizations.manage-all',
            'organizations.universities.manage',
            'organizations.faculties.manage',
            'organizations.divisions.manage',
            'organizations.departments.manage'
        ]);
    }

    public function canManageUniversities(): bool
    {
        return $this->hasAnyPermission([
            'organizations.universities.manage',
            'organizations.manage-all'
        ]);
    }

    public function canManageFaculties(): bool
    {
        return $this->hasAnyPermission([
            'organizations.faculties.manage',
            'organizations.manage-all'
        ]);
    }

    public function canManageDivisions(): bool
    {
        return $this->hasAnyPermission([
            'organizations.divisions.manage',
            'organizations.manage-all'
        ]);
    }

    public function canManageDepartments(): bool
    {
        return $this->hasAnyPermission([
            'organizations.departments.manage',
            'organizations.manage-all'
        ]);
    }

    public function canManageStaff(): bool
    {
        return $this->hasAnyPermission([
            'organizations.staff.manage',
            'organizations.staff.assign-department'
        ]);
    }

    /**
     * Get user's management scope
     */
    public function getManagementScope(): string
    {
        if ($this->isSuperAdmin()) {
            return 'system';
        }

        if ($this->isSystemAdmin() || $this->isOrganizationAdmin()) {
            return 'organization';
        }

        if ($this->isFacultyAdmin()) {
            return 'faculty';
        }

        if ($this->isDivisionAdmin()) {
            return 'division';
        }

        if ($this->isDepartmentAdmin()) {
            return 'department';
        }

        return 'none';
    }

    /**
     * Get accessible organizations based on user role and department
     */
    public function getAccessibleUniversities()
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return University::active()->get();
        }

        if ($this->department) {
            return University::where('id', $this->university->id)->active()->get();
        }

        return collect();
    }

    public function getAccessibleFaculties($universityId = null)
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            $query = Faculty::active();
            if ($universityId) {
                $query->where('university_id', $universityId);
            }
            return $query->get();
        }

        if ($this->department) {
            $facultyId = $this->faculty->id;
            $query = Faculty::where('id', $facultyId)->active();

            if ($universityId && $this->university->id != $universityId) {
                return collect();
            }

            return $query->get();
        }

        return collect();
    }

    public function getAccessibleDivisions($facultyId = null)
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            $query = Division::active();
            if ($facultyId) {
                $query->where('faculty_id', $facultyId);
            }
            return $query->get();
        }

        if ($this->hasRole('Faculty Admin') && $this->department) {
            $userFacultyId = $this->faculty->id;
            $query = Division::where('faculty_id', $userFacultyId)->active();

            if ($facultyId && $facultyId != $userFacultyId) {
                return collect();
            }

            return $query->get();
        }

        if ($this->department) {
            $divisionId = $this->division->id;
            $query = Division::where('id', $divisionId)->active();

            if ($facultyId && $this->faculty->id != $facultyId) {
                return collect();
            }

            return $query->get();
        }

        return collect();
    }

    public function getAccessibleDepartments($divisionId = null)
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            $query = Department::active();
            if ($divisionId) {
                $query->where('division_id', $divisionId);
            }
            return $query->get();
        }

        if ($this->hasRole('Faculty Admin') && $this->department) {
            $query = Department::whereHas('division', function ($q) {
                $q->where('faculty_id', $this->faculty->id);
            })->active();

            if ($divisionId) {
                $userDivisionIds = $this->faculty->divisions->pluck('id');
                if (!$userDivisionIds->contains($divisionId)) {
                    return collect();
                }
                $query->where('division_id', $divisionId);
            }

            return $query->get();
        }

        if ($this->hasRole('Division Admin') && $this->department) {
            $userDivisionId = $this->division->id;
            $query = Department::where('division_id', $userDivisionId)->active();

            if ($divisionId && $divisionId != $userDivisionId) {
                return collect();
            }

            return $query->get();
        }

        if ($this->department) {
            $departmentId = $this->department->id;
            $query = Department::where('id', $departmentId)->active();

            if ($divisionId && $this->division->id != $divisionId) {
                return collect();
            }

            return $query->get();
        }

        return collect();
    }

    /**
     * Check if user can access specific organization units
     */
    public function canAccessUniversity(University $university): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return true;
        }

        if ($this->department) {
            return $this->university->id === $university->id;
        }

        return false;
    }

    public function canAccessFaculty(Faculty $faculty): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return true;
        }

        if ($this->department) {
            return $this->faculty->id === $faculty->id;
        }

        return false;
    }

    public function canAccessDivision(Division $division): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return true;
        }

        if ($this->department) {
            if ($this->hasRole('Faculty Admin')) {
                return $this->faculty->id === $division->faculty_id;
            }
            return $this->division->id === $division->id;
        }

        return false;
    }

    public function canAccessDepartment(Department $department): bool
    {
        if ($this->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return true;
        }

        if ($this->department) {
            if ($this->hasRole('Faculty Admin')) {
                return $this->faculty->id === $department->division->faculty_id;
            }
            if ($this->hasRole('Division Admin')) {
                return $this->division->id === $department->division_id;
            }
            return $this->department->id === $department->id;
        }

        return false;
    }

    /**
     * Get user's organization statistics
     */
    public function getOrganizationStats(): array
    {
        $scope = $this->getManagementScope();
        $stats = [
            'universities' => 0,
            'faculties' => 0,
            'divisions' => 0,
            'departments' => 0,
            'staff' => 0
        ];

        switch ($scope) {
            case 'system':
            case 'organization':
                $stats['universities'] = University::active()->count();
                $stats['faculties'] = Faculty::active()->count();
                $stats['divisions'] = Division::active()->count();
                $stats['departments'] = Department::active()->count();
                $stats['staff'] = User::staff()->count();
                break;

            case 'faculty':
                if ($this->department) {
                    $stats['universities'] = 1;
                    $stats['faculties'] = 1;
                    $stats['divisions'] = $this->faculty->divisions()->active()->count();
                    $stats['departments'] = Department::whereHas('division', function ($q) {
                        $q->where('faculty_id', $this->faculty->id);
                    })->active()->count();
                    $stats['staff'] = User::byFaculty($this->faculty->id)->staff()->count();
                }
                break;

            case 'division':
                if ($this->department) {
                    $stats['universities'] = 1;
                    $stats['faculties'] = 1;
                    $stats['divisions'] = 1;
                    $stats['departments'] = $this->division->departments()->active()->count();
                    $stats['staff'] = User::byDivision($this->division->id)->staff()->count();
                }
                break;

            case 'department':
                if ($this->department) {
                    $stats['universities'] = 1;
                    $stats['faculties'] = 1;
                    $stats['divisions'] = 1;
                    $stats['departments'] = 1;
                    $stats['staff'] = User::byDepartment($this->department->id)->staff()->count();
                }
                break;
        }

        return $stats;
    }

    /**
     * Get users under management scope
     */
    public function getManagedUsers()
    {
        $scope = $this->getManagementScope();

        switch ($scope) {
            case 'system':
            case 'organization':
                return User::staff();

            case 'faculty':
                if ($this->department) {
                    return User::byFaculty($this->faculty->id)->staff();
                }
                break;

            case 'division':
                if ($this->department) {
                    return User::byDivision($this->division->id)->staff();
                }
                break;

            case 'department':
                if ($this->department) {
                    return User::byDepartment($this->department->id)->staff();
                }
                break;
        }

        return User::whereRaw('1 = 0'); // Empty query
    }

    /**
     * Check if user can manage specific user
     */
    public function canManageUser(User $targetUser): bool
    {
        // Cannot manage yourself
        if ($this->id === $targetUser->id) {
            return false;
        }

        // Super Admin and System Admin can manage all users
        if ($this->hasAnyRole(['Super Admin', 'System Admin'])) {
            return true;
        }

        // Organization Admin can manage all staff
        if ($this->isOrganizationAdmin()) {
            return true;
        }

        // Check hierarchy-based management
        if (!$targetUser->department) {
            return false;
        }

        switch ($this->getManagementScope()) {
            case 'faculty':
                return $this->faculty->id === $targetUser->faculty->id;

            case 'division':
                return $this->division->id === $targetUser->division->id;

            case 'department':
                return $this->department->id === $targetUser->department->id;
        }

        return false;
    }

    /**
     * Get organization breadcrumb
     */
    public function getOrganizationBreadcrumb(): array
    {
        if (!$this->department) {
            return [];
        }

        return [
            [
                'name' => $this->university->name,
                'type' => 'university',
                'id' => $this->university->id
            ],
            [
                'name' => $this->faculty->name,
                'type' => 'faculty',
                'id' => $this->faculty->id
            ],
            [
                'name' => $this->division->name,
                'type' => 'division',
                'id' => $this->division->id
            ],
            [
                'name' => $this->department->name,
                'type' => 'department',
                'id' => $this->department->id
            ]
        ];
    }

    /**
     * Navigation permissions for organization menu
     */
    public function canAccessOrganizationMenu(): bool
    {
        return $this->hasAnyPermission([
            'organizations.view-all',
            'organizations.universities.view',
            'organizations.faculties.view',
            'organizations.divisions.view',
            'organizations.departments.view',
            'organizations.staff.view'
        ]);
    }

    public function getOrganizationMenuItems(): array
    {
        $items = [];

        if ($this->can('access-university-management')) {
            $items[] = [
                'name' => 'มหาวิทยาลัย',
                'route' => 'administrator.organization.universities.index',
                'permission' => 'organizations.universities.view'
            ];
        }

        if ($this->can('access-faculty-management')) {
            $items[] = [
                'name' => 'คณะ/หน่วยงาน',
                'route' => 'administrator.organization.faculties.index',
                'permission' => 'organizations.faculties.view'
            ];
        }

        if ($this->can('access-division-management')) {
            $items[] = [
                'name' => 'ภาควิชา',
                'route' => 'administrator.organization.divisions.index',
                'permission' => 'organizations.divisions.view'
            ];
        }

        if ($this->can('access-department-management')) {
            $items[] = [
                'name' => 'งาน/แผนก',
                'route' => 'administrator.organization.departments.index',
                'permission' => 'organizations.departments.view'
            ];
        }

        if ($this->can('access-staff-management')) {
            $items[] = [
                'name' => 'จัดการบุคลากร',
                'route' => 'administrator.organization.staff.index',
                'permission' => 'organizations.staff.view'
            ];
        }

        return $items;
    }

    // =========================================================================
    // GENERAL METHODS
    // =========================================================================

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Blog Management Methods
     */
    public function canManageBlog()
    {
        return $this->can('blog.posts.view');
    }

    public function isBlogAuthor()
    {
        return $this->blogPosts()->exists();
    }

    /**
     * Document Management Methods
     */
    public function canAccessDocument(Document $document): bool
    {
        // Public documents - everyone can access
        if ($document->access_level === 'public') {
            return true;
        }

        // Registered documents - logged in users only
        if ($document->access_level === 'registered') {
            return true;
        }

        return false;
    }

    public function canManageDocument(Document $document): bool
    {
        // Own documents
        if ($document->created_by === $this->id) {
            return true;
        }

        // Staff can manage documents in their department
        if ($this->is_staff && $this->department_id === $document->department_id) {
            return $this->can('documents.edit');
        }

        // Admin permissions
        return $this->can('documents.manage-all');
    }
}
