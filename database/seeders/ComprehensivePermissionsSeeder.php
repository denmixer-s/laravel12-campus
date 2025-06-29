<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class ComprehensivePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('=== Starting Comprehensive Permissions Setup ===');

        // 1. Create all permissions
        $this->createPermissions();

        // 2. Create roles and assign permissions
        $this->createRoles();

        // 3. Create default users
        $this->createUsers();

        // 4. Display summary
        $this->displaySummary();

        $this->command->info('=== Comprehensive Permissions Setup Complete! ===');
    }

    /**
     * Create all permissions for the system
     */
    private function createPermissions(): void
    {
        $this->command->info('Creating permissions...');

        $allPermissions = [
            // =========================================================================
            // ORGANIZATION MANAGEMENT PERMISSIONS
            // =========================================================================
            'organizations.view-all' => 'ดูข้อมูลองค์กรทั้งหมด',
            'organizations.manage-all' => 'จัดการองค์กรทั้งหมด',
            'organizations.reports.view' => 'ดูรายงานองค์กร',
            'organizations.settings.manage' => 'จัดการการตั้งค่าองค์กร',

            // Universities
            'organizations.universities.view' => 'ดูข้อมูลมหาวิทยาลัย',
            'organizations.universities.create' => 'สร้างมหาวิทยาลัย',
            'organizations.universities.edit' => 'แก้ไขมหาวิทยาลัย',
            'organizations.universities.delete' => 'ลบมหาวิทยาลัย',
            'organizations.universities.manage' => 'จัดการมหาวิทยาลัยทั้งหมด',

            // Faculties
            'organizations.faculties.view' => 'ดูข้อมูลคณะ/หน่วยงาน',
            'organizations.faculties.create' => 'สร้างคณะ/หน่วยงาน',
            'organizations.faculties.edit' => 'แก้ไขคณะ/หน่วยงาน',
            'organizations.faculties.delete' => 'ลบคณะ/หน่วยงาน',
            'organizations.faculties.manage' => 'จัดการคณะ/หน่วยงานทั้งหมด',

            // Divisions
            'organizations.divisions.view' => 'ดูข้อมูลภาควิชา',
            'organizations.divisions.create' => 'สร้างภาควิชา',
            'organizations.divisions.edit' => 'แก้ไขภาควิชา',
            'organizations.divisions.delete' => 'ลบภาควิชา',
            'organizations.divisions.manage' => 'จัดการภาควิชาทั้งหมด',

            // Departments
            'organizations.departments.view' => 'ดูข้อมูลงาน/แผนก',
            'organizations.departments.create' => 'สร้างงาน/แผนก',
            'organizations.departments.edit' => 'แก้ไขงาน/แผนก',
            'organizations.departments.delete' => 'ลบงาน/แผนก',
            'organizations.departments.manage' => 'จัดการงาน/แผนกทั้งหมด',

            // Staff Management
            'organizations.staff.view' => 'ดูข้อมูลบุคลากร',
            'organizations.staff.create' => 'สร้างบุคลากร',
            'organizations.staff.edit' => 'แก้ไขข้อมูลบุคลากร',
            'organizations.staff.delete' => 'ลบบุคลากร',
            'organizations.staff.manage' => 'จัดการบุคลากรทั้งหมด',
            'organizations.staff.assign-department' => 'กำหนดแผนกให้บุคลากร',

            // =========================================================================
            // USER MANAGEMENT PERMISSIONS
            // =========================================================================
            'users.view' => 'ดูรายการผู้ใช้',
            'users.create' => 'สร้างผู้ใช้',
            'users.edit' => 'แก้ไขผู้ใช้',
            'users.delete' => 'ลบผู้ใช้',
            'users.manage' => 'จัดการผู้ใช้ทั้งหมด',
            'users.view-profile' => 'ดูโปรไฟล์ตัวเอง',
            'users.edit-profile' => 'แก้ไขโปรไฟล์ตัวเอง',
            'users.assign-roles' => 'กำหนดบทบาทผู้ใช้',
            'users.manage-permissions' => 'จัดการสิทธิ์ผู้ใช้',
            'users.bulk-delete' => 'ลบผู้ใช้หลายคนพร้อมกัน',
            'users.export' => 'ส่งออกข้อมูลผู้ใช้',
            'users.import' => 'นำเข้าข้อมูลผู้ใช้',

            // =========================================================================
            // ROLE & PERMISSION MANAGEMENT
            // =========================================================================
            'roles.view' => 'ดูรายการบทบาท',
            'roles.create' => 'สร้างบทบาท',
            'roles.edit' => 'แก้ไขบทบาท',
            'roles.delete' => 'ลบบทบาท',
            'roles.assign-permissions' => 'กำหนดสิทธิ์ให้บทบาท',

            'permissions.view' => 'ดูรายการสิทธิ์',
            'permissions.create' => 'สร้างสิทธิ์',
            'permissions.edit' => 'แก้ไขสิทธิ์',
            'permissions.delete' => 'ลบสิทธิ์',
            'permissions.bulk-assign' => 'กำหนดสิทธิ์หลายรายการ',

            // =========================================================================
            // DOCUMENT MANAGEMENT PERMISSIONS
            // =========================================================================
            'documents.view' => 'ดูรายการเอกสาร',
            'documents.create' => 'สร้างเอกสาร',
            'documents.edit' => 'แก้ไขเอกสาร',
            'documents.delete' => 'ลบเอกสาร',
            'documents.publish' => 'เผยแพร่เอกสาร',
            'documents.archive' => 'เก็บถาวรเอกสาร',
            'documents.view-all' => 'ดูเอกสารทั้งหมด (ข้ามแผนก)',
            'documents.edit-all' => 'แก้ไขเอกสารทั้งหมด',
            'documents.delete-all' => 'ลบเอกสารทั้งหมด',
            'documents.manage-versions' => 'จัดการเวอร์ชันเอกสาร',
            'documents.view-statistics' => 'ดูสถิติเอกสาร',
            'documents.view-downloads' => 'ดูสถิติการดาวน์โหลด',
            'documents.view-histories' => 'ดูประวัติการแก้ไข',
            'documents.export-reports' => 'ส่งออกรายงานเอกสาร',

            // Document Categories
            'document-categories.view' => 'ดูหมวดหมู่เอกสาร',
            'document-categories.create' => 'สร้างหมวดหมู่เอกสาร',
            'document-categories.edit' => 'แก้ไขหมวดหมู่เอกสาร',
            'document-categories.delete' => 'ลบหมวดหมู่เอกสาร',
            'document-categories.manage' => 'จัดการหมวดหมู่เอกสารทั้งหมด',

            // Document Types
            'document-types.view' => 'ดูประเภทเอกสาร',
            'document-types.create' => 'สร้างประเภทเอกสาร',
            'document-types.edit' => 'แก้ไขประเภทเอกสาร',
            'document-types.delete' => 'ลบประเภทเอกสาร',
            'document-types.manage' => 'จัดการประเภทเอกสารทั้งหมด',

            // =========================================================================
            // BLOG MANAGEMENT PERMISSIONS
            // =========================================================================
            'blog.posts.view' => 'ดูบทความบล็อก',
            'blog.posts.create' => 'สร้างบทความบล็อก',
            'blog.posts.edit' => 'แก้ไขบทความบล็อก',
            'blog.posts.delete' => 'ลบบทความบล็อก',
            'blog.posts.publish' => 'เผยแพร่บทความบล็อก',
            'blog.posts.schedule' => 'กำหนดเวลาเผยแพร่',
            'blog.posts.feature' => 'ตั้งเป็นบทความเด่น',
            'blog.posts.bulk-delete' => 'ลบบทความหลายรายการ',
            'blog.posts.export' => 'ส่งออกบทความ',

            // Blog Categories & Tags
            'blog.categories.view' => 'ดูหมวดหมู่บล็อก',
            'blog.categories.create' => 'สร้างหมวดหมู่บล็อก',
            'blog.categories.edit' => 'แก้ไขหมวดหมู่บล็อก',
            'blog.categories.delete' => 'ลบหมวดหมู่บล็อก',
            'blog.categories.reorder' => 'จัดเรียงหมวดหมู่บล็อก',

            'blog.tags.view' => 'ดูแท็กบล็อก',
            'blog.tags.create' => 'สร้างแท็กบล็อก',
            'blog.tags.edit' => 'แก้ไขแท็กบล็อก',
            'blog.tags.delete' => 'ลบแท็กบล็อก',
            'blog.tags.merge' => 'รวมแท็กบล็อก',

            // Blog Comments
            'blog.comments.view' => 'ดูความคิดเห็นบล็อก',
            'blog.comments.moderate' => 'ตรวจสอบความคิดเห็น',
            'blog.comments.approve' => 'อนุมัติความคิดเห็น',
            'blog.comments.reject' => 'ปฏิเสธความคิดเห็น',
            'blog.comments.delete' => 'ลบความคิดเห็น',
            'blog.comments.bulk-moderate' => 'ตรวจสอบความคิดเห็นหลายรายการ',

            // Blog Settings & Analytics
            'blog.settings.view' => 'ดูการตั้งค่าบล็อก',
            'blog.settings.edit' => 'แก้ไขการตั้งค่าบล็อก',
            'blog.analytics.view' => 'ดูสถิติบล็อก',
            'blog.analytics.export' => 'ส่งออกสถิติบล็อก',

            // Blog Media
            'blog.media.upload' => 'อัพโหลดไฟล์มีเดีย',
            'blog.media.delete' => 'ลบไฟล์มีเดีย',
            'blog.media.manage' => 'จัดการไฟล์มีเดีย',

            // =========================================================================
            // CONTENT MANAGEMENT PERMISSIONS
            // =========================================================================
            // Menu Management
            'menus.view' => 'ดูรายการเมนู',
            'menus.create' => 'สร้างเมนู',
            'menus.edit' => 'แก้ไขเมนู',
            'menus.delete' => 'ลบเมนู',
            'menus.reorder' => 'จัดเรียงเมนู',
            'menus.bulk-delete' => 'ลบเมนูหลายรายการ',

            // Page Management
            'pages.view' => 'ดูรายการหน้าเว็บ',
            'pages.create' => 'สร้างหน้าเว็บ',
            'pages.edit' => 'แก้ไขหน้าเว็บ',
            'pages.delete' => 'ลบหน้าเว็บ',
            'pages.publish' => 'เผยแพร่หน้าเว็บ',
            'pages.bulk-delete' => 'ลบหน้าเว็บหลายรายการ',
            'pages.seo-manage' => 'จัดการ SEO หน้าเว็บ',

            // Slider Management
            'sliders.view' => 'ดูรายการสไลเดอร์',
            'sliders.create' => 'สร้างสไลเดอร์',
            'sliders.edit' => 'แก้ไขสไลเดอร์',
            'sliders.delete' => 'ลบสไลเดอร์',
            'sliders.reorder' => 'จัดเรียงสไลเดอร์',
            'sliders.bulk-delete' => 'ลบสไลเดอร์หลายรายการ',
            'sliders.bulk-update' => 'อัพเดทสไลเดอร์หลายรายการ',
            'sliders.change-location' => 'เปลี่ยนตำแหน่งสไลเดอร์',

            // =========================================================================
            // SYSTEM MANAGEMENT PERMISSIONS
            // =========================================================================
            'system.settings.view' => 'ดูการตั้งค่าระบบ',
            'system.settings.edit' => 'แก้ไขการตั้งค่าระบบ',
            'system.logs.view' => 'ดู Log ระบบ',
            'system.cache.clear' => 'ล้าง Cache ระบบ',
            'system.backup.create' => 'สร้างสำรองข้อมูล',
            'system.backup.restore' => 'คืนค่าสำรองข้อมูล',
            'system.maintenance.mode' => 'เปิด/ปิดโหมดบำรุงรักษา',

            // =========================================================================
            // DASHBOARD & ANALYTICS PERMISSIONS
            // =========================================================================
            'dashboard.view' => 'ดูแดชบอร์ด',
            'dashboard.analytics' => 'ดูการวิเคราะห์',
            'dashboard.reports' => 'ดูรายงาน',

            // =========================================================================
            // PROFILE MANAGEMENT
            // =========================================================================
            'profile.view' => 'ดูโปรไฟล์',
            'profile.edit' => 'แก้ไขโปรไฟล์',
            'profile.change-password' => 'เปลี่ยนรหัสผ่าน',
            'profile.delete-account' => 'ลบบัญชี',
        ];

        // Create all permissions
        foreach ($allPermissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('Created ' . count($allPermissions) . ' permissions');
    }

    /**
     * Create all roles and assign permissions
     */
    private function createRoles(): void
    {
        $this->command->info('Creating roles...');

        $roles = [
            'Super Admin' => [
                'description' => 'ผู้ดูแลระบบสูงสุด - มีสิทธิ์ทั้งหมด',
                'permissions' => 'all'
            ],

            'System Admin' => [
                'description' => 'ผู้ดูแลระบบ - จัดการระบบและองค์กร',
                'permissions' => [
                    // Organization permissions (except universities management)
                    'organizations.universities.view',
                    'organizations.faculties.*',
                    'organizations.divisions.*',
                    'organizations.departments.*',
                    'organizations.staff.*',
                    'organizations.view-all',
                    'organizations.manage-all',
                    'organizations.reports.view',

                    // User management
                    'users.*',
                    'roles.view', 'roles.create', 'roles.edit',
                    'permissions.view',

                    // System settings
                    'system.*',

                    // Content management
                    'menus.*', 'pages.*', 'sliders.*',
                    'documents.*', 'document-categories.*', 'document-types.*',
                    'blog.*',

                    // Dashboard
                    'dashboard.*',
                    'profile.*',
                ]
            ],

            'Organization Admin' => [
                'description' => 'ผู้ดูแลองค์กร - จัดการโครงสร้างองค์กร',
                'permissions' => [
                    'organizations.universities.view',
                    'organizations.faculties.*',
                    'organizations.divisions.*',
                    'organizations.departments.*',
                    'organizations.staff.*',
                    'organizations.view-all',
                    'organizations.manage-all',
                    'organizations.reports.view',
                    'users.view', 'users.create', 'users.edit',
                    'users.view-profile', 'users.edit-profile',
                    'dashboard.view', 'dashboard.analytics', 'dashboard.reports',
                    'profile.*',
                ]
            ],

            'Faculty Admin' => [
                'description' => 'ผู้ดูแลคณะ - จัดการคณะและหน่วยงานภายใต้',
                'permissions' => [
                    'organizations.faculties.view',
                    'organizations.divisions.*',
                    'organizations.departments.*',
                    'organizations.staff.view', 'organizations.staff.edit', 'organizations.staff.assign-department',
                    'users.view', 'users.edit',
                    'users.view-profile', 'users.edit-profile',
                    'dashboard.view',
                    'profile.*',
                ]
            ],

            'Division Admin' => [
                'description' => 'ผู้ดูแลภาควิชา - จัดการภาควิชาและแผนกภายใต้',
                'permissions' => [
                    'organizations.divisions.view',
                    'organizations.departments.*',
                    'organizations.staff.view', 'organizations.staff.edit', 'organizations.staff.assign-department',
                    'users.view', 'users.edit',
                    'users.view-profile', 'users.edit-profile',
                    'dashboard.view',
                    'profile.*',
                ]
            ],

            'Department Admin' => [
                'description' => 'ผู้ดูแลแผนก - จัดการแผนกและบุคลากร',
                'permissions' => [
                    'organizations.departments.view',
                    'organizations.staff.view', 'organizations.staff.edit',
                    'users.view', 'users.edit',
                    'users.view-profile', 'users.edit-profile',
                    'dashboard.view',
                    'profile.*',
                ]
            ],

            'Content Admin' => [
                'description' => 'ผู้ดูแลเนื้อหา - จัดการเนื้อหาทั้งหมด',
                'permissions' => [
                    'menus.*', 'pages.*', 'sliders.*',
                    'documents.*', 'document-categories.*', 'document-types.*',
                    'blog.*',
                    'dashboard.view', 'dashboard.analytics',
                    'profile.*',
                ]
            ],

            'Editor' => [
                'description' => 'บรรณาธิการ - แก้ไขและจัดการเนื้อหา',
                'permissions' => [
                    'menus.view', 'menus.create', 'menus.edit',
                    'pages.*',
                    'sliders.view', 'sliders.create', 'sliders.edit', 'sliders.reorder',
                    'documents.view', 'documents.create', 'documents.edit', 'documents.publish',
                    'document-categories.view',
                    'blog.posts.*', 'blog.categories.*', 'blog.tags.*',
                    'blog.comments.view', 'blog.comments.moderate', 'blog.comments.approve',
                    'blog.media.*',
                    'dashboard.view',
                    'profile.*',
                ]
            ],

            'Author' => [
                'description' => 'ผู้เขียน - สร้างและแก้ไขเนื้อหาพื้นฐาน',
                'permissions' => [
                    'pages.view', 'pages.create', 'pages.edit',
                    'documents.view', 'documents.create', 'documents.edit',
                    'blog.posts.view', 'blog.posts.create', 'blog.posts.edit',
                    'blog.categories.view',
                    'blog.tags.view', 'blog.tags.create',
                    'blog.comments.view',
                    'blog.media.upload',
                    'dashboard.view',
                    'profile.*',
                ]
            ],

            'Staff' => [
                'description' => 'บุคลากร - สิทธิ์พื้นฐานสำหรับบุคลากรในองค์กร',
                'permissions' => [
                    'organizations.universities.view',
                    'organizations.faculties.view',
                    'organizations.divisions.view',
                    'organizations.departments.view',
                    'organizations.staff.view',
                    'documents.view',
                    'blog.posts.view', 'blog.categories.view', 'blog.tags.view',
                    'dashboard.view',
                    'users.view-profile', 'users.edit-profile',
                    'profile.*',
                ]
            ],

            'User' => [
                'description' => 'ผู้ใช้ทั่วไป - สิทธิ์พื้นฐาน',
                'permissions' => [
                    'dashboard.view',
                    'users.view-profile', 'users.edit-profile',
                    'profile.*',
                ]
            ],
        ];

        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($roleData['permissions'] === 'all') {
                // Super Admin gets all permissions
                $role->syncPermissions(Permission::all());
                $this->command->info("Created role: {$roleName} with ALL permissions");
            } else {
                // Process permission patterns
                $permissionNames = [];
                foreach ($roleData['permissions'] as $permissionPattern) {
                    if (str_ends_with($permissionPattern, '.*')) {
                        // Wildcard pattern - get all permissions that start with the prefix
                        $prefix = str_replace('.*', '.', $permissionPattern);
                        $matchingPermissions = Permission::where('name', 'like', $prefix . '%')->pluck('name')->toArray();
                        $permissionNames = array_merge($permissionNames, $matchingPermissions);
                    } else {
                        // Exact permission name
                        $permissionNames[] = $permissionPattern;
                    }
                }

                $permissions = Permission::whereIn('name', array_unique($permissionNames))->get();
                $role->syncPermissions($permissions);
                $this->command->info("Created role: {$roleName} with " . count($permissions) . " permissions");
            }
        }
    }

    /**
     * Create default users
     */
    private function createUsers(): void
    {
        $this->command->info('Creating users...');

        $users = [
            [
                'name' => 'Denpha Saenpaing',
                'email' => 'denpha.sa@rmuti.ac.th',
                'password' => Hash::make('52865055'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Chalalai Ngaonoi',
                'email' => 'chalalai.ng@rmuti.ac.th',
                'password' => Hash::make('52865055'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'System Admin'
            ],
            [
                'name' => 'Organization Admin',
                'email' => 'org.admin@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Organization Admin'
            ],
            [
                'name' => 'Faculty Admin',
                'email' => 'faculty.admin@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Faculty Admin'
            ],
            [
                'name' => 'Division Admin',
                'email' => 'division.admin@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Division Admin'
            ],
            [
                'name' => 'Department Admin',
                'email' => 'dept.admin@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Department Admin'
            ],
            [
                'name' => 'Content Admin',
                'email' => 'content.admin@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Content Admin'
            ],
            [
                'name' => 'Content Editor',
                'email' => 'editor@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Editor'
            ],
            [
                'name' => 'Content Author',
                'email' => 'author@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Author'
            ],
            [
                'name' => 'Staff Member',
                'email' => 'staff@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'staff',
                'status' => 'active',
                'role' => 'Staff'
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@rstc.local',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'user_type' => 'public',
                'status' => 'active',
                'role' => 'User'
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->assignRole($role);

            $this->command->info("Created user: {$user->email} with role: {$role}");
        }

        // Create some additional sample users for testing
        $this->createSampleUsers();

        $this->command->info('All users created successfully!');
    }

    /**
     * Create additional sample users for testing
     */
    private function createSampleUsers(): void
    {
        $this->command->info('Creating sample users...');

        // Sample data for realistic testing
        $sampleUsers = [
            [
                'name' => 'สมชาย ใจดี',
                'email' => 'somchai.j@rstc.local',
                'role' => 'Staff',
                'user_type' => 'staff'
            ],
            [
                'name' => 'สมหญิง สวยงาม',
                'email' => 'somying.s@rstc.local',
                'role' => 'Author',
                'user_type' => 'staff'
            ],
            [
                'name' => 'วิชาญ เก่งกาจ',
                'email' => 'wichan.k@rstc.local',
                'role' => 'Editor',
                'user_type' => 'staff'
            ],
            [
                'name' => 'สุดา ขยันหา',
                'email' => 'suda.k@rstc.local',
                'role' => 'Staff',
                'user_type' => 'staff'
            ],
            [
                'name' => 'ประเสริฐ ดีเด่น',
                'email' => 'prasert.d@rstc.local',
                'role' => 'Department Admin',
                'user_type' => 'staff'
            ],
        ];

        foreach ($sampleUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                ])
            );

            $user->assignRole($role);
            $this->command->info("Created sample user: {$user->email} with role: {$role}");
        }

        // Create test public users
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "testuser{$i}@rstc.local"],
                [
                    'name' => "Test User {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'user_type' => 'public',
                    'status' => 'active',
                ]
            );

            $user->assignRole('User');
            $this->command->info("Created test user: {$user->email}");
        }
    }

    /**
     * Display summary of created permissions and roles
     */
    private function displaySummary(): void
    {
        $this->command->info('');
        $this->command->info('=== SUMMARY ===');
        $this->command->info('Permissions: ' . Permission::count());
        $this->command->info('Roles: ' . Role::count());
        $this->command->info('Users: ' . User::count());
        $this->command->info('');

        $this->command->table(
            ['Role', 'Users Count', 'Permissions Count'],
            Role::withCount(['users', 'permissions'])->get()->map(function ($role) {
                return [
                    $role->name,
                    $role->users_count,
                    $role->permissions_count
                ];
            })->toArray()
        );

        $this->command->info('');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Super Admin: denpha.sa@rmuti.ac.th / 52865055');
        $this->command->info('System Admin: chalalai.ng@rmuti.ac.th / 52865055');
        $this->command->info('Others: [email] / password');
        $this->command->info('');
    }
}
