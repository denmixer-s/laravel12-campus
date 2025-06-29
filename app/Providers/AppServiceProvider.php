<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use App\Models\Slider;
use Carbon\CarbonImmutable;
use App\Policies\MenuPolicy;
use App\Policies\PagePolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\SliderPolicy;
use App\Policies\DocumentPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\UniversityPolicy;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\MenuService::class);
    }

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class       => UserPolicy::class,
        Role::class       => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Menu::class => MenuPolicy::class,
        Slider::class => SliderPolicy::class,
        Page::class => PagePolicy::class,
        DocumentPolicy::class => DocumentPolicy::class,
        UniversityPolicy::class => UniversityPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Below are settings adapted from https://planetscale.com/blog/laravels-safety-mechanisms
        // As these are concerned with application correctness,
        // leave them enabled all the time.
        Model::preventAccessingMissingAttributes();
        Model::preventSilentlyDiscardingAttributes();

        DB::prohibitDestructiveCommands($this->app->isProduction());

        // Set default timezone to Europe/Copenhagen
        date_default_timezone_set(config('app.timezone'));

        // Use immutable dates
        Date::use(CarbonImmutable::class);

        // // Since this is a performance concern only, don't halt
        // // production for violations.
        // Model::preventLazyLoading(! $this->app->isProduction());

        // Option 1: Disable lazy loading only in production
        if ($this->app->environment('production')) {
            Model::preventLazyLoading();
        }

        // Option 2: Allow lazy loading but log violations (recommended for debugging)
        Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            if (app()->environment('local')) {
                logger()->warning("Lazy loading violation: {$relation} on " . get_class($model));
            }
        });

        // Option 3: Prevent lazy loading everywhere except specific cases
        Model::preventLazyLoading(! app()->isProduction());

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });


        ##### Slides #####
        // Define additional Gates if needed
        Gate::define('manage-all-sliders', function ($user) {
            return $user->hasRole('Super Admin') ||
                $user->hasPermission('manage-sliders');
        });

        Gate::define('access-slider-admin', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator', 'Content Manager', 'Editor']) ||
                $user->hasAnyPermission([
                    'view-slider',
                    'create-slider',
                    'update-slider',
                    'manage-slider'
                ]);
        });

        Gate::define('upload-slider-images', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator', 'Content Manager', 'Editor']) ||
                $user->hasAnyPermission([
                    'create-slider',
                    'update-slider',
                    'manage-slider-images',
                    'manage-slider'
                ]);
        });

        Gate::define('change-slider-visibility', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator']) ||
                $user->hasAnyPermission([
                    'publish-slider',
                    'change-slider-location',
                    'manage-slider'
                ]);
        });

        // Gate for accessing slider analytics
        Gate::define('view-slider-dashboard', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator', 'Content Manager']) ||
                $user->hasAnyPermission([
                    'view-slider-analytics',
                    'view-any-slider-analytics',
                    'manage-slider'
                ]);
        });

        // Gate for bulk operations
        Gate::define('bulk-slider-operations', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator']) ||
                $user->hasAnyPermission([
                    'bulk-update-slider',
                    'bulk-delete-slider',
                    'manage-slider'
                ]);
        });

        // Gate for import/export operations
        Gate::define('slider-import-export', function ($user) {
            return $user->hasRole(['Super Admin', 'Administrator']) ||
                $user->hasAnyPermission([
                    'import-slider',
                    'export-slider',
                    'manage-slider'
                ]);
        });
    }
}
