<?php

namespace App\Livewire\Sakon\Permissions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.dashboard')]
#[Title('Permission Details')]
class ShowPermission extends Component
{
    use AuthorizesRequests;

    public Permission $permission;
    public $activeTab = 'overview';

    public function mount(Permission $permission)
    {
        $this->permission = $permission->load(['roles.users']);
        $this->authorize('view', $this->permission);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function goToPermissionsList()
    {
        return $this->redirect(route('administrator.permissions.index'), navigate: true);
    }

    public function editPermission()
    {
        return $this->redirect(route('administrator.permissions.edit', $this->permission), navigate: true);
    }

    public function duplicatePermission()
    {
        return $this->redirect(route('administrator.permissions.create', [
            'duplicate' => $this->permission->id
        ]), navigate: true);
    }

    public function getPermissionStatsProperty()
    {
        $rolesWithUsers = $this->permission->roles()->withCount('users')->get();
        $totalUsers = $rolesWithUsers->sum('users_count');

        return [
            'roles_count' => $this->permission->roles()->count(),
            'total_users_affected' => $totalUsers,
            'unique_users_affected' => $this->permission->roles()
                ->with('users')
                ->get()
                ->pluck('users')
                ->flatten()
                ->unique('id')
                ->count(),
            'created_date' => $this->permission->created_at->format('M d, Y'),
            'updated_date' => $this->permission->updated_at->format('M d, Y'),
            'created_datetime' => $this->permission->created_at->format('M d, Y \a\t H:i'),
            'updated_datetime' => $this->permission->updated_at->format('M d, Y \a\t H:i'),
            'created_human' => $this->permission->created_at->diffForHumans(),
            'updated_human' => $this->permission->updated_at->diffForHumans(),
            'age_in_days' => $this->permission->created_at->diffInDays(now()),
        ];
    }

    public function getPermissionCategoryProperty()
    {
        $parts = explode('-', $this->permission->name);
        return count($parts) > 1 ? $parts[1] : 'general';
    }

    public function getPermissionActionProperty()
    {
        $parts = explode('-', $this->permission->name);
        return count($parts) > 1 ? $parts[0] : $this->permission->name;
    }

    public function getRelatedPermissionsProperty()
    {
        $category = $this->permissionCategory;
        if ($category === 'general') {
            return collect();
        }

        return Permission::where('name', 'like', '%-' . $category . '%')
                        ->where('id', '!=', $this->permission->id)
                        ->withCount('roles')
                        ->orderBy('name')
                        ->limit(10)
                        ->get();
    }

    public function getRolesWithPermissionProperty()
    {
        return $this->permission->roles()
                    ->withCount('users')
                    ->with(['users' => function($query) {
                        $query->select('users.id', 'users.name', 'users.email', 'users.created_at')
                              ->orderBy('users.name');
                    }])
                    ->orderBy('name')
                    ->get();
    }

    public function getUnassignedRolesProperty()
    {
        $assignedRoleIds = $this->permission->roles()->pluck('roles.id');

        return Role::whereNotIn('id', $assignedRoleIds)
                   ->withCount('users')
                   ->orderBy('name')
                   ->get();
    }

    public function getPermissionUsageStatsProperty()
    {
        $totalRoles = Role::count();
        $assignedRoles = $this->permission->roles()->count();
        $assignmentPercentage = $totalRoles > 0 ? round(($assignedRoles / $totalRoles) * 100, 1) : 0;

        return [
            'total_roles' => $totalRoles,
            'assigned_roles' => $assignedRoles,
            'unassigned_roles' => $totalRoles - $assignedRoles,
            'assignment_percentage' => $assignmentPercentage,
            'is_widely_used' => $assignmentPercentage > 50,
            'is_rarely_used' => $assignmentPercentage < 20,
        ];
    }

    public function getPermissionSecurityAnalysisProperty()
    {
        $name = $this->permission->name;
        $isHighRisk = false;
        $riskFactors = [];

        // Check for high-risk permission patterns
        $highRiskPatterns = [
            'delete' => 'Contains delete operations',
            'force-delete' => 'Contains force delete operations',
            'admin' => 'Administrative access',
            'manage-system' => 'System management access',
            'manage-user' => 'User management access',
            'super' => 'Super user privileges',
        ];

        foreach ($highRiskPatterns as $pattern => $description) {
            if (str_contains(strtolower($name), $pattern)) {
                $isHighRisk = true;
                $riskFactors[] = $description;
            }
        }

        // Check if widely assigned (potential over-privilege)
        $usageStats = $this->permissionUsageStats;
        if ($usageStats['assignment_percentage'] > 70) {
            $riskFactors[] = 'Widely assigned (' . $usageStats['assignment_percentage'] . '% of roles)';
        }

        return [
            'is_high_risk' => $isHighRisk,
            'risk_factors' => $riskFactors,
            'risk_level' => $isHighRisk ? 'High' : ($usageStats['assignment_percentage'] > 50 ? 'Medium' : 'Low'),
            'recommendations' => $this->getSecurityRecommendations($isHighRisk, $riskFactors, $usageStats),
        ];
    }

    private function getSecurityRecommendations($isHighRisk, $riskFactors, $usageStats)
    {
        $recommendations = [];

        if ($isHighRisk) {
            $recommendations[] = 'Review role assignments carefully for this high-risk permission';
            $recommendations[] = 'Consider implementing additional audit logging';
        }

        if ($usageStats['assignment_percentage'] > 70) {
            $recommendations[] = 'Consider splitting this permission into more specific permissions';
            $recommendations[] = 'Review if all assigned roles truly need this permission';
        }

        if ($usageStats['assignment_percentage'] < 10 && $usageStats['assigned_roles'] > 0) {
            $recommendations[] = 'This permission is rarely used - consider if it\'s still needed';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Permission usage appears appropriate';
        }

        return $recommendations;
    }

    public function getPermissionHistoryProperty()
    {
        // This would require audit logging to be properly implemented
        // For now, we'll return basic creation/update info
        return [
            'created' => [
                'date' => $this->permission->created_at,
                'human' => $this->permission->created_at->diffForHumans(),
                'action' => 'Permission created',
            ],
            'updated' => [
                'date' => $this->permission->updated_at,
                'human' => $this->permission->updated_at->diffForHumans(),
                'action' => 'Permission last modified',
            ],
        ];
    }

    // Quick actions
    public function assignToRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->givePermissionTo($this->permission);

            // Refresh the permission relationship
            $this->permission->refresh();

            session()->flash('success', "Permission assigned to role '{$role->name}' successfully.");

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to assign permission: ' . $e->getMessage());
        }
    }

    public function removeFromRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            $role->revokePermissionTo($this->permission);

            // Refresh the permission relationship
            $this->permission->refresh();

            session()->flash('success', "Permission removed from role '{$role->name}' successfully.");

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove permission: ' . $e->getMessage());
        }
    }

    public function exportPermissionData()
    {
        $data = [
            'permission' => [
                'name' => $this->permission->name,
                'guard_name' => $this->permission->guard_name,
                'created_at' => $this->permission->created_at->toISOString(),
                'updated_at' => $this->permission->updated_at->toISOString(),
            ],
            'roles' => $this->rolesWithPermission->map(function ($role) {
                return [
                    'name' => $role->name,
                    'users_count' => $role->users_count,
                    'users' => $role->users->map(function ($user) {
                        return [
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }),
                ];
            }),
            'stats' => $this->permissionStats,
            'analysis' => $this->permissionSecurityAnalysis,
        ];

        $filename = 'permission-' . str_replace(['/', '\\', ' '], '-', $this->permission->name) . '-' . now()->format('Y-m-d') . '.json';

        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename, ['Content-Type' => 'application/json']);
    }

    public function render()
    {
        return view('livewire.sakon.permissions.show-permission', [
            'permissionStats' => $this->permissionStats,
            'rolesWithPermission' => $this->rolesWithPermission,
            'unassignedRoles' => $this->unassignedRoles,
            'relatedPermissions' => $this->relatedPermissions,
            'usageStats' => $this->permissionUsageStats,
            'securityAnalysis' => $this->permissionSecurityAnalysis,
            'permissionHistory' => $this->permissionHistory,
        ]);
    }
}
