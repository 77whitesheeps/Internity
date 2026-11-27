@extends('layouts.app')

@section('title', 'Faculty Advisor Dashboard')

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">Faculty Advisor Dashboard</h4>
        <p class="text-secondary mb-0 small">
            Welcome back, {{ auth()->user()->name }}. Here's your student overview.
        </p>
    </div>
@endsection

@section('content')

    {{-- Dashboard Overview --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-1">Academic Overview</h5>
        <p class="text-secondary small mb-0">
            Monitor your assigned students, OJT activities, and capstone projects.
        </p>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Assigned Students</span>
                        <i class="bi bi-people-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">{{ number_format($counts['assignedStudents'] ?? 0) }}</h3>
                    <small class="text-secondary">Students under your supervision</small>
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
                    <h3 class="fw-bold mb-1">{{ number_format($counts['activeInterns'] ?? 0) }}</h3>
                    <small class="text-secondary">Students currently undergoing OJT</small>
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
                    <small class="text-secondary">Submissions awaiting your review</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary small fw-semibold">Capstone Projects</span>
                        <i class="bi bi-kanban-fill fs-4 text-warning"></i>
                    </div>
                    <h3 class="fw-bold mb-1">--</h3>
                    <small class="text-secondary">Projects under your supervision</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Student Management --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0">My Students</h5>
                        <a href="{{ route('faculty.students.index') }}" class="btn btn-sm btn-outline-secondary">
                            View All
                        </a>
                    </div>
                    <p class="text-secondary small mb-4">
                        Overview of students assigned to you.
                    </p>

                    @forelse ($recentStudents ?? [] as $item)
                        @php($placement = $item['placement'])
                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                            <div>
                                <div class="fw-semibold">{{ $placement->student?->user?->name }}</div>
                                <small class="text-secondary">
                                    {{ $placement->student?->student_number }} &middot; {{ $placement->company?->company_name }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary">{{ ucfirst($placement->status) }}</span>
                                <a href="{{ route('faculty.students.show', $placement->student) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    Inspect
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-secondary"></i>
                            <p class="text-secondary mt-3 mb-0">
                                Your assigned students will appear here.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Student Risk Monitoring</h5>
                    <p class="text-secondary small mb-4">
                        Students who may require additional attention.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-shield-exclamation fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Student monitoring records will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- OJT and Capstone Management --}}
    <div class="row g-3">

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">OJT Management</h5>
                    <p class="text-secondary small mb-4">
                        Review student attendance, logs, and weekly reports.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-journal-check fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            OJT review activities will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Capstone Management</h5>
                    <p class="text-secondary small mb-4">
                        Monitor project milestones and student evaluations.
                    </p>

                    <div class="text-center py-5">
                        <i class="bi bi-kanban fs-1 text-secondary"></i>
                        <p class="text-secondary mt-3 mb-0">
                            Capstone activities will appear here.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
