<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                                @if($menu->icon)
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <!-- Icon based on menu icon -->
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                @else
                                    <span class="text-white font-medium text-sm">{{ substr($menu->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800">{{ $menu->name }}</h1>
                                <p class="text-slate-600">Menu Details and Management</p>
                            </div>
                        </div>

                        <!-- Menu Badges -->
                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if($menu->is_active)
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Active
                                @else
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Inactive
                                @endif
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $menuStats['children_count'] }} {{ Str::plural('Child', $menuStats['children_count']) }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($menu->show === 'header') bg-blue-100 text-blue-800
                                @elseif($menu->show === 'footer') bg-purple-100 text-purple-800
                                @elseif($menu->show === 'sidebar') bg-green-100 text-green-800
                                @elseif($menu->show === 'both') bg-amber-100 text-amber-800
                                @else bg-slate-100 text-slate-800
                                @endif">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                {{ ucfirst($menu->show) }}
                            </span>

                            @if($menu->permission)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Protected
                            </span>
                            @endif

                            @if($menu->target === '_blank')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                External Link
                            </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Created {{ $menuStats['created_human'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button wire:click="goToMenusList"
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </button>

                        <button wire:click="toggleStatus"
                                class="inline-flex items-center px-4 py-2 {{ $menu->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium rounded-lg transition-colors duration-200">
                            @if($menu->is_active)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                </svg>
                                Deactivate
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activate
                            @endif
                        </button>

                        <button wire:click="editMenu"
                                class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Menu
                        </button>

                        <button wire:click="duplicateMenu"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Child Items</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $menuStats['children_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Active Children</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $menuStats['active_children_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Hierarchy Depth</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $menuStats['depth'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Last Updated</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['updated_date'] }}</p>
                        <p class="text-xs text-slate-500">{{ $menuStats['updated_human'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('overview')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Overview
                    </button>

                    <button wire:click="setActiveTab('hierarchy')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'hierarchy' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hierarchy ({{ $menuStats['children_count'] }})
                    </button>

                    <button wire:click="setActiveTab('settings')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'settings' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Settings
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Menu Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Menu Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Menu Name</label>
                                    <p class="text-slate-900 font-medium">{{ $menu->name }}</p>
                                </div>
                                @if($menu->description)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Description</label>
                                    <p class="text-slate-900">{{ $menu->description }}</p>
                                </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-slate-600">URL/Route</label>
                                    @if($menu->route_name)
                                        <p class="text-slate-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 mr-2">
                                                Route
                                            </span>
                                            {{ $menu->route_name }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">Resolves to: {{ $menuStats['url'] }}</p>
                                    @elseif($menu->url)
                                        <p class="text-slate-900">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                                URL
                                            </span>
                                            {{ $menu->url }}
                                        </p>
                                    @else
                                        <p class="text-slate-500">No URL specified</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Sort Order</label>
                                    <p class="text-slate-900">{{ $menu->sort_order }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Access & Display</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Display Location</label>
                                    <p class="text-slate-900 capitalize">{{ $menu->show }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Link Target</label>
                                    <p class="text-slate-900">{{ $menu->target === '_blank' ? 'New Window' : 'Same Window' }}</p>
                                </div>
                                @if($menu->permission)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Required Permission</label>
                                    <p class="text-slate-900">{{ $menu->permission }}</p>
                                    <p class="text-xs {{ $menuStats['can_access'] ? 'text-green-600' : 'text-red-600' }} mt-1">
                                        {{ $menuStats['can_access'] ? 'You have access' : 'You do not have access' }}
                                    </p>
                                </div>
                                @else
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Access Level</label>
                                    <p class="text-green-600">Public (No permission required)</p>
                                </div>
                                @endif
                                @if($menu->icon)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Icon</label>
                                    <p class="text-slate-900">{{ $menu->icon }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Breadcrumb Trail -->
                    @if(count($menuStats['breadcrumbs']) > 1)
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Breadcrumb Trail</h3>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                @foreach($menuStats['breadcrumbs'] as $index => $breadcrumb)
                                <li class="flex">
                                    @if($index > 0)
                                    <svg class="flex-shrink-0 h-5 w-5 text-slate-400 mr-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                    <span class="text-sm font-medium {{ $breadcrumb->id === $menu->id ? 'text-blue-600' : 'text-slate-500' }}">
                                        {{ $breadcrumb->name }}
                                    </span>
                                </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($menuStats['url'] !== '#')
                            <a href="{{ $menuStats['url'] }}" 
                               target="{{ $menu->target }}"
                               class="inline-flex items-center justify-center px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Visit Link
                            </a>
                            @endif

                            <button wire:click="editMenu"
                                    class="inline-flex items-center justify-center px-4 py-3 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Menu
                            </button>

                            <button wire:click="duplicateMenu"
                                    class="inline-flex items-center justify-center px-4 py-3 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Duplicate
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Hierarchy Tab -->
                @if($activeTab === 'hierarchy')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Menu Hierarchy</h3>
                        <p class="text-sm text-slate-600">Complete hierarchy starting from this menu</p>
                    </div>

                    @if($menuHierarchy->count() > 1)
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                        <div class="divide-y divide-slate-200">
                            @foreach($menuHierarchy as $hierarchyItem)
                            <div class="p-4 {{ $hierarchyItem->id === $menu->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}">
                                <div class="flex items-center" style="padding-left: {{ ($hierarchyItem->hierarchy_level ?? 0) * 20 }}px;">
                                    @if(isset($hierarchyItem->hierarchy_level) && $hierarchyItem->hierarchy_level > 0)
                                        <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    @endif
                                    
                                    <div class="flex items-center flex-1">
                                        @if($hierarchyItem->icon)
                                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-slate-400 to-slate-500 flex items-center justify-center mr-3">
                                                <span class="text-white font-medium text-xs">{{ substr($hierarchyItem->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <h4 class="text-sm font-medium text-slate-900 {{ $hierarchyItem->id === $menu->id ? 'font-bold' : '' }}">
                                                    {{ $hierarchyItem->name }}
                                                </h4>
                                                @if($hierarchyItem->id === $menu->id)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        Current
                                                    </span>
                                                @endif
                                            </div>
                                            @if($hierarchyItem->url || $hierarchyItem->route_name)
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $hierarchyItem->route_name ? 'Route: ' . $hierarchyItem->route_name : 'URL: ' . $hierarchyItem->url }}
                                            </p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $hierarchyItem->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $hierarchyItem->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                            <span class="text-xs text-slate-500">Order: {{ $hierarchyItem->sort_order }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 mb-2">No child items</h3>
                        <p class="text-slate-500">This menu doesn't have any child items yet.</p>
                    </div>
                    @endif

                    <!-- Sibling Menus -->
                    @if($siblingMenus->count() > 0)
                    <div>
                        <h4 class="text-lg font-semibold text-slate-800 mb-4">Sibling Menus</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($siblingMenus as $sibling)
                            <div class="bg-white rounded-lg border border-slate-200 p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    @if($sibling->icon)
                                        <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-slate-400 to-slate-500 flex items-center justify-center mr-3">
                                            <span class="text-white font-medium text-xs">{{ substr($sibling->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1 min-w-0">
                                        <h5 class="text-sm font-medium text-slate-900 truncate">{{ $sibling->name }}</h5>
                                        <p class="text-xs text-slate-500">Order: {{ $sibling->sort_order }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Settings Tab -->
                @if($activeTab === 'settings')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Menu Settings & Information</h3>
                        <p class="text-sm text-slate-600">Detailed configuration and metadata</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Technical Details -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-slate-800 mb-4">Technical Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Menu ID</label>
                                    <p class="text-slate-900 font-mono">{{ $menu->id }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Guard Name</label>
                                    <p class="text-slate-900">web</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Created At</label>
                                    <p class="text-slate-900">{{ $menuStats['created_datetime'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $menuStats['created_human'] }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Updated At</label>
                                    <p class="text-slate-900">{{ $menuStats['updated_datetime'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $menuStats['updated_human'] }}</p>
                                </div>
                                @if($menu->parent_id)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Parent ID</label>
                                    <p class="text-slate-900 font-mono">{{ $menu->parent_id }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Access Information -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-slate-800 mb-4">Access Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Authentication Required</label>
                                    <p class="text-slate-900">{{ $menuAccessInfo['requires_auth'] ? 'Yes' : 'No' }}</p>
                                </div>
                                @if($menuAccessInfo['permission_required'])
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Required Permission</label>
                                    <p class="text-slate-900">{{ $menuAccessInfo['permission_required'] }}</p>
                                </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Current User Access</label>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $menuAccessInfo['current_user_can_access'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $menuAccessInfo['current_user_can_access'] ? 'Has Access' : 'No Access' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">External Link</label>
                                    <p class="text-slate-900">{{ $menuAccessInfo['is_external_link'] ? 'Yes' : 'No' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Opens in New Tab</label>
                                    <p class="text-slate-900">{{ $menuAccessInfo['opens_in_new_tab'] ? 'Yes' : 'No' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Data -->
                    @if($menu->meta_data)
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-slate-800 mb-4">Meta Data</h4>
                        <pre class="text-xs text-slate-700 bg-white p-4 rounded border overflow-x-auto">{{ json_encode($menu->meta_data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @endif

                    <!-- Quick Status Toggle -->
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-slate-800 mb-4">Quick Actions</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <h5 class="text-sm font-medium text-slate-900">Menu Status</h5>
                                <p class="text-xs text-slate-500">Toggle the active status of this menu</p>
                            </div>
                            <button wire:click="toggleStatus"
                                    class="inline-flex items-center px-4 py-2 {{ $menu->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium rounded-lg transition-colors">
                                @if($menu->is_active)
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                    </svg>
                                    Deactivate
                                @else
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Activate
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>