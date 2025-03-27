<?php
use Botble\Base\Facades\AdminHelper;
use Botble\Program\Http\Controllers\ActivityController;
use Botble\Program\Http\Controllers\ProgramController;
use Botble\Program\Http\Controllers\PublicController;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Facades\Route;
use Botble\Theme\Facades\Theme;

AdminHelper::registerRoutes(function (): void {

    Route::group(['namespace' => 'Botble\Program\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'program', 'as' => 'program.'], function (): void {
            Route::resource('', 'ProgramController')->parameters(['' => 'program']);
        });

        // Activity routes
        Route::group(['prefix' => 'programs/{program}/activities', 'as' => 'admin.programs.activities.'], function (): void {
            Route::resource('', 'ActivityController')->parameters(['' => 'activity']);
        });

        Route::get('programs/{program}/activities/{activity}/edit', [ActivityController::class, 'edit'])
            ->name('admin.programs.activities.edit');

        Route::post('programs/{program}/activities/{activity}/store', [ActivityController::class, 'store'])
            ->name('admin.programs.activities.store');

        Route::post('/programs/{program}/activities/{activity}/update', [ActivityController::class, 'update'])
            ->name('admin.programs.activities.update');


    });


});

Route::group(['namespace' => 'Botble\Program\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    if (defined('THEME_MODULE_SCREEN_NAME')) {

        Theme::registerRoutes(function (): void {
            Route::get(SlugHelper::getPrefix(ProgramController::class, 'programs') ?: 'programs', 'PublicController@programs')
                ->name('public.programs');

            Route::get('/program/{id}', [PublicController::class, 'show'])->name('public.programs.show'); // Show program details

            Route::group(['prefix' => 'programs/{program}/activities', 'as' => 'public.programs.activities.'], function (): void {
                Route::resource('', 'ActivityController')->parameters(['' => 'activity']);
            });

            Route::get('programs/{program}/activities/{activity}/edit', [ActivityController::class, 'edit'])
                ->name('public.programs.activities.edit');

            Route::post('/programs/{program}/activities/{activity}/update', [ActivityController::class, 'update'])
                ->name('public.programs.activities.update');
        });



    }
});

