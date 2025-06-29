<?php

// routes/blog-backend.php - Backend Blog Routes

use App\Livewire\Backend\Organization\DepartmentsForm;

use App\Livewire\Backend\Organization\DepartmentsList;

use App\Livewire\Backend\Organization\DivisionsForm;

use App\Livewire\Backend\Organization\DivisionsList;

use App\Livewire\Backend\Organization\DivisionsShow;

use App\Livewire\Backend\Organization\FacultiesForm;

use App\Livewire\Backend\Organization\FacultiesList;

use App\Livewire\Backend\Organization\FacultiesShow;

use App\Livewire\Backend\Organization\UniversitiesForm;

use App\Livewire\Backend\Organization\UniversitiesList;

use App\Livewire\Backend\Organization\UniversitiesShow;
use App\Livewire\Sakon\Dashboard;
use App\Livewire\Sakon\Menus\CreateMenu;
use App\Livewire\Sakon\Menus\EditMenu;
use App\Livewire\Sakon\Menus\ListMenu;
use App\Livewire\Sakon\Menus\ShowMenu;
use App\Livewire\Sakon\Permissions\CreatePermission;
use App\Livewire\Sakon\Permissions\EditPermission;
use App\Livewire\Sakon\Permissions\ListPermission;
use App\Livewire\Sakon\Permissions\ShowPermission;
use App\Livewire\Sakon\Roles\CreateRole;use App\Livewire\Sakon\Roles\EditRole;use App\Livewire\Sakon\Roles\ListRole;use App\Livewire\Sakon\Roles\ShowRole;use App\Livewire\Sakon\Sliders\CreateSlider;use App\Livewire\Sakon\Sliders\EditSlider;use App\Livewire\Sakon\Sliders\ListSlider;use App\Livewire\Sakon\Sliders\ShowSlider;use App\Livewire\Sakon\Users\CreateUser;use App\Livewire\Sakon\Users\EditUser;use App\Livewire\Sakon\Users\ListUser;use App\Livewire\Sakon\Users\ShowUser;use Illuminate\Support\Facades\Route;

// Administrator
Route::prefix('administrator')->as('administrator.')->group(function (): void {

    Route::get('/', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard')->middleware('can:dashboard');

    Route::get('/users', ListUser::class)->name('users.index')
        ->middleware('permission:view-user|create-user|edit-user|delete-user');

    Route::get('/users/create', CreateUser::class)->name('users.create')
        ->middleware('permission:create-user');

    Route::get('/users/{user}', ShowUser::class)->name('users.show')
        ->middleware('permission:view-user');

    Route::get('/users/{user}/edit', EditUser::class)->name('users.edit')
        ->middleware('permission:edit-user');

    Route::get('/roles', ListRole::class)->name('roles.index')
        ->middleware('permission:view-role|create-role|edit-role|delete-role');

    Route::get('/roles/create', CreateRole::class)->name('roles.create')
        ->middleware('permission:create-role');

    Route::get('/roles/{role}', ShowRole::class)->name('roles.show')
        ->middleware('permission:view-role');

    Route::get('/roles/{role}/edit', EditRole::class)->name('roles.edit')
        ->middleware('permission:edit-role');

    Route::get('/permissions', ListPermission::class)->name('permissions.index');
    Route::get('/permissions/create', CreatePermission::class)->name('permissions.create');
    Route::get('/permissions/{permission}', ShowPermission::class)->name('permissions.show');
    Route::get('permissions/{permission}/edit', EditPermission::class)->name('permissions.edit');

    // Menu
    Route::get('/menus', ListMenu::class)->name('menus.index')->middleware('can:menus_view');
    Route::get('/menus/create', CreateMenu::class)->name('menus.create')->middleware('can:menus_create');
    Route::get('/menus/{menu}/edit', EditMenu::class)->name('menus.edit')->middleware('can:menus_update');
    Route::get('/menus/{menu}', ShowMenu::class)->name('menus.show')->middleware('can:menus_view');

    Route::get('/sliders', ListSlider::class)->name('sliders.index');
    Route::get('/sliders/create', CreateSlider::class)->name('sliders.create');
    Route::get('/sliders/{slider}', ShowSlider::class)->name('sliders.show');
    Route::get('/sliders/{slider}/edit', EditSlider::class)->name('sliders.edit');

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', App\Livewire\Sakon\Pages\ListPage::class)->name('index');
        Route::get('/create', App\Livewire\Sakon\Pages\CreatePage::class)->name('create');
        Route::get('/{page}/edit', App\Livewire\Sakon\Pages\EditPage::class)->name('edit');
        Route::get('/{page}', App\Livewire\Sakon\Pages\ShowPage::class)->name('show');
    });

});

Route::prefix('administrator')->group(function () {

    Route::get('/documents/statistics', \App\Livewire\Backend\Document\Statistics::class)
        ->name('administrator.documents.statistics');

    Route::get('/documents/categories', \App\Livewire\Backend\Document\CategoriesList::class)
        ->name('administrator.documents.categories');

    Route::get('/documents/types', \App\Livewire\Backend\Document\TypesList::class)
        ->name('administrator.documents.types');

    Route::get('/documents', \App\Livewire\Backend\Document\DocumentList::class)
        ->name('administrator.documents.index');

    Route::get('/documents/create', \App\Livewire\Backend\Document\DocumentCreate::class)
        ->name('administrator.documents.create');

    Route::get('/documents/trash', \App\Livewire\Backend\Document\DocumentsTrash::class)
        ->name('administrator.documents.trash');

    Route::get('documents/{document}/edit', \App\Livewire\Backend\Document\DocumentEdit::class)
        ->name('administrator.documents.edit');

});

Route::middleware(['auth', 'verified'])->prefix('administrator')->as('administrator.')->group(function () {

    // Organization Management
    Route::prefix('organization')->as('organization.')->group(function () {

        // Universities Routes
        Route::prefix('universities')->as('universities.')->group(function () {
            Route::get('/', UniversitiesList::class)->name('index');
            Route::get('/create', UniversitiesForm::class)->name('create');
            Route::get('/{university}/edit', UniversitiesForm::class)->name('edit');
            Route::get('/{university}', UniversitiesShow::class)->name('show');
        });

        // Faculties Routes
        Route::prefix('faculties')->as('faculties.')->group(function () {
            Route::get('/', FacultiesList::class)->name('index');
            Route::get('/create', FacultiesForm::class)->name('create');
            Route::get('/{faculty}/edit', FacultiesForm::class)->name('edit');
            Route::get('/{faculty}', FacultiesShow::class)->name('show');
        });

        // Divisions Routes
        Route::prefix('divisions')->as('divisions.')->group(function () {
            Route::get('/', DivisionsList::class)->name('index');
            Route::get('/create', DivisionsForm::class)->name('create');
            Route::get('/{division}/edit', DivisionsForm::class)->name('edit');
            Route::get('/{division}', DivisionsShow::class)->name('show');
        });

        // Departments Routes
        Route::prefix('departments')->as('departments.')->group(function () {
            Route::get('/', DepartmentsList::class)->name('index');
            Route::get('/create', DepartmentsForm::class)->name('create');
            Route::get('/{department}/edit', DepartmentsForm::class)->name('edit');
        });
    });
});
