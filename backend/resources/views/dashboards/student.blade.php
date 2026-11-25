
@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('page-heading', 'Student Dashboard')

@section('content')

    <!-- Welcome section -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            Welcome back, {{ auth()->user()->name }}!
        </h3>

        <p class="text-muted mb-0">
            Here's your internship workspace.
        </p>
    </div>

    <!-- Overview cards -->
    <div class="row g-4">

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">
                            Internship Status
                        </span>

                        <i class="bi bi-briefcase fs-4"
                           style="color: #FAC05E;"></i>
                    </div>

                    <h4 class="fw-bold mb-1">Not Started</h4>

                    <small class="text-muted">
                        Your internship progress will appear here.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">
                            Required Hours
                        </span>

                        <i class="bi bi-clock-history fs-4"
                           style="color: #4DAA57;"></i>
                    </div>

                    <h4 class="fw-bold mb-1">0 Hours</h4>

                    <small class="text-muted">
                        Your accumulated internship hours will appear here.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">
                            Assigned Supervisors
                        </span>

                        <i class="bi bi-people fs-4"
                           style="color: #FAC05E;"></i>
                    </div>

                    <h4 class="fw-bold mb-1">0 Assigned</h4>

                    <small class="text-muted">
                        Your faculty advisor and industry mentor will appear here.
                    </small>
                </div>
            </div>
        </div>

    </div>

    <!-- Main dashboard sections -->
    <div class="row g-4 mt-1">

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Internship Overview
                    </h5>

                    <p class="text-muted mb-0">
                        Your placement details, internship progress,
                        and required documents will be displayed here.
                    </p>

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Recent Activities
                    </h5>

                    <p class="text-muted mb-0">
                        Updates and notifications will appear here.
                    </p>

                </div>
            </div>
        </div>

    </div>

@endsection
