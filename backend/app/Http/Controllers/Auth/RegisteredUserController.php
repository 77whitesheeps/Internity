<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the student registration page.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Process a new student registration.
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalize the email before validating it.
        $request->merge([
            'email' => strtolower(trim($request->input('email', ''))),
        ]);

        // Validate the submitted information.
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'student_number' => [
                'required',
                'string',
                'max:255',
                'unique:student_profiles,student_number',
            ],

            'contact_number' => [
                'required',
                'string',
                'max:255',
            ],

            'program' => [
                'required',
                'string',
                'max:255',
            ],

            'year_level' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'ends_with:@usm.edu.ph',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],

            'terms' => [
                'accepted',
            ],
        ]);

        // Retrieve the student role from the database.
        $studentRole = Role::where('name', 'student')->firstOrFail();

        // Create the user and student profile together.
        DB::transaction(function () use ($validated, $studentRole) {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role_id' => $studentRole->id,
            ]);

            $user->studentProfile()->create([
                'student_number' => $validated['student_number'],
                'contact_number' => $validated['contact_number'],
                'program' => $validated['program'],
                'year_level' => $validated['year_level'],
            ]);
        });

        // Redirect the student to the login page.
        return redirect()
            ->route('login')
            ->with('status', 'Your student account has been created successfully. You may now sign in.');
    }
}
