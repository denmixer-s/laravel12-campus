<?php

namespace App\Livewire\Sakon\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

#[Layout('components.layouts.dashboard')]
#[Title('Edit User')]
class EditUser extends Component
{
    use AuthorizesRequests;

    // Route model binding
    public User $user;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';

    public $email = '';

    #[Rule('nullable|string|min:8')]
    public $password = '';

    #[Rule('nullable|string|same:password')]
    public $password_confirmation = '';

    #[Rule('nullable|string|max:20')]
    public $phone = '';

    #[Rule('nullable|string|max:255')]
    public $address = '';

    #[Rule('required|array|min:1')]
    public $selectedRoles = [];

    #[Rule('required|in:active,inactive')]
    public $status = 'active';

    #[Rule('required|in:staff,public')]
    public $user_type = 'public';

    #[Rule('nullable|exists:departments,id')]
    public $department_id = null;

    #[Rule('nullable|boolean')]
    public $email_verified = false;

    // UI state
    public $isProcessing = false;
    public $showPassword = false;
    public $updatePassword = false;
    public $originalData = [];
    public $hasChanges = false;

    // Real-time validation messages
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $this->user->id,
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
            'status' => 'required|in:active,inactive',
            'user_type' => 'required|in:staff,public',
        ];

        // Department is required only for staff users
        if ($this->user_type === 'staff') {
            $rules['department_id'] = 'required|exists:departments,id';
        } else {
            $rules['department_id'] = 'nullable|exists:departments,id';
        }

        if ($this->updatePassword) {
            $rules['password'] = [
                'required',
                'string',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ];
            $rules['password_confirmation'] = 'required|string|same:password';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Full name is required.',
        'name.max' => 'Name cannot exceed 255 characters.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already taken.',
        'password.required' => 'Password is required when updating password.',
        'password.min' => 'Password must be at least 8 characters.',
        'password_confirmation.required' => 'Password confirmation is required.',
        'password_confirmation.same' => 'Password confirmation does not match.',
        'selectedRoles.required' => 'Please select at least one role.',
        'selectedRoles.min' => 'Please select at least one role.',
        'status.required' => 'Please select user status.',
        'user_type.required' => 'Please select user type.',
        'department_id.required' => 'Department is required for staff users.',
        'department_id.exists' => 'Selected department is invalid.',
        'phone.max' => 'Phone number cannot exceed 20 characters.',
        'address.max' => 'Address cannot exceed 255 characters.',
    ];

    // Lifecycle hooks
    public function mount(User $user)
    {
        $this->user = $user;

        // Prevent editing Super Admin user
        if ($this->user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You cannot edit Super Admin users.');
            return $this->redirect(route('administrator.users.index'), navigate: true);
        }

        $this->authorize('update', $this->user);

        // Load user data
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone ?? '';
        $this->address = $this->user->address ?? '';
        $this->selectedRoles = $this->user->roles->pluck('id')->toArray();
        $this->status = $this->user->status ?? 'active';
        $this->user_type = $this->user->user_type ?? 'public';
        $this->department_id = $this->user->department_id;
        $this->email_verified = !is_null($this->user->email_verified_at);

        // Store original data for change detection
        $this->originalData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'selectedRoles' => $this->selectedRoles,
            'status' => $this->status,
            'user_type' => $this->user_type,
            'department_id' => $this->department_id,
            'email_verified' => $this->email_verified,
        ];
    }

    public function hydrate()
    {
        $this->authorize('update', $this->user);
    }

    // Real-time validation and change detection
    public function updatedName()
    {
        $this->validateOnly('name');
        $this->checkForChanges();
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
        $this->checkForChanges();
    }

    public function updatedPassword()
    {
        if ($this->updatePassword) {
            $this->validateOnly('password');
        }
    }

    public function updatedPasswordConfirmation()
    {
        if ($this->updatePassword) {
            $this->validateOnly('password_confirmation');
        }
    }

    public function updatedPhone()
    {
        $this->validateOnly('phone');
        $this->checkForChanges();
    }

    public function updatedAddress()
    {
        $this->validateOnly('address');
        $this->checkForChanges();
    }

    public function updatedSelectedRoles()
    {
        $this->validateOnly('selectedRoles');
        $this->checkForChanges();
    }

    public function updatedStatus()
    {
        $this->validateOnly('status');
        $this->checkForChanges();
    }

    public function updatedUserType()
    {
        $this->validateOnly('user_type');

        // Clear department when switching to public user
        if ($this->user_type === 'public') {
            $this->department_id = null;
        }

        // Revalidate department_id with new rules
        $this->validateOnly('department_id');
        $this->checkForChanges();
    }

    public function updatedDepartmentId()
    {
        $this->validateOnly('department_id');
        $this->checkForChanges();
    }

    public function updatedEmailVerified()
    {
        $this->checkForChanges();
    }

    public function updatedUpdatePassword()
    {
        if (!$this->updatePassword) {
            $this->password = '';
            $this->password_confirmation = '';
            $this->resetValidation(['password', 'password_confirmation']);
        }
        $this->checkForChanges();
    }

    // Helper methods
    private function checkForChanges()
    {
        $this->hasChanges = (
            $this->name !== $this->originalData['name'] ||
            $this->email !== $this->originalData['email'] ||
            $this->phone !== $this->originalData['phone'] ||
            $this->address !== $this->originalData['address'] ||
            $this->status !== $this->originalData['status'] ||
            $this->user_type !== $this->originalData['user_type'] ||
            $this->department_id !== $this->originalData['department_id'] ||
            $this->email_verified !== $this->originalData['email_verified'] ||
            array_diff($this->selectedRoles, $this->originalData['selectedRoles']) ||
            array_diff($this->originalData['selectedRoles'], $this->selectedRoles) ||
            $this->updatePassword
        );
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    // Main update method
    public function update()
    {
        $this->authorize('update', $this->user);

        // Check if Super Admin user is being edited
        if ($this->user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            session()->flash('error', 'You cannot edit Super Admin users.');
            return;
        }

        $this->isProcessing = true;

        try {
            $this->validate();

            DB::transaction(function () {
                $updateData = [
                    'name' => trim($this->name),
                    'email' => strtolower(trim($this->email)),
                    'phone' => $this->phone ?: null,
                    'address' => $this->address ?: null,
                    'status' => $this->status,
                    'user_type' => $this->user_type,
                ];

                // Add department_id only if provided and user is staff
                if ($this->user_type === 'staff' && $this->department_id) {
                    $updateData['department_id'] = $this->department_id;
                } elseif ($this->user_type === 'public') {
                    $updateData['department_id'] = null;
                }

                // Update password if requested
                if ($this->updatePassword && !empty($this->password)) {
                    $updateData['password'] = Hash::make($this->password);
                }

                // Update email verification
                if ($this->email_verified && is_null($this->user->email_verified_at)) {
                    $updateData['email_verified_at'] = now();
                } elseif (!$this->email_verified && !is_null($this->user->email_verified_at)) {
                    $updateData['email_verified_at'] = null;
                }

                // Update the user
                $this->user->update($updateData);

                // Sync roles
                $roles = Role::whereIn('id', $this->selectedRoles)->get();
                $this->user->syncRoles($roles);

                // Log activity
                \Log::info('User updated', [
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'user_email' => $this->user->email,
                    'user_type' => $this->user->user_type,
                    'department_id' => $this->user->department_id,
                    'roles_count' => count($this->selectedRoles),
                    'updated_by' => auth()->user()->id,
                    'updated_by_name' => auth()->user()->name,
                    'password_updated' => $this->updatePassword,
                    'changes' => [
                        'name_changed' => $this->name !== $this->originalData['name'],
                        'email_changed' => $this->email !== $this->originalData['email'],
                        'user_type_changed' => $this->user_type !== $this->originalData['user_type'],
                        'department_changed' => $this->department_id !== $this->originalData['department_id'],
                        'roles_changed' => count(array_diff($this->selectedRoles, $this->originalData['selectedRoles'])) > 0 ||
                                         count(array_diff($this->originalData['selectedRoles'], $this->selectedRoles)) > 0,
                    ],
                ]);
            });

            // Update original data
            $this->originalData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'selectedRoles' => $this->selectedRoles,
                'status' => $this->status,
                'user_type' => $this->user_type,
                'department_id' => $this->department_id,
                'email_verified' => $this->email_verified,
            ];
            $this->hasChanges = false;
            $this->updatePassword = false;
            $this->password = '';
            $this->password_confirmation = '';

            // Success feedback with session flash for redirect
            session()->flash('success', "User '{$this->name}' has been updated successfully.");

            // Dispatch event for real-time updates
            $this->dispatch('userUpdated', [
                'id' => $this->user->id,
                'name' => $this->name,
                'email' => $this->email,
                'user_type' => $this->user_type,
                'department_id' => $this->department_id,
                'roles_count' => count($this->selectedRoles)
            ]);

            // Redirect to users index
            return $this->redirect(route('administrator.users.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update user: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = $this->originalData['name'];
        $this->email = $this->originalData['email'];
        $this->phone = $this->originalData['phone'];
        $this->address = $this->originalData['address'];
        $this->selectedRoles = $this->originalData['selectedRoles'];
        $this->status = $this->originalData['status'];
        $this->user_type = $this->originalData['user_type'];
        $this->department_id = $this->originalData['department_id'];
        $this->email_verified = $this->originalData['email_verified'];
        $this->hasChanges = false;
        $this->updatePassword = false;
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.users.index'), navigate: true);
    }

    // Computed properties
    public function getSelectedRolesCountProperty()
    {
        return count($this->selectedRoles);
    }

    public function getAvailableRolesProperty()
    {
        return Role::orderBy('name')->get();
    }

    public function getAvailableDepartmentsProperty()
    {
        // Get departments based on current user's access level
        $currentUser = auth()->user();

        if ($currentUser->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin'])) {
            return Department::with(['division.faculty.university'])
                ->active()
                ->orderBy('name')
                ->get();
        }

        // For other users, return departments they can access
        return $currentUser->getAccessibleDepartments();
    }

    public function getCanUpdateProperty()
    {
        $basicFieldsValid = !$this->isProcessing &&
                           !empty(trim($this->name)) &&
                           !empty(trim($this->email)) &&
                           count($this->selectedRoles) > 0 &&
                           ($this->hasChanges || $this->updatePassword) &&
                           (!$this->updatePassword || (!empty($this->password) && !empty($this->password_confirmation)));

        // Check if department is required for staff users
        if ($this->user_type === 'staff') {
            return $basicFieldsValid && !empty($this->department_id);
        }

        return $basicFieldsValid;
    }

    public function getAddedRolesProperty()
    {
        return array_diff($this->selectedRoles, $this->originalData['selectedRoles']);
    }

    public function getRemovedRolesProperty()
    {
        return array_diff($this->originalData['selectedRoles'], $this->selectedRoles);
    }

    public function getPasswordStrengthProperty()
    {
        if (!$this->updatePassword || empty($this->password)) {
            return null;
        }

        $score = 0;
        $password = $this->password;

        if (strlen($password) >= 8) $score++;
        if (preg_match('/[a-z]/', $password)) $score++;
        if (preg_match('/[A-Z]/', $password)) $score++;
        if (preg_match('/[0-9]/', $password)) $score++;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score++;

        return [
            'score' => $score,
            'label' => match($score) {
                0, 1 => 'Very Weak',
                2 => 'Weak',
                3 => 'Fair',
                4 => 'Strong',
                5 => 'Very Strong',
                default => 'Unknown'
            },
            'color' => match($score) {
                0, 1 => 'red',
                2 => 'orange',
                3 => 'yellow',
                4 => 'blue',
                5 => 'green',
                default => 'gray'
            }
        ];
    }

    public function getSelectedDepartmentProperty()
    {
        if (!$this->department_id) {
            return null;
        }

        return Department::with(['division.faculty.university'])
            ->find($this->department_id);
    }

    // Quick actions
    public function duplicateUser()
    {
        return $this->redirect(route('administrator.users.create', [
            'duplicate' => $this->user->id
        ]), navigate: true);
    }

    // Render component
    public function render()
    {
        return view('livewire.sakon.users.edit-user');
    }
}
