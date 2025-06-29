<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
    class="overflow-hidden border-r border-gray-200 bg-sidebar text-sidebar-foreground dark:border-gray-700 sidebar-transition">
    <!-- Sidebar Content -->
    <div class="flex flex-col h-full">
        <!-- Sidebar Menu -->
        <nav class="flex-1 py-4 overflow-y-auto custom-scrollbar">
            <ul class="px-2 space-y-1">
                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('administrator.dashboard') }}" icon='fas-chart-line'
                    :active="request()->routeIs('administrator.dashboard*')">Dashboard</x-layouts.sidebar-link>

                <!-- Example two level -->
                {{-- <x-layouts.sidebar-two-level-link-parent title="Example two level" icon="fas-house"
                    :active="request()->routeIs('two-level*')">
                    <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                        :active="request()->routeIs('two-level*')">Child</x-layouts.sidebar-two-level-link>
                </x-layouts.sidebar-two-level-link-parent> --}}

                <!-- Example three level -->
                {{-- <x-layouts.sidebar-two-level-link-parent title="Example three level" icon="fas-house"
                    :active="request()->routeIs('three-level*')">
                    <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                        :active="request()->routeIs('three-level*')">Single Link</x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-three-level-parent title="Third Level" icon="fas-house"
                        :active="request()->routeIs('three-level*')">
                        <x-layouts.sidebar-three-level-link href="#" :active="request()->routeIs('three-level*')">
                            Third Level Link
                        </x-layouts.sidebar-three-level-link>
                    </x-layouts.sidebar-three-level-parent>
                </x-layouts.sidebar-two-level-link-parent> --}}

                <!-- Menu Content -->
                <x-layouts.sidebar-link href="{{ route('administrator.pages.index') }}" icon='fas-file-lines'
                    :active="request()->routeIs('administrator.pages.*')">Pages</x-layouts.sidebar-link>

                <x-layouts.sidebar-link href="{{ route('administrator.sliders.index') }}" icon='fas-sliders'
                    :active="request()->routeIs('administrator.sliders.*')">Sliders</x-layouts.sidebar-link>



                {{-- <x-layouts.sidebar-two-level-link-parent title="Content" icon="fas-layer-group" :active="request()->routeIs('administrator.pages.*', 'administrator.sliders.*')">


                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.sliders.index') }}"
                        icon='fas-sliders' :active="request()->routeIs('administrator.sliders.*')">Sliders
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.pages.index') }}"
                        icon='fas-file-lines' :active="request()->routeIs('administrator.pages.*')">Pages
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent> --}}



                <!-- Documents DATA WEB -->
                <x-layouts.sidebar-two-level-link-parent title="Documents" icon="fas-file-pdf" :active="request()->routeIs('administrator.documents.*', 'administrator.categorydocs.*')">

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.documents.statistics') }}"
                        icon='fas-list' :active="request()->routeIs('administrator.documents.statistics')">สถิติ
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.documents.index') }}"
                        icon='fas-file-pdf' :active="request()->routeIs(
                            'administrator.documents.index',
                            'administrator.documents.create',
                            'administrator.documents.edit',
                            'administrator.documents.trash',
                        )">Documents
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.documents.categories') }}"
                        icon='fas-list' :active="request()->routeIs('administrator.documents.categories')">หมวดหมู่เอกสาร
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.documents.types') }}"
                        icon='fas-list' :active="request()->routeIs('administrator.documents.types')">ประเภทเอกสาร
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>


                <x-layouts.sidebar-two-level-link-parent title="จัดการบล็อก" icon="fas-blog" :active="request()->routeIs('administrator.blog.*')">

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.dashboard') }}"
                        icon='fas-gauge-high' :active="request()->routeIs('administrator.blog.dashboard')">แดชบอร์ด
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.posts.index') }}"
                        icon='fas-folder-open' :active="request()->routeIs('administrator.blog.posts.index')">โพสต์ทั้งหมด
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.posts.create') }}"
                        icon='fas-pencil' :active="request()->routeIs('administrator.blog.posts.create')">เขียนโพสต์ใหม่
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.posts.bulk-actions') }}"
                        icon='fas-book' :active="request()->routeIs('administrator.blog.posts.bulk-actions')">จัดการแบบกลุ่ม
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.categories.index') }}"
                        icon='fas-list' :active="request()->routeIs('administrator.blog.categories.index')">หมวดหมู่
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.blog.tags.index') }}"
                        icon='fas-list' :active="request()->routeIs('administrator.blog.tags.index')">แท็ก
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-three-level-parent title="Third Level" icon="fas-house" :active="request()->routeIs('three-level*')">
                        <x-layouts.sidebar-three-level-link href="#" :active="request()->routeIs('three-level*')">
                            Third Level Link
                        </x-layouts.sidebar-three-level-link>
                    </x-layouts.sidebar-three-level-parent>
                </x-layouts.sidebar-two-level-link-parent>


                <!-- Menu DATA WEB -->
                <x-layouts.sidebar-two-level-link-parent title="Structure" icon="fas-folder-tree" :active="request()->routeIs(
                    'administrator.organization.universities.*',
                    'administrator.organization.faculties.*',
                    'administrator.organization.divisions.*',
                    'administrator.organization.departments.*',
                    'administrator.menus.*',
                )">
                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.menus.index') }}" icon='fas-bars'
                        :active="request()->routeIs('administrator.menus.*')">Menu
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link
                        href="{{ route('administrator.organization.universities.index') }}" icon='fas-house'
                        :active="request()->routeIs('administrator.organization.universities.*')">มหาวิทยาลัยฯ
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.organization.faculties.index') }}"
                        icon='fas-building' :active="request()->routeIs('administrator.organization.faculties.*')">สำนักงาน/คณะ
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.organization.divisions.index') }}"
                        icon='fas-sitemap' :active="request()->routeIs('administrator.organization.divisions.*')">กอง/งาน
                    </x-layouts.sidebar-two-level-link>
                    <x-layouts.sidebar-two-level-link
                        href="{{ route('administrator.organization.departments.index') }}" icon='fas-folder-tree'
                        :active="request()->routeIs('administrator.organization.departments.*')">แผนกงาน
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>

                <!-- Menu SYSTEM -->
                <x-layouts.sidebar-two-level-link-parent title="System" icon="fas-gear" :active="request()->routeIs(
                    'administrator.users.*',
                    'administrator.roles.*',
                    'administrator.permissions.*',
                )">

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.users.index') }}" icon='fas-users'
                        :active="request()->routeIs('administrator.users.*')">Users
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.roles.index') }}" icon='fas-gears'
                        :active="request()->routeIs('administrator.roles.*')">Roles
                    </x-layouts.sidebar-two-level-link>

                    <x-layouts.sidebar-two-level-link href="{{ route('administrator.permissions.index') }}"
                        icon='fas-list-check' :active="request()->routeIs('administrator.permissions.*')"> Permissions
                    </x-layouts.sidebar-two-level-link>

                </x-layouts.sidebar-two-level-link-parent>


            </ul>
        </nav>
    </div>
</aside>
