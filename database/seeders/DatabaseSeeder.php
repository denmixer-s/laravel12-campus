<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call([
            ComprehensivePermissionsSeeder::class,
            UniversityStructureSeeder::class,
            BlogCategoriesSeeder::class,
            BlogTagsSeeder::class,
            PagesSeeder::class,
            SlidersSeeder::class,
            MenuSeeder::class,
        ]);

        // Clear cache after seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
