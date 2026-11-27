
@extends('layouts.app')

@section('title', 'Industry Mentor Dashboard')

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">Industry Mentor Dashboard</h4>
        <p class="text-secondary mb-0 small">
            Welcome back, {{ auth()->user()->name }}. Here's your internship overview.
        </p>
    </div>
@endsection

@section('content')

    {{-- Dashboard Overview --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Internship Overview</h5>
        <p class="text-secondary small mb-0">
            Monitor your interns, review their submissions, and track their workplace performance.
        </p>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">My Interns</span>
                        <i class="bi bi-people-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Students assigned to you</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Active Interns</span>
                        <i class="bi bi-briefcase-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Currently undergoing OJT</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Pending Reviews</span>
                        <i class="bi bi-clipboard-check-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Submissions awaiting review</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Pending Evaluations</span>
                        <i class="bi bi-star-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Interns awaiting evaluation</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Intern Management --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0">My Interns</h5>
                        <span class="badge bg-warning text-dark">Overview</span>
                    </div>

                    <p class="text-secondary small mb-4">
                        Students currently assigned to your supervision.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Your assigned interns will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Intern Progress</h5>
                    <p class="text-secondary small mb-4">
                        Monitor the progress of your assigned interns.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-graph-up-arrow fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Internship progress records will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Attendance and Accomplishment Reviews --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Attendance Approvals</h5>
                    <p class="text-secondary small mb-4">
                        Review and approve submitted intern attendance records.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-calendar-check fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Attendance submissions will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Daily Accomplishment Logs</h5>
                    <p class="text-secondary small mb-4">
                        Review the daily activities and accomplishments of your interns.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-journal-text fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Daily accomplishment submissions will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Weekly Reports and Evaluations --}}
    <div class="row g-3">

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Weekly Reports</h5>
                    <p class="text-secondary small mb-4">
                        Review weekly internship summaries submitted by your interns.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-text fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Weekly reports will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Performance Evaluations</h5>
                    <p class="text-secondary small mb-4">
                        Evaluate intern performance and workplace competencies.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-star-half fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Performance evaluation records will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
