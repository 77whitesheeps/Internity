
@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="container-fluid min-vh-100">
    <div class="row min-vh-100">

        <!-- Left Branding Section -->
        <div class="col-lg-6 d-none d-lg-flex bg-internity-dark text-white align-items-center justify-content-center p-5">

            <div class="w-100" style="max-width: 480px;">

                <div class="mb-5">
                    <h2 class="fw-bold">
                        <i class="bi bi-hexagon-fill text-internity me-2"></i>
                        Internity
                    </h2>
                    <p class="text-white-50">
                        Internship Management System
                    </p>
                </div>

                <h1 class="display-5 fw-bold mb-4">
                    Your journey towards professional growth starts here.
                </h1>

                <p class="text-white-50 fs-5">
                    Manage your internship, monitor your progress, and
                    stay connected with your academic and industry
                    supervisors.
                </p>

                <div class="mt-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-check-circle-fill text-internity fs-4"></i>
                        <span>Track your internship progress</span>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-check-circle-fill text-internity fs-4"></i>
                        <span>Manage internship requirements</span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill text-internity fs-4"></i>
                        <span>Connect with your supervisors</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Login Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5">

            <div class="w-100" style="max-width: 440px;">

                <!-- Mobile Branding -->
                <div class="d-lg-none text-center mb-5">
                    <h2 class="fw-bold">
                        <i class="bi bi-hexagon-fill text-internity me-2"></i>
                        Internity
                    </h2>
                    <p class="text-muted-custom">
                        Internship Management System
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>
                    </div>
                @endif

                <!-- General Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <h2 class="fw-bold mb-2">Welcome back!</h2>
                    <p class="text-muted-custom">
                        Sign in to access your Internity account.
                    </p>
                </div>

                <!-- Functional Login Form -->
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Email Address
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>

                            <input
                                type="email"
                                class="form-control py-2 @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="Enter your email address"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label fw-semibold">
                                Password
                            </label>

                            <a href="{{ route('password.request') }}"
                               class="small text-internity">
                                Forgot password?
                            </a>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-lock text-muted"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control py-2"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-4">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >

                        <label class="form-check-label text-muted-custom"
                               for="remember">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="btn btn-internity w-100 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>

                </form>

                <!-- Registration Link -->
                <div class="text-center mt-4">
                    <p class="text-muted-custom">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                           class="text-internity fw-semibold">
                            Register here
                        </a>
                    </p>
                </div>

                <!-- Footer -->
                <div class="text-center mt-5">
                    <small class="text-muted-custom">
                        &copy; {{ date('Y') }} Internity.
                        All rights reserved.
                    </small>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
