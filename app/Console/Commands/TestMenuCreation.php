<?php

// Create this file as: app/Console/Commands/TestMenuCreation.php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;

class TestMenuCreation extends Command
{
    protected $signature = 'menu:test-create';
    protected $description = 'Test menu creation functionality';

    public function handle()
    {
        $this->info('Testing menu creation...');

        try {
            // Test 1: Check if table exists
            $this->info('1. Checking if menus table exists...');
            if (!\Schema::hasTable('menus')) {
                $this->error('❌ Menus table does not exist! Run: php artisan migrate');
                return 1;
            }
            $this->info('✅ Menus table exists');

            // Test 2: Check table columns
            $this->info('2. Checking table structure...');
            $columns = \Schema::getColumnListing('menus');
            $requiredColumns = ['id', 'name', 'slug', 'url', 'icon', 'parent_id', 'sort_order', 'target', 'permission'];

            foreach ($requiredColumns as $column) {
                if (!in_array($column, $columns)) {
                    $this->error("❌ Missing column: {$column}");
                    return 1;
                }
            }
            $this->info('✅ All required columns exist');

            // Test 3: Try to create a menu
            $this->info('3. Testing menu creation...');
            $menu = Menu::create([
                'name' => 'Test Menu ' . now()->timestamp,
                'slug' => 'test-menu-' . now()->timestamp,
                'url' => '/test',
                'icon' => 'fas fa-test',
                'sort_order' => 999,
                'target' => '_self',
                'permission' => null
            ]);

            $this->info("✅ Menu created successfully! ID: {$menu->id}");

            // Test 4: Try to retrieve the menu
            $this->info('4. Testing menu retrieval...');
            $retrievedMenu = Menu::find($menu->id);
            if ($retrievedMenu) {
                $this->info('✅ Menu retrieved successfully');
                $this->table(['Field', 'Value'], [
                    ['ID', $retrievedMenu->id],
                    ['Name', $retrievedMenu->name],
                    ['Slug', $retrievedMenu->slug],
                    ['URL', $retrievedMenu->url],
                    ['Icon', $retrievedMenu->icon],
                    ['Sort Order', $retrievedMenu->sort_order],
                    ['Target', $retrievedMenu->target],
                    ['Permission', $retrievedMenu->permission ?? 'NULL'],
                ]);
            } else {
                $this->error('❌ Could not retrieve created menu');
                return 1;
            }

            // Test 5: Clean up
            $this->info('5. Cleaning up test data...');
            $retrievedMenu->delete();
            $this->info('✅ Test menu deleted');

            $this->info('🎉 All tests passed! Menu creation is working correctly.');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error during testing: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}

/*
=================================================================
USAGE:
=================================================================

1. Create the command file:
   php artisan make:command TestMenuCreation

2. Copy the above code to: app/Console/Commands/TestMenuCreation.php

3. Run the test:
   php artisan menu:test-create

4. If any tests fail, follow the error messages to fix the issues

=================================================================
*/
