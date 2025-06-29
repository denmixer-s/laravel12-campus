# Organization Management Components Summary
## Laravel 12 + Livewire 3.6 + Tailwind 4.1

---

## 1. คำสั่ง Laravel สำหรับสร้าง Organization Components

### University Management

### Layout BACKEND

```bash
#[Layout('components.layouts.dashboard')]
```

```bash
# Universities
php artisan make:livewire Backend/Organization/UniversitiesList
php artisan make:livewire Backend/Organization/UniversitiesForm
php artisan make:livewire Backend/Organization/UniversitiesShow
```

### Faculty Management
```bash
# Faculties
php artisan make:livewire Backend/Organization/FacultiesList
php artisan make:livewire Backend/Organization/FacultiesForm
php artisan make:livewire Backend/Organization/FacultiesShow
```

### Division Management
```bash
# Divisions
php artisan make:livewire Backend/Organization/DivisionsList
php artisan make:livewire Backend/Organization/DivisionsForm
php artisan make:livewire Backend/Organization/DivisionsShow
```

### Department Management
```bash
# Departments
php artisan make:livewire Backend/Organization/DepartmentsList
php artisan make:livewire Backend/Organization/DepartmentsForm
php artisan make:livewire Backend/Organization/DepartmentsShow
```
### Routes structure
```bash
###
<?php

use App\Livewire\Backend\Organization\{
    UniversitiesList,
    UniversitiesForm,
    UniversitiesShow,
    FacultiesList,
    FacultiesForm,
    FacultiesShow,
    DivisionsList,
    DivisionsForm,
    DivisionsShow,
    DepartmentsList,
    DepartmentsForm,
    DepartmentsShow,
    UsersList,
    UsersForm,
    UsersShow
};

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
            Route::get('/{department}', DepartmentsShow::class)->name('show');
        });
    });
});

// Example Usage of Route Names:
/*
administrator.dashboard
administrator.organization.universities.index
administrator.organization.universities.create
administrator.organization.universities.edit
administrator.organization.universities.show

administrator.organization.faculties.index
administrator.organization.faculties.create
administrator.organization.faculties.edit
administrator.organization.faculties.show

administrator.organization.divisions.index
administrator.organization.divisions.create
administrator.organization.divisions.edit
administrator.organization.divisions.show

administrator.organization.departments.index
administrator.organization.departments.create
administrator.organization.departments.edit
administrator.organization.departments.show
*/
```
