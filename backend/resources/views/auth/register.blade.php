
@extends('layouts.guest')

@section('title', 'Student Registration')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <!-- Branding -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">
                    <i class="bi bi-hexagon-fill text-internity me-2"></i>
                    Internity
                </h2>

                <p class="text-muted-custom">
                    Automated Internship & Capstone Tracking System
                </p>
            </div>

            <!-- Registration Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="mb-4">
                        <h3 class="fw-bold mb-2">Create your account</h3>

                        <p class="text-muted-custom mb-3">
                            Register using your official USM student
                            information to access Internity.
                        </p>

                        <div class="alert alert-info d-flex gap-2 align-items-start">
                            <i class="bi bi-info-circle-fill mt-1"></i>

                            <div>
                                <strong>USM students only</strong>
                                <p class="mb-0 small">
                                    Registration requires an official
                                    institutional email ending in
                                    <strong>@usm.edu.ph</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Form (Interface Only) -->
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <strong>Please review your information.</strong>

                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Personal Information -->
                        <h6 class="fw-bold text-uppercase text-muted mb-3">
                            Personal Information
                        </h6>

                        <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control py-2 @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    placeholder="Enter your full name"
                                    autocomplete="name"
                                    value="{{ old('name') }}"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_number"
                                       class="form-label fw-semibold">
                                    Student Number
                                </label>
                                <input
                                    type="text"
                                    class="form-control py-2 @error('student_number') is-invalid @enderror"
                                    id="student_number"
                                    name="student_number"
                                    placeholder="Enter student number"
                                    value="{{ old('student_number') }}"
                                    required
                                >

                                @error('student_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_number"
                                       class="form-label fw-semibold">
                                    Contact Number
                                </label>

                                <input
                                    type="tel"
                                    class="form-control py-2 @error('contact_number') is-invalid @enderror"
                                    id="contact_number"
                                    name="contact_number"
                                    placeholder="09XXXXXXXXX"
                                    autocomplete="tel"
                                    value="{{ old('contact_number') }}"
                                    required
                                >

                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Academic Information -->
                        <h6 class="fw-bold text-uppercase text-muted mb-3">
                            Academic Information
                        </h6>

                        <div class="mb-3">
                            <label for="program" class="form-label fw-semibold">
                                Academic Program
                            </label>

                            <input
                                type="text"
                                class="form-control py-2 @error('program') is-invalid @enderror"
                                id="program"
                                name="program"
                                placeholder="Enter your academic program"
                                value="{{ old('program') }}"
                                required
                            >
                            @error('program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year_level" class="form-label fw-semibold">
                                Year Level
                            </label>

                            <select
                                class="form-select py-2 @error('year_level') is-invalid @enderror"
                                id="year_level"
                                name="year_level"
                                required
                            >
                                <option value="" disabled {{ old('year_level') ? '' : 'selected' }}>
                                    Select your year level
                                </option>

                                <option value="1" {{ old('year_level') == 1 ? 'selected' : '' }}>
                                    1st Year
                                </option>

                                <option value="2" {{ old('year_level') == 2 ? 'selected' : '' }}>
                                    2nd Year
                                </option>

                                <option value="3" {{ old('year_level') == 3 ? 'selected' : '' }}>
                                    3rd Year
                                </option>

                                <option value="4" {{ old('year_level') == 4 ? 'selected' : '' }}>
                                    4th Year
                                </option>

                                <option value="5" {{ old('year_level') == 5 ? 'selected' : '' }}>
                                    5th Year
                                </option>
                            </select>

                            @error('year_level')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Account Information -->
                        <h6 class="fw-bold text-uppercase text-muted mb-3">
                            Account Information
                        </h6>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                USM Institutional Email
                            </label>

                            <input
                                type="email"
                                class="form-control py-2 @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                placeholder="username@usm.edu.ph"
                                autocomplete="email"
                                pattern="^[^@\s]+@usm\.edu\.ph$"
                                title="Use your official USM institutional email."
                                value="{{ old('email') }}"
                                required
                            >

                            <small class="text-muted-custom">
                                Only @usm.edu.ph email addresses are accepted.
                            </small>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                Password
                            </label>

                            <input
                                type="password"
                                class="form-control py-2 @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="Create a password"
                                autocomplete="new-password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation"
                                   class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                class="form-control py-2"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Re-enter your password"
                                autocomplete="new-password"
                                required
                            >
                        </div>

                        <!-- Terms -->
                        <div class="form-check mb-4">
                            <input
                                class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox"
                                id="terms"
                                name="terms"
                                value="1"
                                {{ old('terms') ? 'checked' : '' }}
                                required
                            >

                            <label class="form-check-label small" for="terms">
                                I confirm that the information provided is
                                accurate and agree to the system's terms
                                and privacy policy.
                            </label>

                            @error('terms')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            class="btn btn-internity w-100 py-2"
                        >
                            <i class="bi bi-person-plus me-2"></i>
                            Create Student Account
                        </button>

                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted-custom mb-0">
                            Already have an account?

                            <a href="{{ route('login') }}"
                               class="text-internity fw-semibold">
                                Sign in
                            </a>
                        </p>
                    </div>

                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted-custom">
                    &copy; {{ date('Y') }} Internity. All rights reserved.
                </small>
            </div>

        </div>
    </div>
</div>
@endsection
