
{{-- Breadcrumb Component --}}
@if(request()->routeIs('administrator.blog.*'))
    <nav class="mb-6" aria-label="Breadcrumb">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 px-4 py-3">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('administrator.dashboard') }}" class="text-slate-500 hover:text-slate-700 transition-colors">
                        แดชบอร์ด
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('administrator.blog.dashboard') }}" class="text-slate-500 hover:text-slate-700 transition-colors">
                        จัดการบล็อก
                    </a>
                </li>

                @if(request()->routeIs('administrator.blog.posts.*'))
                    <li>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li>
                        <a href="{{ route('administrator.blog.posts.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors">
                            โพสต์
                        </a>
                    </li>

                    @if(request()->routeIs('administrator.blog.posts.bulk-actions'))
                        <li>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="text-slate-800 font-medium">จัดการแบบกลุ่ม</li>
                    @elseif(request()->routeIs('administrator.blog.posts.create'))
                        <li>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="text-slate-800 font-medium">เขียนโพสต์ใหม่</li>
                    @elseif(request()->routeIs('administrator.blog.posts.edit'))
                        <li>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </li>
                        <li class="text-slate-800 font-medium">แก้ไขโพสต์</li>
                    @endif
                @endif
            </ol>
        </div>
    </nav>
@endif
