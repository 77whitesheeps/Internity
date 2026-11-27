<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Supervisor\PlacementManagementController;
use App\Http\Controllers\Supervisor\StudentManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
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
            Route::get('/dashboard', [FacultyDashboardController::class, 'index'])
                ->name('dashboard');

            // Module 1: Student Management & Assignment
            Route::get('/students', [StudentManagementController::class, 'index'])
                ->name('students.index');
            Route::get('/students/search', [StudentManagementController::class, 'search'])
                ->name('students.search');
            Route::get('/students/{student}', [StudentManagementController::class, 'show'])
                ->name('students.show');

            // Internship Placements & Supervisor Assignments
            Route::get('/placements', [PlacementManagementController::class, 'index'])
                ->name('placements.index');
            Route::post('/placements', [PlacementManagementController::class, 'store'])
                ->name('placements.store');
            Route::patch('/placements/{placement}/status', [PlacementManagementController::class, 'updateStatus'])
                ->name('placements.status');
            Route::put('/placements/{placement}/assign', [PlacementManagementController::class, 'assignSupervisors'])
                ->name('placements.assign');
        });

    // Industry Mentor
    Route::prefix('mentor')
        ->name('mentor.')
        ->middleware('role:industry_mentor')
        ->group(function () {
            Route::get('/dashboard', [MentorDashboardController::class, 'index'])
                ->name('dashboard');

            // Module 1: Assigned Intern Management (read-only on placements)
            Route::get('/interns', [StudentManagementController::class, 'index'])
                ->name('interns.index');
            Route::get('/interns/search', [StudentManagementController::class, 'search'])
                ->name('interns.search');
            Route::get('/interns/{student}', [StudentManagementController::class, 'show'])
                ->name('interns.show');
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
