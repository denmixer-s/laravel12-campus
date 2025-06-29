<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        
        // Users can update their own profile
        if ($this->user()->id === $user->id) {
            return true;
        }

        // Check if user has permission to edit users
        return $this->user()->can('edit-user');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = $this->route('user');
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'email_verified' => ['boolean'],
        ];

        // Add password rules if password is being changed
        if ($this->filled('password')) {
            $rules['password'] = ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'roles.required' => 'Please select at least one role for the user.',
            'roles.min' => 'Please select at least one role for the user.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'roles' => 'user roles',
            'email_verified' => 'email verification status',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->route('user');
            $currentUser = $this->user();

            // Super Admin protection checks
            if ($user->hasRole('Super Admin')) {
                // Only Super Admin can edit Super Admin users
                if (!$currentUser->hasRole('Super Admin')) {
                    $validator->errors()->add('general', 'You cannot edit Super Admin users.');
                    return;
                }

                // Super Admin can only edit their own profile (unless editing as another Super Admin)
                if ($user->id !== $currentUser->id && !$currentUser->hasRole('Super Admin')) {
                    $validator->errors()->add('general', 'Super Admin users can only edit their own profile.');
                    return;
                }
            }

            // Check if trying to assign Super Admin role
            if (in_array('Super Admin', $this->input('roles', []))) {
                if (!$currentUser->hasRole('Super Admin')) {
                    $validator->errors()->add('roles', 'You cannot assign the Super Admin role.');
                }
            }

            // Prevent removing Super Admin role from the last Super Admin
            if ($user->hasRole('Super Admin') && !in_array('Super Admin', $this->input('roles', []))) {
                $superAdminCount = User::role('Super Admin')->count();
                if ($superAdminCount <= 1) {
                    $validator->errors()->add('roles', 'Cannot remove Super Admin role from the last Super Admin user.');
                }
            }
        });
    }
}
