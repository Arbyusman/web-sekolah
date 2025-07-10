<?php

use App\Http\Controllers\ActivityCategoryController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\SettingController;
use App\Models\ActivityCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::prefix('settings')
            ->name('settings.')->group(function () {
                Route::resource('', SettingController::class)->parameter('', 'setting')->only(['index', 'update']);
            });

        Route::controller(MajorController::class)->group(function () {
            Route::get('majors/table', 'table')->name('majors.table');
            Route::resource('majors', MajorController::class)->except(['create', 'edit']);
        });

        Route::prefix('activity')
            ->name('activity.')
            ->group(function () {
                Route::controller(ActivityCategoryController::class)
                    ->group(function () {
                        Route::get('categories/table', 'table')->name('categories.table');
                        Route::resource('categories', ActivityCategoryController::class)->except(['create', 'edit'])->parameters(['categories' => 'activityCategory']);
                    });
            });
    });
