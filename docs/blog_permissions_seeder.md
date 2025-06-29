<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BlogPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define blog permissions
        $blogPermissions = [
            // Blog Posts
            'blog.posts.view',
            'blog.posts.create',
            'blog.posts.edit',
            'blog.posts.delete',
            'blog.posts.publish',
            'blog.posts.schedule',
            'blog.posts.feature',
            'blog.posts.bulk-delete',
            'blog.posts.export',
            
            // Blog Categories
            'blog.categories.view',
            'blog.categories.create',
            'blog.categories.edit',
            'blog.categories.delete',
            'blog.categories.reorder',
            
            // Blog Tags
            'blog.tags.view',
            'blog.tags.create',
            'blog.tags.edit',
            'blog.tags.delete',
            'blog.tags.merge',
            
            // Blog Comments
            'blog.comments.view',
            'blog.comments.moderate',
            'blog.comments.approve',
            'blog.comments.reject',
            'blog.comments.delete',
            'blog.comments.bulk-moderate',
            
            // Blog Settings
            'blog.settings.view',
            'blog.settings.edit',
            
            // Blog Analytics
            'blog.analytics.view',
            'blog.analytics.export',
            
            // Blog Media
            'blog.media.upload',
            'blog.media.delete',
            'blog.media.manage',
        ];

        // Create permissions
        foreach ($blogPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
            
            $this->command->info("Created permission: {$permission}");
        }

        // Assign permissions to existing roles
        $this->assignPermissionsToRoles();

        $this->command->info('Blog permissions created and assigned successfully!');
    }

    private function assignPermissionsToRoles(): void
    {
        // Super Admin - All blog permissions
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::where('name', 'like', 'blog.%')->get());
            $this->command->info('Assigned all blog permissions to Super Admin');
        }

        // Admin - Most blog permissions (except some sensitive ones)
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $adminPermissions = [
                'blog.posts.view', 'blog.posts.create', 'blog.posts.edit', 'blog.posts.delete',
                'blog.posts.publish', 'blog.posts.schedule', 'blog.posts.feature',
                'blog.categories.view', 'blog.categories.create', 'blog.categories.edit', 'blog.categories.delete',
                'blog.tags.view', 'blog.tags.create', 'blog.tags.edit', 'blog.tags.delete',
                'blog.comments.view', 'blog.comments.moderate', 'blog.comments.approve', 'blog.comments.reject',
                'blog.settings.view', 'blog.settings.edit',
                'blog.analytics.view',
                'blog.media.upload', 'blog.media.delete', 'blog.media.manage',
            ];
            
            $permissions = Permission::whereIn('name', $adminPermissions)->get();
            $admin->givePermissionTo($permissions);
            $this->command->info('Assigned blog permissions to Admin');
        }

        // Editor - Content management permissions
        $editor = Role::where('name', 'Editor')->first();
        if ($editor) {
            $editorPermissions = [
                'blog.posts.view', 'blog.posts.create', 'blog.posts.edit', 'blog.posts.publish',
                'blog.categories.view', 'blog.categories.create', 'blog.categories.edit',
                'blog.tags.view', 'blog.tags.create', 'blog.tags.edit',
                'blog.comments.view', 'blog.comments.moderate', 'blog.comments.approve',
                'blog.media.upload', 'blog.media.manage',
            ];
            
            $permissions = Permission::whereIn('name', $editorPermissions)->get();
            $editor->givePermissionTo($permissions);
            $this->command->info('Assigned blog permissions to Editor');
        }

        // Author - Basic content creation
        $author = Role::where('name', 'Author')->first();
        if ($author) {
            $authorPermissions = [
                'blog.posts.view', 'blog.posts.create', 'blog.posts.edit',
                'blog.categories.view',
                'blog.tags.view', 'blog.tags.create',
                'blog.comments.view',
                'blog.media.upload',
            ];
            
            $permissions = Permission::whereIn('name', $authorPermissions)->get();
            $author->givePermissionTo($permissions);
            $this->command->info('Assigned blog permissions to Author');
        }

        // Viewer - Read-only access
        $viewer = Role::where('name', 'Viewer')->first();
        if ($viewer) {
            $viewerPermissions = [
                'blog.posts.view',
                'blog.categories.view',
                'blog.tags.view',
                'blog.comments.view',
            ];
            
            $permissions = Permission::whereIn('name', $viewerPermissions)->get();
            $viewer->givePermissionTo($permissions);
            $this->command->info('Assigned blog permissions to Viewer');
        }
    }
}
