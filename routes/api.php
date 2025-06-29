<?php

use App\Http\Controllers\Api\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 🌍 Translation API Routes
Route::prefix('translate')->group(function () {
    Route::get('/test', [TranslationController::class, 'test'])->name('translate.test');
    Route::get('/test-curl', [TranslationController::class, 'testCurl'])->name('translate.test-curl');
    Route::get('/usage', [TranslationController::class, 'usage'])->name('translate.usage');
});

// 🚀 Main Translation Routes
Route::post('/translate-batch', [TranslationController::class, 'batchTranslate'])
     ->name('translate.batch');

// 🧪 Simple Test Route
Route::get('/translate-simple', function () {
    return response()->json([
        'message' => 'Translation API endpoint is working',
        'timestamp' => now(),
        'routes_loaded' => true,
    ]);
});
