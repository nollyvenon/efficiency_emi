<?php
use Botble\Base\Facades\AdminHelper;
use Botble\Program\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Program\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'program', 'as' => 'program.'], function (): void {
            Route::resource('', 'ProgramController')->parameters(['' => 'program']);
        });

        /*Route::group(['prefix' => 'admin/programs/{program}/activities', 'as' => 'admin.programs.activities.'], function (): void {
            Route::resource('', 'ActivityController')->parameters(['' => 'activity']);
        });*/

        /*Route::resource('programs.activities', ActivityController::class)
            ->shallow()
            ->except(['index']);*/


        Route::group([
            'prefix' => 'programs/{program}/activities',
            'as' => 'admin.programs.activities.',
           // 'middleware' => ['web', 'auth'],
        ], function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/create', [ActivityController::class, 'create'])->name('create');
            Route::post('/', [ActivityController::class, 'store'])->name('store');
            Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
            Route::put('/{activity}', [ActivityController::class, 'update'])->name('update');
            Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
        });
    });

});

/*
use Botble\Base\Facades\AdminHelper;
use Botble\Portfolio\Http\Controllers\CustomFieldController;
use Botble\Program\Http\Controllers\ActivityController;
use Botble\Program\Http\Controllers\ProgramController;
use Botble\Theme\Facades\Theme;
use Botble\Program\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use Botble\Slug\Facades\SlugHelper;

AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'program', 'as' => 'admin.program.'], function () {
        Route::get('/', [ProgramController::class, 'index'])->name('index');
    });
});

// Admin routes (protected)
Route::group([
    'prefix' => 'admin/programs',
    'middleware' => ['web', 'auth'],
    'as' => 'admin.programs.'
], function () {
    // Program routes (no parameter needed for index/create/store)
    Route::get('/', [ProgramController::class, 'index'])->name('index');
    Route::get('/create', [ProgramController::class, 'create'])->name('create');
    Route::post('/', [ProgramController::class, 'store'])->name('store');

    // Program routes requiring program parameter
    Route::group(['prefix' => '{program}'], function () {
        Route::get('/', [ProgramController::class, 'show'])->name('show');
        Route::get('/edit', [ProgramController::class, 'edit'])->name('edit');
        Route::put('/', [ProgramController::class, 'update'])->name('update');
        Route::delete('/', [ProgramController::class, 'destroy'])->name('destroy');

        // Nested activity routes
        Route::group(['prefix' => 'activities', 'as' => 'activities.'], function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/create', [ActivityController::class, 'create'])->name('create');
            Route::post('/', [ActivityController::class, 'store'])->name('store');
            Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
            Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
            Route::put('/{activity}', [ActivityController::class, 'update'])->name('update');
            Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
        });
    });
});

// Public routes
Route::group([
    'prefix' => 'programs',
    'as' => 'public.programs.'
], function () {
    Route::get('/', [PublicController::class, 'index'])->name('index');
    Route::get('/{slug}', [PublicController::class, 'show'])->name('show');

    // Activity routes
    Route::group(['prefix' => '{program}/activities'], function () {
        Route::get('calendar', [PublicController::class, 'calendar'])->name('calendar');
        Route::post('{activity}/register', [PublicController::class, 'register'])->name('register');
        Route::delete('{activity}/unregister', [PublicController::class, 'unregister'])->name('unregister');
    });
});
*/
