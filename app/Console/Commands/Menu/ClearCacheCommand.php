<?php
namespace App\Console\Commands\Menu;

use App\Services\MenuService;
use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'menu:clear-cache';
    protected $description = 'Clear all menu caches';

    /**
     * Execute the console command.
     */
    public function handle(MenuService $menuService)
    {
        $menuService->clearCache();
        $this->info('Menu caches cleared successfully!');
    }
}
