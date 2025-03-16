<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\BaseHelper;
use Botble\Event\Http\Controllers\EventController;
use Botble\Event\Http\Controllers\EventRegistrationController;
use Botble\Event\Models\Event;
use Illuminate\Support\Facades\Route;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;

/*AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'events', 'as' => 'event.'], function () {
        Route::resource('', EventController::class)->parameters(['' => 'event']);
    });
});*/

Route::group(['namespace' => 'Botble\Event\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function (): void {
        Route::group(['prefix' => 'events', 'as' => 'event.'], function (): void {
            Route::resource('', 'EventController')->parameters(['' => 'event']);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {

        Theme::registerRoutes(function (): void {
            Route::get(SlugHelper::getPrefix(Event::class, 'events') ?: 'events', 'PublicController@events')
                ->name('public.events');

            Route::get('/event/{id}', [EventController::class, 'show'])->name('public.events.show'); // Show event details
        });

        Route::post('/register/{id}', [EventRegistrationController::class, 'store'])->name('public.events.register'); // Register for event

    }
});

