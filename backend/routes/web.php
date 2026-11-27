<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
| Accessible only to unauthenticated users. POST endpoints are throttled
| per IP to protect against brute-force and spam attempts.
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    // Registration
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('register.store');

    // Password recovery
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('throttle:5,1')
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
        $route = auth()->user()->dashboardRoute();

        abort_unless($route, 403, 'Your account does not have a valid system role.');

        return redirect()->route($route);
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Administrator
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:administrator')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // Faculty Advisor
    Route::prefix('faculty')
        ->name('faculty.')
        ->middleware('role:faculty_advisor')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboards.faculty');
            })->name('dashboard');
        });

    // Industry Mentor
    Route::prefix('mentor')
        ->name('mentor.')
        ->middleware('role:industry_mentor')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboards.mentor');
            })->name('dashboard');
        });

    // Student
    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboards.student');
            })->name('dashboard');
        });

});
