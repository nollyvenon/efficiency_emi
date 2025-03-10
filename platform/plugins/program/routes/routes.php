<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Program\Http\Controllers\ProgramController;

AdminHelper::registerRoutes(function () {
    Route::group(['prefix' => 'programs', 'as' => 'admin.programs.'], function () {
        Route::resource('', ProgramController::class)->parameters(['' => 'program']);
    });
});
