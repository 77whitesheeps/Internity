@extends('layouts.app')

@section('title', $pageTitle)

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">{{ $pageTitle }}</h4>
        <p class="text-secondary mb-0 small">
            Search assigned trainees by name or student number, filter by internship status, and inspect progress.
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

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Assigned Trainees</h5>
            <p class="text-secondary small mb-0">
                Showing <span id="assignedStudentCount" class="fw-semibold">{{ $records->count() }}</span>
                assigned {{ Str::plural('placement', $records->count()) }}.
            </p>
        </div>

        @if ($isFaculty)
            <a href="{{ route('faculty.placements.index') }}" class="btn btn-internity">
                <i class="bi bi-briefcase-fill me-2"></i>
                Manage Placements &amp; Assignments
            </a>
        @endif
    </div>

    {{-- Search & Filter Bar (AJAX-powered, works without JS via form submit too) --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET"
                  action="{{ $indexUrl }}"
                  id="studentFilterForm"
                  data-search-url="{{ $searchUrl }}"
                  class="row g-3 align-items-end">

                <div class="col-12 col-md-6">
                    <label for="studentSearchInput" class="form-label small fw-semibold">
                        Search Student
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input
                            type="search"
                            class="form-control"
                            id="studentSearchInput"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search by full name or student number..."
                            autocomplete="off"
                        >
                    </div>
                </div>

                <div class="col-12 col-sm-7 col-md-4">
                    <label for="studentStatusFilter" class="form-label small fw-semibold">
                        Internship Status
                    </label>
                    <select class="form-select" id="studentStatusFilter" name="status">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $option)
                            <option value="{{ $option }}" {{ $status === $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-5 col-md-2 d-grid">
                    <button type="button" id="studentFilterReset" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Reset
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Error Alert for AJAX failures --}}
    <div id="studentSearchError" class="alert alert-danger d-none" role="alert"></div>

    {{-- Responsive Student Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Student</th>
                            <th>Student Number</th>
                            <th>Partner Company</th>
                            <th>Status</th>
                            <th style="min-width: 180px;">OJT Progress</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody id="assignedStudentsTableBody">
                        @forelse ($records as $record)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $record['student_name'] }}</div>
                                    <small class="text-secondary">
                                        {{ $record['program'] }} &middot; Year {{ $record['year_level'] }}
                                    </small>
                                </td>
                                <td>
                                    <span class="font-monospace small">{{ $record['student_number'] }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $record['company_name'] }}</div>
                                    <small class="text-secondary">
                                        {{ $record['start_date'] }} &ndash; {{ $record['end_date'] }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $badgeMap[$record['status']] ?? 'bg-secondary' }}">
                                        {{ ucfirst($record['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>
                                            {{ number_format($record['progress']['approved_hours'], 1) }}
                                            / {{ number_format($record['progress']['required_hours'], 0) }} hrs
                                        </span>
                                        <span class="fw-semibold">{{ number_format($record['progress']['percentage'], 0) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-warning"
                                             role="progressbar"
                                             style="width: {{ $record['progress']['percentage'] }}%"
                                             aria-valuenow="{{ $record['progress']['percentage'] }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ $record['detail_url'] }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye me-1"></i>
                                        View Profile
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    No assigned students match your current filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
