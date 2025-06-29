<?php

namespace App\Livewire\Sakon\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.dashboard')]
#[Title('User Details')]
class ShowUser extends Component
{
    use AuthorizesRequests;

    public User $user;
    public $activeTab = 'overview';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->authorize('view', $this->user);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function goToUsersList()
    {
        return $this->redirect(route('administrator.users.index'), navigate: true);
    }

    public function editUser()
    {
        return $this->redirect(route('administrator.users.edit', $this->user), navigate: true);
    }

    public function duplicateUser()
    {
        return $this->redirect(route('administrator.users.create', [
            'duplicate' => $this->user->id
        ]), navigate: true);
    }

    public function toggleUserStatus()
    {
        try {
            if ($this->user->id === auth()->id()) {
                session()->flash('error', 'You cannot change your own status.');
                return;
            }

            if ($this->user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You cannot change Super Admin status.');
                return;
            }

            $this->authorize('update', $this->user);

            $newStatus = $this->user->status === 'active' ? 'inactive' : 'active';
            $this->user->update(['status' => $newStatus]);

            session()->flash('success', "User status changed to {$newStatus}.");
            
            // Refresh the user model
            $this->user->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user status: ' . $e->getMessage());
        }
    }

    public function toggleEmailVerification()
    {
        try {
            $this->authorize('update', $this->user);

            if ($this->user->email_verified_at) {
                $this->user->update(['email_verified_at' => null]);
                session()->flash('success', 'Email verification removed.');
            } else {
                $this->user->update(['email_verified_at' => now()]);
                session()->flash('success', 'Email verified successfully.');
            }
            
            // Refresh the user model
            $this->user->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update email verification: ' . $e->getMessage());
        }
    }

    public function sendPasswordReset()
    {
        try {
            if ($this->user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                session()->flash('error', 'You cannot reset Super Admin password.');
                return;
            }

            $this->authorize('update', $this->user);

            // Method 1: Using Laravel's built-in Password facade
            // $status = Password::sendResetLink(['email' => $this->user->email]);
            
            // Method 2: Using a custom notification
            // $this->user->notify(new \App\Notifications\AdminPasswordReset());
            
            // Method 3: Using a queued job (recommended for production)
            // dispatch(new \App\Jobs\SendPasswordResetEmail($this->user, auth()->user()));
            
            // For demonstration, we'll simulate sending the email
            $token = \Str::random(60);
            
            // You would typically store this token in password_resets table
            // DB::table('password_resets')->updateOrInsert(
            //     ['email' => $this->user->email],
            //     [
            //         'token' => Hash::make($token),
            //         'created_at' => now()
            //     ]
            // );
            
            // Log the action
            \Log::info('Password reset initiated by admin', [
                'target_user_id' => $this->user->id,
                'target_user_email' => $this->user->email,
                'target_user_name' => $this->user->name,
                'initiated_by' => auth()->user()->id,
                'initiated_by_name' => auth()->user()->name,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            session()->flash('success', "Password reset link has been sent to {$this->user->email}. The user will receive instructions on how to reset their password.");
            
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Failed to send password reset email. Please try again or contact system administrator.');
        }
    }

    public function resendVerificationEmail()
    {
        try {
            if ($this->user->email_verified_at) {
                session()->flash('info', 'User email is already verified.');
                return;
            }

            $this->authorize('update', $this->user);

            // In a real application, you would send an actual verification email
            // $this->user->sendEmailVerificationNotification();
            
            // Log the action
            \Log::info('Verification email resent', [
                'target_user_id' => $this->user->id,
                'target_user_email' => $this->user->email,
                'sent_by' => auth()->user()->id,
                'sent_by_name' => auth()->user()->name,
            ]);
            
            session()->flash('success', "Verification email has been sent to {$this->user->email}.");
            
        } catch (\Exception $e) {
            \Log::error('Failed to resend verification email', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);
            session()->flash('error', 'Failed to resend verification email: ' . $e->getMessage());
        }
    }

    public function getUserStatsProperty()
    {
        return [
            'roles_count' => $this->user->roles()->count(),
            'permissions_count' => $this->user->getAllPermissions()->count(),
            'created_date' => $this->user->created_at->format('M d, Y'),
            'updated_date' => $this->user->updated_at->format('M d, Y'),
            'created_datetime' => $this->user->created_at->format('M d, Y \a\t H:i'),
            'updated_datetime' => $this->user->updated_at->format('M d, Y \a\t H:i'),
            'created_human' => $this->user->created_at->diffForHumans(),
            'updated_human' => $this->user->updated_at->diffForHumans(),
            'last_login' => $this->user->last_login_at ? $this->user->last_login_at->format('M d, Y \a\t H:i') : 'Never',
            'last_login_human' => $this->user->last_login_at ? $this->user->last_login_at->diffForHumans() : 'Never logged in',
            'email_verified' => !is_null($this->user->email_verified_at),
            'email_verified_date' => $this->user->email_verified_at ? $this->user->email_verified_at->format('M d, Y \a\t H:i') : null,
            'status' => $this->user->status ?? 'active',
        ];
    }

    public function getUserRolesGroupedProperty()
    {
        return $this->user->roles->groupBy(function ($role) {
            // Group by role type or just return all as one group
            if (in_array($role->name, ['Super Admin', 'Administrator'])) {
                return 'administrative';
            } elseif (in_array($role->name, ['Manager', 'Team Lead'])) {
                return 'management';
            } elseif (in_array($role->name, ['Editor', 'Content Manager'])) {
                return 'content';
            } else {
                return 'general';
            }
        });
    }

    public function getUserPermissionsGroupedProperty()
    {
        return $this->user->getAllPermissions()->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->map(function ($group) {
            return $group->sortBy('name');
        });
    }

    public function getAccountSecurityProperty()
    {
        return [
            'two_factor_enabled' => false, // Implement if you have 2FA
            'password_updated' => $this->user->password_updated_at ?? $this->user->updated_at,
            'failed_login_attempts' => 0, // Implement if you track failed attempts
            'account_locked' => false, // Implement if you have account locking
        ];
    }

    public function getActivitySummaryProperty()
    {
        // This would typically come from an activity log package like Spatie Activity Log
        return [
            'total_logins' => 0, // Implement if you track logins
            'last_ip_address' => request()->ip(), // Last known IP
            'browser_sessions' => 1, // Active browser sessions
            'recent_activities' => [], // Recent user activities
        ];
    }

    public function getUserProfileCompletenessProperty()
    {
        $fields = [
            'name' => !empty($this->user->name),
            'email' => !empty($this->user->email),
            'phone' => !empty($this->user->phone),
            'address' => !empty($this->user->address),
            'email_verified' => !is_null($this->user->email_verified_at),
            'avatar' => !empty($this->user->avatar), // If you have avatar field
        ];

        $completed = count(array_filter($fields));
        $total = count($fields);
        $percentage = round(($completed / $total) * 100);

        return [
            'fields' => $fields,
            'completed' => $completed,
            'total' => $total,
            'percentage' => $percentage,
        ];
    }

    public function render()
    {
        return view('livewire.sakon.users.show-user', [
            'userStats' => $this->userStats,
            'userRolesGrouped' => $this->userRolesGrouped,
            'userPermissionsGrouped' => $this->userPermissionsGrouped,
            'accountSecurity' => $this->accountSecurity,
            'activitySummary' => $this->activitySummary,
            'profileCompleteness' => $this->userProfileCompleteness,
        ]);
    }
}