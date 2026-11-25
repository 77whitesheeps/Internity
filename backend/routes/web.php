<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/frontend-test', function () {
    return view('layouts.test');
})->name('frontend.test');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
| Accessible only to users who are not authenticated.
| Authentication logic will be implemented in a later task.
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    // Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    // Password recovery
    // Display the forgot-password form
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Send the password reset link
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Display the new password form
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Process the new password
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| Accessible only to logged-in users.
*/

Route::middleware('auth')->group(function () {

    // Role-based dashboard redirection
    Route::get('/dashboard', function () {

        $role = auth()->user()->role->name;

        return match ($role) {
            'administrator' => redirect()->route('admin.dashboard'),
            'faculty_advisor' => redirect()->route('faculty.dashboard'),
            'industry_mentor' => redirect()->route('mentor.dashboard'),
            'student' => redirect()->route('student.dashboard'),

            default => abort(403, 'Your account does not have a valid system role.'),
        };

    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Administrator
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:administrator')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Faculty Advisor
    |--------------------------------------------------------------------------
    */

    Route::prefix('faculty')
        ->name('faculty.')
        ->middleware('role:faculty_advisor')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboards.faculty');
            })->name('dashboard');

        });


    /*
    |--------------------------------------------------------------------------
    | Industry Mentor
    |--------------------------------------------------------------------------
    */


    Route::prefix('mentor')
        ->name('mentor.')
        ->middleware('role:industry_mentor')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboards.mentor');
            })->name('dashboard');

        });


    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('dashboards.student');
            })->name('dashboard');

        });

});