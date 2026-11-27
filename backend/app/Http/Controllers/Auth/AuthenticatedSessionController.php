<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        // Normalize the email address.
        $credentials['email'] = strtolower(trim($credentials['email']));

        // Attempt authentication.
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // Regenerate the session after successful login.
        $request->session()->regenerate();

        // Redirect according to the user's role.
        $route = Auth::user()->dashboardRoute();

        if (! $route) {
            return $this->logoutInvalidRole($request);
        }

        return redirect()->route($route);
    }

    /**
     * Log out the authenticated user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'You have successfully logged out.');
    }

    /**
     * Log out users with unsupported roles.
     */
    private function logoutInvalidRole(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Your account does not have a valid system role.',
            ]);
    }
}
