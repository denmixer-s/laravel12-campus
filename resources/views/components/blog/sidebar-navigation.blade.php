{{-- Blog Management Sidebar Navigation (Simple Fix) --}}
@can('blog.posts.view')
    <div class="mb-4">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            {{-- Header --}}
            <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-slate-200">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">จัดการบล็อก</h3>
                        <p class="text-xs text-slate-600">Blog Management</p>
                    </div>
                </div>
            </div>

            {{-- Navigation Items --}}
            <div class="divide-y divide-slate-100">
                {{-- Dashboard --}}
                <a href="{{ route('administrator.blog.dashboard') }}"
                   class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.dashboard') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                    <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-slate-800">แดชบอร์ด</div>
                        <div class="text-xs text-slate-500">ภาพรวม</div>
                    </div>
                </a>

                {{-- Posts Management --}}
                @can('blog.posts.view')
                    <a href="{{ route('administrator.blog.posts.index') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.posts.index') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">โพสต์ทั้งหมด</div>
                            <div class="text-xs text-slate-500">
                                @if(class_exists('App\Models\BlogPost'))
                                    {{ App\Models\BlogPost::count() }} รายการ
                                @else
                                    All Posts
                                @endif
                            </div>
                        </div>
                        @if(class_exists('App\Models\BlogPost'))
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ App\Models\BlogPost::count() }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('administrator.blog.posts.bulk-actions') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.posts.bulk-actions') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">จัดการแบบกลุ่ม</div>
                            <div class="text-xs text-slate-500">Bulk Actions</div>
                        </div>
                    </a>

                    @can('blog.posts.create')
                        <a href="{{ route('administrator.blog.posts.create') }}"
                           class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.posts.create') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                            <div class="p-1.5 bg-green-100 rounded-lg mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-slate-800">เขียนโพสต์ใหม่</div>
                                <div class="text-xs text-slate-500">Create New Post</div>
                            </div>
                        </a>
                    @endcan
                @endcan

                {{-- Categories Management --}}
                @can('blog.categories.view')
                    <a href="{{ route('administrator.blog.categories.index') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.categories.*') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">หมวดหมู่</div>
                            <div class="text-xs text-slate-500">
                                @if(class_exists('App\Models\BlogCategory'))
                                    {{ App\Models\BlogCategory::count() }} หมวดหมู่
                                @else
                                    Categories
                                @endif
                            </div>
                        </div>
                        @if(class_exists('App\Models\BlogCategory'))
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ App\Models\BlogCategory::count() }}
                            </span>
                        @endif
                    </a>
                @endcan

                {{-- Tags Management --}}
                @can('blog.tags.view')
                    <a href="{{ route('administrator.blog.tags.index') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.tags.*') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">แท็ก</div>
                            <div class="text-xs text-slate-500">
                                @if(class_exists('App\Models\BlogTag'))
                                    {{ App\Models\BlogTag::count() }} แท็ก
                                @else
                                    Tags
                                @endif
                            </div>
                        </div>
                        @if(class_exists('App\Models\BlogTag'))
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ App\Models\BlogTag::count() }}
                            </span>
                        @endif
                    </a>
                @endcan

                {{-- Comments Management --}}
                @can('blog.comments.view')
                    <a href="{{ route('administrator.blog.comments.index') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.comments.*') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">ความคิดเห็น</div>
                            <div class="text-xs text-slate-500">
                                @if(class_exists('App\Models\BlogComment'))
                                    @php $pending = App\Models\BlogComment::where('status', 'pending')->count(); @endphp
                                    {{ $pending > 0 ? $pending . ' รออนุมัติ' : 'ทั้งหมด' }}
                                @else
                                    Comments
                                @endif
                            </div>
                        </div>
                        @if(class_exists('App\Models\BlogComment'))
                            @php $pending = App\Models\BlogComment::where('status', 'pending')->count(); @endphp
                            @if($pending > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $pending }}
                                </span>
                            @endif
                        @endif
                    </a>
                @endcan

                {{-- Media Management --}}
                @can('blog.media.manage')
                    <a href="{{ route('administrator.blog.media.index') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.media.*') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">คลังสื่อ</div>
                            <div class="text-xs text-slate-500">Media Library</div>
                        </div>
                    </a>
                @endcan

                {{-- Analytics --}}
                @can('blog.analytics.view')
                    <a href="{{ route('administrator.blog.analytics') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.analytics') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">สถิติการใช้งาน</div>
                            <div class="text-xs text-slate-500">Analytics</div>
                        </div>
                    </a>
                @endcan

                {{-- Settings --}}
                @can('blog.settings.view')
                    <a href="{{ route('administrator.blog.settings') }}"
                       class="flex items-center px-4 py-3 hover:bg-slate-50 transition-colors {{ request()->routeIs('administrator.blog.settings') ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                        <div class="p-1.5 bg-slate-100 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-slate-800">การตั้งค่า</div>
                            <div class="text-xs text-slate-500">Settings</div>
                        </div>
                    </a>
                @endcan
            </div>

            {{-- Footer Actions --}}
            <div class="px-4 py-3 bg-slate-50 border-t border-slate-200">
                <a href="{{ route('administrator.blog.dashboard') }}"
                   target="_blank"
                   class="flex items-center justify-center w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    ดูบล็อก (หน้าเว็บ)
                </a>
            </div>
        </div>
    </div>
@endcan
