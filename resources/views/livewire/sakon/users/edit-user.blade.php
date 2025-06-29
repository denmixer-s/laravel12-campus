<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Edit User</h1>
                        </div>
                        <p class="text-slate-600">Modify user information and settings for <span
                                class="font-medium text-slate-800">"{{ $user->name }}"</span></p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="cancel" type="button"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>

                        <button wire:click="duplicateUser" type="button"
                            class="inline-flex items-center px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>

                <!-- User Info Bar -->
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 112 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 112-2V6" />
                            </svg>
                            <span class="font-medium">{{ ucfirst($user->user_type ?? 'public') }}</span>
                            @if (($user->user_type ?? 'public') === 'staff')
                                <span class="text-blue-600">(บุคลากร)</span>
                            @else
                                <span class="text-green-600">(บุคคลทั่วไป)</span>
                            @endif
                        </div>
                        @if ($user->department)
                            <div class="flex items-center text-slate-600">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="font-medium">{{ $user->department->name }}</span>
                            </div>
                        @endif
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="font-medium">{{ count($selectedRoles) }}</span> roles
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Joined: {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Changes Alert -->
        @if ($hasChanges)
            <div class="mb-6">
                <div class="bg-amber-50 rounded-lg border border-amber-200 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-amber-800 font-medium">You have unsaved changes</p>
                            <p class="text-amber-700 text-sm mt-1">Don't forget to save your changes before leaving this
                                page.</p>
                        </div>
                        <button wire:click="resetForm" type="button"
                            class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                            Reset Changes
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Personal Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.live.debounce.300ms="name" type="text" id="name"
                                    placeholder="Enter full name"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.live.debounce.300ms="email" type="email" id="email"
                                    placeholder="Enter email address"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                    Phone Number
                                </label>
                                <input wire:model.live.debounce.300ms="phone" type="tel" id="phone"
                                    placeholder="Enter phone number"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('phone') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="status" id="status"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('status') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                                    Address
                                </label>
                                <textarea wire:model.live.debounce.300ms="address" id="address" rows="3" placeholder="Enter address"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('address') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Organization Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Organization Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Type -->
                            <div>
                                <label for="user_type" class="block text-sm font-medium text-slate-700 mb-2">
                                    User Type <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="user_type" id="user_type"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('user_type') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select user type</option>
                                    <option value="staff">Staff (บุคลากร)</option>
                                    <option value="public">Public (บุคคลทั่วไป)</option>
                                </select>
                                @error('user_type')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Department
                                    @if ($user_type === 'staff')
                                        <span class="text-red-500">*</span>
                                    @endif
                                    @if ($user_type === 'public')
                                        <span class="text-slate-500">(Optional)</span>
                                    @endif
                                </label>
                                <select wire:model.live="department_id" id="department_id"
                                    {{ $user_type === 'public' ? '' : 'required' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('department_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select department</option>
                                    @foreach ($this->availableDepartments as $department)
                                        <option value="{{ $department->id }}">
                                            {{ $department->name }}
                                            @if ($department->division)
                                                - {{ $department->division->name }}
                                                @if ($department->division->faculty)
                                                    - {{ $department->division->faculty->name }}
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror

                                <!-- Department hierarchy info -->
                                @if ($this->selectedDepartment)
                                    <div class="mt-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="text-sm text-blue-800">
                                            <div class="font-medium mb-1">Organization Hierarchy:</div>
                                            <div class="text-xs text-blue-600">
                                                @if ($this->selectedDepartment->division?->faculty?->university)
                                                    {{ $this->selectedDepartment->division->faculty->university->name }}
                                                    →
                                                @endif
                                                @if ($this->selectedDepartment->division?->faculty)
                                                    {{ $this->selectedDepartment->division->faculty->name }} →
                                                @endif
                                                @if ($this->selectedDepartment->division)
                                                    {{ $this->selectedDepartment->division->name }} →
                                                @endif
                                                {{ $this->selectedDepartment->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Type Info -->
                        <div class="mt-4 p-4 bg-slate-50 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-slate-500 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="text-sm text-slate-600">
                                    @if ($user_type === 'staff')
                                        <strong>Staff Users:</strong> Internal organization members who require
                                        department assignment and typically have elevated permissions for system
                                        management.
                                    @elseif($user_type === 'public')
                                        <strong>Public Users:</strong> External users who can access public features.
                                        Department assignment is optional.
                                    @else
                                        <strong>Please select a user type</strong> to see more information about the
                                        requirements and permissions.
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Original User Type Info -->
                        @if ($user_type !== $originalData['user_type'])
                            <div class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-200">
                                <div class="flex items-center text-amber-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <div class="text-sm">
                                        <strong>User type changed:</strong> From
                                        <span class="font-medium">{{ ucfirst($originalData['user_type']) }}</span>
                                        to
                                        <span class="font-medium">{{ ucfirst($user_type) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Password Update Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-800">Password Management</h2>
                            <label class="flex items-center cursor-pointer">
                                <input wire:model.live="updatePassword" type="checkbox"
                                    class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                                <span class="ml-3 text-sm font-medium text-slate-700">Update password</span>
                            </label>
                        </div>

                        @if ($updatePassword)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- New Password -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                        New Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input wire:model.live.debounce.300ms="password"
                                            type="{{ $showPassword ? 'text' : 'password' }}" id="password"
                                            placeholder="Enter new password"
                                            class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <button wire:click="togglePasswordVisibility" type="button"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if ($showPassword)
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                @endif
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror

                                    <!-- Password Strength Indicator -->
                                    @if ($updatePassword && $password && $this->passwordStrength)
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-xs mb-1">
                                                <span class="text-slate-600">Password Strength</span>
                                                <span
                                                    class="font-medium text-{{ $this->passwordStrength['color'] }}-600">
                                                    {{ $this->passwordStrength['label'] }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="bg-{{ $this->passwordStrength['color'] }}-500 h-2 rounded-full transition-all duration-300"
                                                    style="width: {{ ($this->passwordStrength['score'] / 5) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-slate-700 mb-2">
                                        Confirm New Password <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live.debounce.300ms="password_confirmation"
                                        type="{{ $showPassword ? 'text' : 'password' }}" id="password_confirmation"
                                        placeholder="Confirm new password"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('password_confirmation') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-500">
                                <svg class="w-12 h-12 mx-auto mb-4 text-slate-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <p>Enable password update to change the user's password</p>
                            </div>
                        @endif
                    </div>

                    <!-- Roles Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-800">Role Assignment</h2>
                            <span class="text-sm text-slate-500">
                                {{ $this->selectedRolesCount }} role(s) selected
                            </span>
                        </div>

                        <!-- Roles Grid -->
                        @error('selectedRoles')
                            <div class="mb-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                <p class="text-red-600 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($this->availableRoles as $role)
                                <label
                                    class="flex items-center cursor-pointer p-3 hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">
                                    <input wire:model.live="selectedRoles" type="checkbox"
                                        value="{{ $role->id }}"
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-slate-900">{{ $role->name }}</span>
                                        @if ($role->name === 'Super Admin')
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                System Role
                                            </span>
                                        @endif
                                        <p class="text-xs text-slate-500">{{ $role->permissions->count() }}
                                            permissions</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Additional Settings Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Additional Settings</h2>

                        <div class="space-y-4">
                            <!-- Email Verification -->
                            <label class="flex items-center cursor-pointer">
                                <input wire:model.live="email_verified" type="checkbox"
                                    class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-slate-700">Email verified</span>
                                    <p class="text-xs text-slate-500">Mark this user's email as verified</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Update Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Update Status</h3>

                        <div class="space-y-4">
                            <!-- Name Change Status -->
                            <div class="flex items-center">
                                @if (
                                    $name !== $originalData['name'] ||
                                        $email !== $originalData['email'] ||
                                        $phone !== $originalData['phone'] ||
                                        $address !== $originalData['address'] ||
                                        $status !== $originalData['status']
                                )
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span
                                    class="text-sm {{ $name !== $originalData['name'] || $email !== $originalData['email'] || $phone !== $originalData['phone'] || $address !== $originalData['address'] || $status !== $originalData['status'] ? 'text-amber-700' : 'text-green-700' }}">
                                    Personal info
                                    {{ $name !== $originalData['name'] || $email !== $originalData['email'] || $phone !== $originalData['phone'] || $address !== $originalData['address'] || $status !== $originalData['status'] ? 'modified' : 'unchanged' }}
                                </span>
                            </div>

                            <!-- Organization Change Status -->
                            <div class="flex items-center">
                                @if ($user_type !== $originalData['user_type'] || $department_id !== $originalData['department_id'])
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span
                                    class="text-sm {{ $user_type !== $originalData['user_type'] || $department_id !== $originalData['department_id'] ? 'text-amber-700' : 'text-green-700' }}">
                                    Organization
                                    {{ $user_type !== $originalData['user_type'] || $department_id !== $originalData['department_id'] ? 'modified' : 'unchanged' }}
                                </span>
                            </div>

                            <!-- Roles Change Status -->
                            <div class="flex items-center">
                                @if (count($this->addedRoles) > 0 || count($this->removedRoles) > 0)
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span
                                    class="text-sm {{ count($this->addedRoles) > 0 || count($this->removedRoles) > 0 ? 'text-amber-700' : 'text-green-700' }}">
                                    Roles
                                    {{ count($this->addedRoles) > 0 || count($this->removedRoles) > 0 ? 'modified' : 'unchanged' }}
                                </span>
                            </div>

                            <!-- Password Status -->
                            <div class="flex items-center">
                                @if ($updatePassword)
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span class="text-sm {{ $updatePassword ? 'text-amber-700' : 'text-green-700' }}">
                                    Password {{ $updatePassword ? 'will be updated' : 'unchanged' }}
                                </span>
                            </div>
                        </div>

                        <!-- Changes Summary -->
                        @if ($hasChanges)
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <h4 class="text-sm font-medium text-slate-800 mb-2">Changes Summary:</h4>
                                <div class="space-y-2 text-sm">
                                    @if (
                                        $name !== $originalData['name'] ||
                                            $email !== $originalData['email'] ||
                                            $phone !== $originalData['phone'] ||
                                            $address !== $originalData['address'] ||
                                            $status !== $originalData['status']
                                    )
                                        <div class="flex items-center text-amber-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Personal info changed
                                        </div>
                                    @endif

                                    @if ($user_type !== $originalData['user_type'])
                                        <div class="flex items-center text-blue-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            User type: {{ ucfirst($originalData['user_type']) }} →
                                            {{ ucfirst($user_type) }}
                                        </div>
                                    @endif

                                    @if ($department_id !== $originalData['department_id'])
                                        <div class="flex items-center text-purple-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Department changed
                                        </div>
                                    @endif

                                    @if (count($this->addedRoles) > 0)
                                        <div class="flex items-center text-green-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            +{{ count($this->addedRoles) }} roles added
                                        </div>
                                    @endif

                                    @if (count($this->removedRoles) > 0)
                                        <div class="flex items-center text-red-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            -{{ count($this->removedRoles) }} roles removed
                                        </div>
                                    @endif

                                    @if ($updatePassword)
                                        <div class="flex items-center text-blue-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Password will be updated
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Current User Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">User Summary</h3>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Current Name:</span>
                                <p class="font-medium text-slate-800">{{ $name ?: 'Not specified' }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Current Email:</span>
                                <p class="font-medium text-slate-800">{{ $email ?: 'Not specified' }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">User Type:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $user_type === 'staff' ? 'bg-blue-100 text-blue-800' : ($user_type === 'public' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $user_type ? ($user_type === 'staff' ? 'Staff (บุคลากร)' : 'Public (บุคคลทั่วไป)') : 'Not specified' }}
                                </span>
                            </div>

                            @if ($this->selectedDepartment)
                                <div>
                                    <span class="text-sm text-slate-600">Department:</span>
                                    <p class="font-medium text-slate-800">{{ $this->selectedDepartment->name }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm text-slate-600">Status:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Roles:</span>
                                <p class="font-medium text-slate-800">{{ count($selectedRoles) }} selected</p>
                            </div>

                            @if (count($selectedRoles) > 0)
                                <div>
                                    <span class="text-sm text-slate-600">Current Roles:</span>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach ($this->availableRoles as $role)
                                            @if (in_array($role->id, $selectedRoles))
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canUpdate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update User
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating User...
                            </span>
                        </button>

                        @if (!$this->canUpdate && $hasChanges)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                @if (empty(trim($name)))
                                    Name is required
                                @elseif(empty(trim($email)))
                                    Email is required
                                @elseif(count($selectedRoles) === 0)
                                    At least one role must be selected
                                @elseif($user_type === 'staff' && empty($department_id))
                                    Department is required for staff users
                                @endif
                            </p>
                        @elseif(!$hasChanges && !$updatePassword)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                No changes to save
                            </p>
                        @endif

                        <button wire:click="resetForm" type="button"
                            {{ !$hasChanges && !$updatePassword ? 'disabled' : '' }}
                            class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 disabled:bg-slate-50 text-slate-700 disabled:text-slate-400 font-medium rounded-lg transition-colors duration-200 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-slate-600 font-medium">Updating user account...</p>
        </div>
    </div>

    <!-- Unsaved Changes Warning -->
    @if ($hasChanges || $updatePassword)
        <script>
            window.addEventListener('beforeunload', function(e) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            });
        </script>
    @endif
</div>
