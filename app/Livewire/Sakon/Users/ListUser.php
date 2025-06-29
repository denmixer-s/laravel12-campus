<?php

namespace App\Livewire\Sakon\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Users')]
class ListUser extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $verificationFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Delete confirmation
    public $confirmingUserDeletion = false;
    public $userToDelete = null;
    public $userToDeleteName = '';

    // Bulk actions
    public $selectedUsers = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Bulk confirmation
    public $confirmingBulkAction = false;
    public $bulkActionType = '';
    public $bulkActionMessage = '';

    // Password reset
    public $confirmingPasswordReset = false;
    public $userToResetPassword = null;
    public $newPassword = '';
    public $showNewPassword = false;

    // Real-time search
    protected $listeners = [
        'userCreated' => 'handleUserCreated',
        'userUpdated' => 'handleUserUpdated',
        'userDeleted' => 'handleUserDeleted',
        'refresh' => '$refresh',
    ];

    // Validation rules
    protected $rules = [
        'newPassword' => 'required|string|min:8',
    ];

    protected $messages = [
        'newPassword.required' => 'Password is required.',
        'newPassword.min' => 'Password must be at least 8 characters.',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        try {
            $this->authorize('viewAny', User::class);
        } catch (\Exception $e) {
            session()->flash('error', 'You do not have permission to view users.');
            return redirect()->route('dashboard');
        }
    }

    // Livewire updaters
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedVerificationFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedUsers = $this->users->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers()
    {
        if (count($this->selectedUsers) === 0) {
            $this->selectAll = false;
        } elseif (count($this->selectedUsers) === $this->users->count()) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    // Real-time updates
    public function handleUserCreated($data = null)
    {
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    public function handleUserUpdated($data = null)
    {
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    public function handleUserDeleted($data = null)
    {
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    // Navigation methods
    public function createUser()
    {
        return $this->redirect(route('administrator.users.create'), navigate: true);
    }

    public function viewUser($userId)
    {
        return $this->redirect(route('administrator.users.show', $userId), navigate: true);
    }

    public function editUser($userId)
    {
        return $this->redirect(route('administrator.users.edit', $userId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($userId)
    {
        Log::info('confirmDelete called with userId: ' . $userId);

        try {
            $user = User::findOrFail($userId);
            Log::info('User found: ' . $user->name);

            // Basic validation checks
            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'Super Admin users cannot be deleted.');
                return;
            }

            if ($user->id === auth()->id()) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }

            // Check authorization
            if (!auth()->user()->can('delete', $user)) {
                session()->flash('error', 'You do not have permission to delete this user.');
                return;
            }

            // Set the properties for the modal
            $this->userToDelete = $userId;
            $this->userToDeleteName = $user->name;
            $this->confirmingUserDeletion = true;

            Log::info('Modal should open now', [
                'userToDelete' => $this->userToDelete,
                'userToDeleteName' => $this->userToDeleteName,
                'confirmingUserDeletion' => $this->confirmingUserDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->userToDelete) {
            session()->flash('error', 'No user selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $user = User::findOrFail($this->userToDelete);
            $userName = $user->name;

            Log::info('Deleting user: ' . $userName);

            // Final validation
            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'Super Admin users cannot be deleted.');
                $this->cancelDelete();
                return;
            }

            if ($user->id === auth()->id()) {
                session()->flash('error', 'You cannot delete your own account.');
                $this->cancelDelete();
                return;
            }

            $this->authorize('delete', $user);

            // Delete the user
            DB::transaction(function () use ($user, $userName) {
                // Remove all roles
                $user->roles()->detach();
                
                // Log the deletion
                Log::info('User deletion', [
                    'deleted_user_id' => $user->id,
                    'deleted_user_name' => $userName,
                    'deleted_user_email' => $user->email,
                    'deleted_by' => auth()->user()->id,
                    'deleted_by_name' => auth()->user()->name,
                ]);
                
                // Soft delete or hard delete based on your preference
                $user->delete();
            });

            Log::info('User deleted successfully: ' . $userName);

            session()->flash('success', "User '{$userName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete user: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
        $this->userToDeleteName = '';
    }

    // Bulk actions
    public function confirmBulkAction()
    {
        if (empty($this->selectedUsers)) {
            session()->flash('error', 'Please select users first.');
            return;
        }

        if (empty($this->bulkAction)) {
            session()->flash('error', 'Please select an action.');
            return;
        }

        $this->bulkActionType = $this->bulkAction;
        $userCount = count($this->selectedUsers);

        switch ($this->bulkAction) {
            case 'activate':
                $this->bulkActionMessage = "Are you sure you want to activate {$userCount} selected user(s)?";
                break;
            case 'deactivate':
                $this->bulkActionMessage = "Are you sure you want to deactivate {$userCount} selected user(s)?";
                break;
            case 'verify_email':
                $this->bulkActionMessage = "Are you sure you want to verify the email addresses of {$userCount} selected user(s)?";
                break;
            case 'delete':
                $this->bulkActionMessage = "Are you sure you want to delete {$userCount} selected user(s)? This action cannot be undone.";
                break;
            default:
                session()->flash('error', 'Invalid action selected.');
                return;
        }

        $this->confirmingBulkAction = true;
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedUsers) || empty($this->bulkActionType)) {
            $this->cancelBulkAction();
            return;
        }

        try {
            switch ($this->bulkActionType) {
                case 'activate':
                    $this->bulkActivate();
                    break;
                case 'deactivate':
                    $this->bulkDeactivate();
                    break;
                case 'verify_email':
                    $this->bulkVerifyEmail();
                    break;
                case 'delete':
                    $this->bulkDelete();
                    break;
                default:
                    session()->flash('error', 'Invalid action.');
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error executing bulk action', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to execute bulk action: ' . $e->getMessage());
        } finally {
            $this->cancelBulkAction();
        }
    }

    public function cancelBulkAction()
    {
        $this->confirmingBulkAction = false;
        $this->bulkActionType = '';
        $this->bulkActionMessage = '';
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    private function bulkActivate()
    {
        try {
            $count = User::whereIn('id', $this->selectedUsers)
                ->where('id', '!=', auth()->id())
                ->update(['status' => 'active']);
            
            session()->flash('success', "Activated {$count} user(s) successfully.");
            
            Log::info('Bulk user activation', [
                'activated_count' => $count,
                'user_ids' => $this->selectedUsers,
                'activated_by' => auth()->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk activate', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to activate users: ' . $e->getMessage());
        }
    }

    private function bulkDeactivate()
    {
        try {
            $count = User::whereIn('id', $this->selectedUsers)
                ->where('id', '!=', auth()->id())
                ->whereDoesntHave('roles', function($query) {
                    $query->where('name', 'Super Admin');
                })
                ->update(['status' => 'inactive']);
            
            session()->flash('success', "Deactivated {$count} user(s) successfully.");
            
            Log::info('Bulk user deactivation', [
                'deactivated_count' => $count,
                'user_ids' => $this->selectedUsers,
                'deactivated_by' => auth()->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk deactivate', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to deactivate users: ' . $e->getMessage());
        }
    }

    private function bulkVerifyEmail()
    {
        try {
            $count = User::whereIn('id', $this->selectedUsers)
                ->whereNull('email_verified_at')
                ->update(['email_verified_at' => now()]);
            
            session()->flash('success', "Verified {$count} user email(s) successfully.");
            
            Log::info('Bulk email verification', [
                'verified_count' => $count,
                'user_ids' => $this->selectedUsers,
                'verified_by' => auth()->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk verify email', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to verify emails: ' . $e->getMessage());
        }
    }

    private function bulkDelete()
    {
        try {
            DB::transaction(function () {
                $users = User::whereIn('id', $this->selectedUsers)
                    ->where('id', '!=', auth()->id())
                    ->whereDoesntHave('roles', function($query) {
                        $query->where('name', 'Super Admin');
                    })
                    ->get();

                $deletedCount = 0;
                $deletedUsers = [];

                foreach ($users as $user) {
                    if ($this->canUserDeleteUser($user)) {
                        $deletedUsers[] = [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                        
                        $user->roles()->detach();
                        $user->delete();
                        $deletedCount++;
                    }
                }

                Log::info('Bulk user deletion', [
                    'deleted_count' => $deletedCount,
                    'deleted_users' => $deletedUsers,
                    'deleted_by' => auth()->user()->id,
                ]);

                session()->flash('success', "Deleted {$deletedCount} user(s) successfully.");
            });
        } catch (\Exception $e) {
            Log::error('Error in bulk delete', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to delete users: ' . $e->getMessage());
        }
    }

    // Helper method to check if user can delete user
    public function canUserDeleteUser($user)
    {
        try {
            // Super Admin users can only be deleted by Super Admin
            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                return false;
            }

            // User cannot delete their own account
            if ($user->id === auth()->id()) {
                return false;
            }

            // Check if user has permission
            return auth()->user()->can('delete', $user);
        } catch (\Exception $e) {
            Log::error('Error checking delete permission', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    // Status management
    public function toggleUserStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            if ($user->id === auth()->id()) {
                session()->flash('error', 'You cannot change your own status.');
                return;
            }

            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You cannot change Super Admin status.');
                return;
            }

            $this->authorize('update', $user);

            $oldStatus = $user->status ?? 'active';
            $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';
            
            $user->update(['status' => $newStatus]);

            Log::info('User status changed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->user()->id,
            ]);

            session()->flash('success', "User status changed to {$newStatus}.");
        } catch (\Exception $e) {
            Log::error('Error toggling user status', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    public function toggleEmailVerification($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            $this->authorize('update', $user);

            if ($user->email_verified_at) {
                $user->update(['email_verified_at' => null]);
                $message = 'Email verification removed.';
                $action = 'removed';
            } else {
                $user->update(['email_verified_at' => now()]);
                $message = 'Email verified successfully.';
                $action = 'verified';
            }

            Log::info('Email verification toggled', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'action' => $action,
                'changed_by' => auth()->user()->id,
            ]);

            session()->flash('success', $message);
        } catch (\Exception $e) {
            Log::error('Error toggling email verification', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to update email verification: ' . $e->getMessage());
        }
    }

    // Password reset functionality
    public function confirmPasswordReset($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You cannot reset Super Admin password.');
                return;
            }

            $this->authorize('update', $user);

            $this->userToResetPassword = $userId;
            $this->newPassword = '';
            $this->confirmingPasswordReset = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to initiate password reset: ' . $e->getMessage());
        }
    }

    public function resetPassword()
    {
        $this->validate();

        try {
            $user = User::findOrFail($this->userToResetPassword);
            
            $this->authorize('update', $user);

            $user->update([
                'password' => Hash::make($this->newPassword),
                'password_updated_at' => now(),
            ]);

            Log::info('Password reset by admin', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'reset_by' => auth()->user()->id,
            ]);

            session()->flash('success', "Password has been reset for {$user->name}.");
            $this->cancelPasswordReset();
        } catch (\Exception $e) {
            Log::error('Error resetting password', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    public function cancelPasswordReset()
    {
        $this->confirmingPasswordReset = false;
        $this->userToResetPassword = null;
        $this->newPassword = '';
        $this->showNewPassword = false;
        $this->resetValidation();
    }

    public function toggleNewPasswordVisibility()
    {
        $this->showNewPassword = !$this->showNewPassword;
    }

    public function generateRandomPassword()
    {
        $this->newPassword = $this->generatePassword();
    }

    private function generatePassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($characters), 0, $length);
    }

    // Clear filters
    public function clearFilters()
    {
        $this->search = '';
        $this->roleFilter = '';
        $this->statusFilter = '';
        $this->verificationFilter = '';
        $this->resetPage();
    }

    // Export functionality
    public function exportUsers()
    {
        try {
            // This would typically use Laravel Excel or similar
            $users = $this->getUsersForExport();
            
            // For now, just show a success message
            session()->flash('success', 'Export functionality would be implemented here.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to export users: ' . $e->getMessage());
        }
    }

    private function getUsersForExport()
    {
        $query = User::with(['roles']);

        // Apply same filters as the main query
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->whereHas('roles', function($q) {
                $q->where('id', $this->roleFilter);
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->verificationFilter === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($this->verificationFilter === 'unverified') {
            $query->whereNull('email_verified_at');
        }

        return $query->orderBy($this->sortField, $this->sortDirection)->get();
    }

    // Data fetching computed properties
    public function getUsersProperty()
    {
        try {
            $query = User::with(['roles']);

            // Apply search
            if ($this->search) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            }

            // Apply role filter
            if ($this->roleFilter) {
                $query->whereHas('roles', function($q) {
                    $q->where('id', $this->roleFilter);
                });
            }

            // Apply status filter
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            // Apply verification filter
            if ($this->verificationFilter === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($this->verificationFilter === 'unverified') {
                $query->whereNull('email_verified_at');
            }

            // Apply sorting
            $query->orderBy($this->sortField, $this->sortDirection);

            return $query->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading users', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading users. Please try again.');
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 0, $this->perPage, 1, [
                    'path' => request()->url(),
                    'pageName' => 'page'
                ]
            );
        }
    }

    public function getAvailableRolesProperty()
    {
        try {
            return Role::orderBy('name')->get();
        } catch (\Exception $e) {
            Log::error('Error loading roles', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    public function getFilterSummaryProperty()
    {
        $filters = [];
        
        if ($this->search) {
            $filters[] = "Search: {$this->search}";
        }
        
        if ($this->roleFilter) {
            try {
                $role = Role::find($this->roleFilter);
                if ($role) {
                    $filters[] = "Role: {$role->name}";
                }
            } catch (\Exception $e) {
                // Role not found, skip
            }
        }
        
        if ($this->statusFilter) {
            $filters[] = "Status: " . ucfirst($this->statusFilter);
        }
        
        if ($this->verificationFilter) {
            $filters[] = "Email: " . ucfirst($this->verificationFilter);
        }

        return $filters;
    }

    public function getSelectedUsersCountProperty()
    {
        return count($this->selectedUsers);
    }

    public function getTotalUsersCountProperty()
    {
        try {
            return User::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function render()
    {
        try {
            return view('livewire.sakon.users.list-user', [
                'users' => $this->users,
            ]);
        } catch (\Exception $e) {
            Log::error('Error rendering ListUser component', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading page. Please refresh and try again.');
            
            return view('livewire.sakon.users.list-user', [
                'users' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]), 0, $this->perPage, 1, [
                        'path' => request()->url(),
                        'pageName' => 'page'
                    ]
                ),
            ]);
        }
    }
}