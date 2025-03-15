<?php


use Botble\Applicant\Http\Controllers\ApplicantController;
use Botble\Base\Facades\AdminHelper;
use Botble\Program\Models\Activity;
use Illuminate\Support\Facades\Route;

// Frontend
/*Route::group(['prefix' => 'applicants'], function () {
    Route::get('/register', [ApplicantController::class, 'create'])->name('applicants.create');
    Route::post('/register', [ApplicantController::class, 'store'])->name('applicants.store');
});*/
Route::group(['namespace' => 'Botble\Applicant\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::get('applicant/register', 'ApplicantController@create')->name('applicant.register');
    Route::post('applicant/register', 'ApplicantController@store')->name('applicant.register.store');
});


AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Applicant\Http\Controllers'], function (): void {

        Route::group(['prefix' => 'applicants', 'as' => 'applicants.'], function (): void {
            Route::resource('', 'ApplicantController')->parameters(['' => 'applicant']);
        });

        /*Route::group([
            'prefix' => 'applicants',
            'as' => 'applicants.',
            'middleware' => ['web', 'auth'],
        ], function () {
            Route::get('/', [ApplicantController::class, 'index'])->name('index');
            Route::get('/create', [ApplicantController::class, 'create'])->name('create');
            Route::post('/', [ApplicantController::class, 'store'])->name('store');
            Route::get('/{applicant}/edit', [ApplicantController::class, 'edit'])->name('edit');
            Route::put('/{applicant}', [ApplicantController::class, 'update'])->name('update');
            Route::delete('/{applicant}', [ApplicantController::class, 'destroy'])->name('destroy');
            // Custom routes
            Route::post('/{applicant}/assign-program', [ApplicantController::class, 'assignProgram'])->name('assign-program');
        });*/
    });

    Route::get('applicants/get-activities', [
        'as' => 'applicants.get-activities',
        'uses' => 'Botble\Applicant\Http\Controllers\ApplicantController@getActivities',
        'permission' => 'applicants.edit',
    ]);

    Route::get('/programs/activities-by-program/{programId}', function ($programId) {
        try {
            $activities = Activity::where('program_id', $programId)
                ->wherePublished()
                ->pluck('title', 'id')
                ->toArray();

            return response()->json([
                'data' => $activities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load activities'
            ], 500);
        }
    })->name('programs.activities-by-program');

});

// Admin
/*Route::group([
    'prefix' => 'admin/applicants',
    'as' => 'admin.applicants.',
    'middleware' => ['web', 'auth'],
], function () {
    // Add this line for the index route
    Route::get('/', [ApplicantController::class, 'index'])->name('index');

    // Your existing routes
    Route::post('/{applicant}/assign-program', [ApplicantController::class, 'assignProgram'])->name('assign-program');
    // ... other routes
});*/

