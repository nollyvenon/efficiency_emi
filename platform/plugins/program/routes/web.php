<?php
use Botble\Base\Facades\AdminHelper;
use Botble\Program\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {

    Route::group(['namespace' => 'Botble\Program\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'program', 'as' => 'program.'], function (): void {
            Route::resource('', 'ProgramController')->parameters(['' => 'program']);
        });

        Route::group(['prefix' => 'programs/{program}/activities', 'as' => 'admin.programs.activities.'], function (): void {
            Route::resource('', 'ActivityController')->parameters(['' => 'activity']);
        });

        Route::get('programs/{program}/activities/{activity}/edit', [ActivityController::class, 'edit'])
            ->name('admin.programs.activities.edit');

        Route::post('/programs/{program}/activities/{activity}/update', [ActivityController::class, 'update'])
            ->name('admin.programs.activities.update');


        /*Route::get('programs/{program}/activities/edit/{activity}', [ActivityController::class, 'edit'])
            ->name('admin.programs.activities.edit');
        Route::group(['prefix' => 'programs/{program}/activities', 'as' => 'admin.programs.activities.'], function () {
            // Add this line for the update route
            Route::put('{activity}', [ActivityController::class, 'update'])->name('update');

            // Keep existing routes
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/create', [ActivityController::class, 'create'])->name('create');
            Route::post('/', [ActivityController::class, 'store'])->name('store');
            Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
            Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
        });*/
    });


});

