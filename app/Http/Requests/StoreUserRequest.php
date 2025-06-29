
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create-user');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
            'send_welcome_email' => ['boolean'],
            'require_email_verification' => ['boolean'],
        ];
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
            'send_welcome_email' => 'welcome email option',
            'require_email_verification' => 'email verification requirement',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if trying to assign Super Admin role
            if (in_array('Super Admin', $this->input('roles', []))) {
                if (!$this->user()->hasRole('Super Admin')) {
                    $validator->errors()->add('roles', 'You cannot assign the Super Admin role.');
                }
            }
        });
    }
}