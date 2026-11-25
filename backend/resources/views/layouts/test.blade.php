
@extends('layouts.guest')

@section('title', 'Frontend Test')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">

                    <div class="mb-4">
                        <span class="badge text-bg-success">
                            Frontend Test
                        </span>
                    </div>

                    <h1 class="fw-bold mb-3">
                        Welcome to <span class="text-internity">Internity</span>
                    </h1>

                    <p class="text-muted-custom mb-4">
                        Automated Internship & Capstone Tracking System
                    </p>

                    <hr>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                        <div>
                            <h6 class="mb-0">Bootstrap is working</h6>
                            <small class="text-muted">Layout and components loaded.</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <i class="bi bi-palette-fill text-internity fs-3"></i>
                        <div>
                            <h6 class="mb-0">Custom styling is working</h6>
                            <small class="text-muted">Internity's color variables are available.</small>
                        </div>
                    </div>

                    <button class="btn btn-internity">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Test Button
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
