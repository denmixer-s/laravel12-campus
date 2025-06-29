<?php

use App\Http\Controllers\Settings;
use App\Livewire\Frontend\Document\CategoriesShow;
use App\Livewire\Frontend\Document\DocumentSearch;
use App\Livewire\Frontend\Document\DocumentShow;
use App\Livewire\Frontend\Document\FrontDocumentList;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/administrator.php';

// Blog Routes
require __DIR__ . '/blog-backend.php';
require __DIR__ . '/blog-frontend.php';
//require __DIR__.'/blog-feeds.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/accordion', function () {
    return view('accordion-icon');
})->name('accordion');



Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

// Documents Search - ต้องอยู่ก่อน documents/{document}
Route::get('/documents/search', DocumentSearch::class)->name('documents.search');

// Documents Main
Route::get('/documents', FrontDocumentList::class)->name('documents.index');

// Documents Categories
Route::get('/documents/categories/{category}', CategoriesShow::class)->name('documents.categories.show');

// Document Detail - ต้องอยู่หลังสุด
Route::get('/documents/{document}', DocumentShow::class)
    ->name('documents.show')
    ->where('document', '[0-9]+'); // รับเฉพาะตัวเลข

// Categories (Alternative path)
//Route::get('/categories/{category}', CategoriesShow::class)->name('categories.show');

