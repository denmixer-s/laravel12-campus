<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('delete-user');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_ids.required' => 'Please select at least one user to delete.',
            'user_ids.min' => 'Please select at least one user to delete.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $userIds = $this->input('user_ids', []);
            $currentUserId = $this->user()->id;

            // Check if trying to delete current user
            if (in_array($currentUserId, $userIds)) {
                $validator->errors()->add('user_ids', 'You cannot delete your own account.');
            }

            // Check if trying to delete Super Admin users
            $superAdminUsers = User::whereIn('id', $userIds)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Super Admin');
                })
                ->exists();

            if ($superAdminUsers) {
                $validator->errors()->add('user_ids', 'Super Admin users cannot be deleted.');
            }
        });
    }
}