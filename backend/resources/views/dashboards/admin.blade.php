@extends('layouts.app')

@section('title', 'Administrator Dashboard')

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">Administrator Dashboard</h4>
        <p class="text-secondary mb-0 small">
            Welcome back, {{ auth()->user()->name }}. Here's your system overview.
        </p>
    </div>
@endsection

@section('content')

    {{-- Dashboard Overview --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">System Overview</h5>
        <p class="text-secondary small mb-0">
            Monitor users, organizations, internships, and capstone activities.
        </p>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Total Students</span>
                        <i class="bi bi-people-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Registered student accounts</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Faculty Advisors</span>
                        <i class="bi bi-person-workspace fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Registered faculty accounts</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Industry Mentors</span>
                        <i class="bi bi-person-badge-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Registered mentor accounts</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Partner Companies</span>
                        <i class="bi bi-buildings-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Registered organizations</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Active Internships</span>
                        <i class="bi bi-briefcase-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Currently ongoing placements</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small fw-semibold">Capstone Projects</span>
                        <i class="bi bi-kanban-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Registered capstone projects</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Management Overview --}}
    <div class="row g-3">

        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Recent Registrations</h5>
                    
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="fw-bold mb-0">Recent Registrations</h5>
                                <span class="badge bg-warning text-dark">Latest 5</span>
                            </div>

                            <p class="text-secondary small mb-4">
                                Recently registered users across the system.
                            </p>

                            @forelse ($recentUsers as $registeredUser)
                                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-warning bg-opacity-25 d-flex align-items-center justify-content-center"
                                            style="width: 42px; height: 42px; min-width: 42px;">
                                            <i class="bi bi-person-fill text-dark"></i>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $registeredUser->name }}
                                            </div>

                                            <div class="text-secondary small">
                                                {{ $registeredUser->email }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <span class="badge bg-secondary">
                                            {{ ucwords(str_replace('_', ' ', $registeredUser->role->name)) }}
                                        </span>

                                        <div class="text-secondary small mt-1">
                                            {{ $registeredUser->created_at->format('M d, Y') }}
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-people fs-1 text-secondary"></i>
                                    <p class="text-secondary mt-3 mb-0">
                                        No registered users found.
                                    </p>
                                </div>
                            @endforelse
                        </div>


                    <div class="text-center py-5">
                        <i class="bi bi-person-lines-fill fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Registration records will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Administrative Activities</h5>
                    <p class="text-secondary small mb-4">
                        Important system activities and pending actions.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-check fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Administrative activities will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
