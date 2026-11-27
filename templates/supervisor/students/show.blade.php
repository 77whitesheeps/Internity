@extends('layouts.app')

@section('title', $student->user?->name ?? 'Student Profile')

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">{{ $student->user?->name ?? 'Student Profile' }}</h4>
        <p class="text-secondary mb-0 small">
            Student Profile, Internship Placement Details &amp; OJT Progress
        </p>
    </div>
@endsection

@section('content')

    @php
        $badgeMap = [
            'pending' => 'bg-warning text-dark',
            'active' => 'bg-success',
            'completed' => 'bg-primary',
            'cancelled' => 'bg-secondary',
        ];
    @endphp

    <div class="mb-4">
        <a href="{{ $backUrl }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to {{ $isFaculty ? 'My Students' : 'My Interns' }}
        </a>
    </div>

    <div class="row g-4 mb-4">

        {{-- Student Profile Card --}}
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="profile-avatar" style="width: 52px; height: 52px; font-size: 1.25rem;">
                            {{ strtoupper(substr($student->user?->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">{{ $student->user?->name }}</h5>
                            <span class="text-secondary small">{{ $student->user?->email }}</span>
                        </div>
                    </div>

                    <dl class="row mb-0 small">
                        <dt class="col-5 text-secondary">Student Number</dt>
                        <dd class="col-7 fw-semibold font-monospace">{{ $student->student_number }}</dd>

                        <dt class="col-5 text-secondary">Program</dt>
                        <dd class="col-7">{{ $student->program }}</dd>

                        <dt class="col-5 text-secondary">Year Level</dt>
                        <dd class="col-7">Year {{ $student->year_level }}</dd>

                        <dt class="col-5 text-secondary">Contact Number</dt>
                        <dd class="col-7 mb-0">{{ $student->contact_number ?? 'Not provided' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- OJT Hour Progress Card --}}
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">OJT Hour Completion</h5>
                        @if ($currentPlacement)
                            <span class="badge {{ $badgeMap[$currentPlacement->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($currentPlacement->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="row g-3 text-center mb-4">
                        <div class="col-4">
                            <div class="p-3 rounded bg-body-tertiary">
                                <div class="fs-4 fw-bold text-internity">
                                    {{ number_format($progress['percentage'], 1) }}%
                                </div>
                                <small class="text-secondary">Completion</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-body-tertiary">
                                <div class="fs-4 fw-bold">
                                    {{ number_format($progress['approved_hours'], 1) }}
                                </div>
                                <small class="text-secondary">Approved Hours</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 rounded bg-body-tertiary">
                                <div class="fs-4 fw-bold">
                                    {{ number_format($progress['remaining_hours'], 1) }}
                                </div>
                                <small class="text-secondary">
                                    Remaining (of {{ number_format($progress['required_hours'], 0) }})
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-warning"
                             role="progressbar"
                             style="width: {{ $progress['percentage'] }}%"
                             aria-valuenow="{{ $progress['percentage'] }}"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>

                    @if (! $progress['attendance_module_ready'])
                        <div class="alert alert-info small mb-0 d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill mt-1"></i>
                            <div>
                                <strong>Attendance &amp; Daily Log Review (On Hold):</strong>
                                Approved hour totals will populate automatically once the Student Attendance
                                Session module is integrated.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Internship Placement Details --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Internship Placements &amp; Assigned Supervisors</h5>
            <p class="text-secondary small mb-4">
                Placement records assigned under your supervision.
            </p>

            @foreach ($placements as $placement)
                @php
                    $facultyAssignment = $placement->supervisorAssignments->firstWhere('assignment_type', 'faculty_advisor');
                    $mentorAssignment = $placement->supervisorAssignments->firstWhere('assignment_type', 'industry_mentor');
                @endphp

                <div class="border rounded-3 p-3 p-md-4 {{ ! $loop->last ? 'mb-3' : '' }}">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">
                                <i class="bi bi-building me-1 text-warning"></i>
                                {{ $placement->company?->company_name ?? 'Unassigned Company' }}
                            </h6>
                            <small class="text-secondary">
                                {{ $placement->company?->address ?? 'No company address on file' }}
                            </small>
                        </div>
                        <span class="badge {{ $badgeMap[$placement->status] ?? 'bg-secondary' }}">
                            {{ ucfirst($placement->status) }}
                        </span>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="text-secondary">Start Date</div>
                            <div class="fw-semibold">{{ $placement->start_date?->format('M d, Y') ?? '—' }}</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="text-secondary">End Date</div>
                            <div class="fw-semibold">{{ $placement->end_date?->format('M d, Y') ?? 'Ongoing' }}</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="text-secondary">Required Hours</div>
                            <div class="fw-semibold">{{ number_format($placement->required_hours, 2) }} hrs</div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="text-secondary">Company Contact</div>
                            <div class="fw-semibold">
                                {{ $placement->company?->contact_email ?? $placement->company?->contact_number ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3 small">
                        <div class="col-12 col-md-6">
                            <span class="text-secondary d-block">Faculty Advisor</span>
                            <span class="fw-semibold">
                                {{ $facultyAssignment?->supervisor?->name ?? 'Unassigned' }}
                            </span>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="text-secondary d-block">Industry Mentor</span>
                            <span class="fw-semibold">
                                {{ $mentorAssignment?->supervisor?->name ?? 'Unassigned' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
