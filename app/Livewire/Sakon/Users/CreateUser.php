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
#[Title('Create User')]
class CreateUser extends Component
{
    use AuthorizesRequests;

    // Form properties with validation rules
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|max:255|unique:users,email')]
    public $email = '';

    #[Rule('required|string|min:8')]
    public $password = '';

    #[Rule('required|string|same:password')]
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

    #[Rule('nullable|boolean')]
    public $send_welcome_email = true;

    // Validation rules method for dynamic validation
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|same:password',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
            'status' => 'required|in:active,inactive',
            'user_type' => 'required|in:staff,public',
            'email_verified' => 'nullable|boolean',
            'send_welcome_email' => 'nullable|boolean',
        ];

        // Department is required only for staff users
        if ($this->user_type === 'staff') {
            $rules['department_id'] = 'required|exists:departments,id';
        } else {
            $rules['department_id'] = 'nullable|exists:departments,id';
        }

        return $rules;
    }

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $showPassword = false;
    public $generatePassword = false;

    // Real-time validation messages
    protected $messages = [
        'name.required' => 'Full name is required.',
        'name.max' => 'Name cannot exceed 255 characters.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email address is already registered.',
        'password.required' => 'Password is required.',
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
    public function mount()
    {
        $this->authorize('create', User::class);
    }

    public function hydrate()
    {
        $this->authorize('create', User::class);
    }

    // Real-time validation on property updates
    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
    }

    public function updatedPassword()
    {
        $this->validateOnly('password');
    }

    public function updatedPasswordConfirmation()
    {
        $this->validateOnly('password_confirmation');
    }

    public function updatedPhone()
    {
        $this->validateOnly('phone');
    }

    public function updatedAddress()
    {
        $this->validateOnly('address');
    }

    public function updatedSelectedRoles()
    {
        $this->validateOnly('selectedRoles');
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
    }

    public function updatedDepartmentId()
    {
        $this->validateOnly('department_id');
    }

    public function updatedGeneratePassword()
    {
        if ($this->generatePassword) {
            $this->password = $this->generateRandomPassword();
            $this->password_confirmation = $this->password;
        } else {
            $this->password = '';
            $this->password_confirmation = '';
        }
    }

    // Helper methods
    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($characters), 0, $length);
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    // Quick presets for common user types
    public function applyPreset($preset)
    {
        switch ($preset) {
            case 'admin':
                $this->selectedRoles = Role::where('name', 'like', '%admin%')
                    ->where('name', '!=', 'Super Admin')
                    ->pluck('id')->toArray();
                $this->user_type = 'staff';
                break;

            case 'manager':
                $this->selectedRoles = Role::whereIn('name', ['Manager', 'Content Manager', 'Team Lead'])
                    ->pluck('id')->toArray();
                $this->user_type = 'staff';
                break;

            case 'editor':
                $this->selectedRoles = Role::whereIn('name', ['Editor', 'Content Editor'])
                    ->pluck('id')->toArray();
                $this->user_type = 'staff';
                break;

            case 'staff':
                $this->selectedRoles = Role::where('name', 'Staff')
                    ->pluck('id')->toArray();
                $this->user_type = 'staff';
                break;

            case 'user':
                $this->selectedRoles = Role::where('name', 'User')
                    ->pluck('id')->toArray();
                $this->user_type = 'public';
                $this->department_id = null;
                break;

            default:
                $this->selectedRoles = [];
                break;
        }
    }

    // Main create method
    public function create()
    {
        try {
            $this->authorize('create', User::class);
        } catch (\Exception $e) {
            session()->flash('error', 'You do not have permission to create users.');
            return;
        }

        $this->isProcessing = true;

        try {
            // Debug: Log form data before validation
            \Log::info('CreateUser: Starting validation', [
                'name' => $this->name,
                'email' => $this->email,
                'user_type' => $this->user_type,
                'department_id' => $this->department_id,
                'password_length' => strlen($this->password),
                'password_confirmation_length' => strlen($this->password_confirmation),
                'roles_count' => count($this->selectedRoles),
                'status' => $this->status,
            ]);

            // Validate the form
            $this->validate();

            \Log::info('CreateUser: Validation passed, starting database transaction');

            DB::transaction(function () {
                // Create the user
                $userData = [
                    'name' => trim($this->name),
                    'email' => strtolower(trim($this->email)),
                    'password' => Hash::make($this->password),
                    'phone' => $this->phone ?: null,
                    'address' => $this->address ?: null,
                    'status' => $this->status,
                    'user_type' => $this->user_type,
                    'email_verified_at' => $this->email_verified ? now() : null,
                ];

                // Add department_id only if provided and user is staff
                if ($this->department_id && $this->user_type === 'staff') {
                    $userData['department_id'] = $this->department_id;
                }

                $user = User::create($userData);

                \Log::info('CreateUser: User created with ID: ' . $user->id);

                // Assign roles
                if (!empty($this->selectedRoles)) {
                    $roles = Role::whereIn('id', $this->selectedRoles)->get();
                    \Log::info('CreateUser: Found roles: ' . $roles->pluck('name')->implode(', '));
                    $user->syncRoles($roles);
                }

                // Send welcome email if requested
                if ($this->send_welcome_email) {
                    // Dispatch welcome email job
                    // dispatch(new SendWelcomeEmail($user, $this->generatePassword ? $this->password : null));
                }

                // Log activity
                \Log::info('User created successfully', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_type' => $user->user_type,
                    'department_id' => $user->department_id,
                    'roles_count' => count($this->selectedRoles),
                    'created_by' => auth()->user()->id,
                    'created_by_name' => auth()->user()->name,
                ]);
            });

            // Success feedback
            $this->showSuccessMessage = true;
            $departmentText = $this->department_id ? ' in department' : '';
            $this->successMessage = "User '{$this->name}' has been created successfully as {$this->user_type}{$departmentText} with " . count($this->selectedRoles) . " role(s).";

            // Store success message in session for the redirect
            session()->flash('success', "User '{$this->name}' has been created successfully.");

            // Dispatch event for real-time updates
            $this->dispatch('userCreated', [
                'name' => $this->name,
                'email' => $this->email,
                'user_type' => $this->user_type,
                'department_id' => $this->department_id,
                'roles_count' => count($this->selectedRoles)
            ]);

            \Log::info('CreateUser: Success, redirecting to users index');

            // Immediate redirect to users index
            return $this->redirect(route('administrator.users.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('CreateUser: Validation failed', [
                'errors' => $e->errors(),
                'form_data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'user_type' => $this->user_type,
                    'department_id' => $this->department_id,
                    'roles' => $this->selectedRoles,
                ]
            ]);
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            \Log::error('CreateUser: Failed to create user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'form_data' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'user_type' => $this->user_type,
                    'department_id' => $this->department_id,
                    'roles' => $this->selectedRoles,
                ]
            ]);
            session()->flash('error', 'Failed to create user: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->phone = '';
        $this->address = '';
        $this->selectedRoles = [];
        $this->status = 'active';
        $this->user_type = 'public';
        $this->department_id = null;
        $this->email_verified = false;
        $this->send_welcome_email = true;
        $this->showPassword = false;
        $this->generatePassword = false;
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.users.index'), navigate: true);
    }

    // Get computed properties
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

    public function getCanCreateProperty()
    {
        $basicFieldsValid = !$this->isProcessing &&
                           !empty(trim($this->name)) &&
                           !empty(trim($this->email)) &&
                           !empty($this->password) &&
                           !empty($this->password_confirmation) &&
                           count($this->selectedRoles) > 0;

        // Check if department is required for staff users
        if ($this->user_type === 'staff') {
            return $basicFieldsValid && !empty($this->department_id);
        }

        return $basicFieldsValid;
    }

    public function getPasswordStrengthProperty()
    {
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

    // Render component
    public function render()
    {
        return view('livewire.sakon.users.create-user');
    }
}
