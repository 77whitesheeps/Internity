
@extends('layouts.guest')

@section('title', 'Student Dashboard')

@section('content')
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Student Dashboard</h2>
            <p class="text-muted-custom mb-0">
                Welcome to your Internity account.
            </p>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right me-2"></i>
                Sign Out
            </button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-semibold">Authentication Test</h5>

            <p class="text-muted-custom mb-0">
                You have successfully accessed the student dashboard.
                Your account is authenticated and has the student role.
            </p>
        </div>
    </div>

</div>
@endsection
