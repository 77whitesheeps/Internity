@extends('layouts.app')

@section('title', 'Placements & Assignments')

@section('page-heading')
    <div>
        <h4 class="mb-1 fw-bold">Internship Placements &amp; Assignments</h4>
        <p class="text-secondary mb-0 small">
            Create internship placements, update placement statuses, and assign or reassign supervisors.
        </p>
    </div>
@endsection

@section('content')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please review the submitted details:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h5 class="fw-bold mb-1">All Internship Placements</h5>
            <p class="text-secondary small mb-0">
                {{ $placements->count() }} {{ Str::plural('placement', $placements->count()) }} found.
            </p>
        </div>

        <button type="button"
                class="btn btn-internity"
                data-bs-toggle="modal"
                data-bs-target="#createPlacementModal">
            <i class="bi bi-plus-circle me-2"></i>
            New Placement
        </button>
    </div>

    {{-- Filter bar --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('faculty.placements.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                    <label for="placementSearch" class="form-label small fw-semibold">Search</label>
                    <input type="search"
                           class="form-control"
                           id="placementSearch"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Student name, student number, or company...">
                </div>

                <div class="col-12 col-sm-7 col-md-4">
                    <label for="placementStatusFilter" class="form-label small fw-semibold">Status</label>
                    <select class="form-select" id="placementStatusFilter" name="status">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $option)
                            <option value="{{ $option }}" {{ $status === $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-5 col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Placements Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Student</th>
                            <th>Company &amp; Schedule</th>
                            <th>Faculty Advisor</th>
                            <th>Industry Mentor</th>
                            <th style="min-width: 150px;">Status (AJAX)</th>
                            <th class="text-end pe-4">Supervisors</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($placements as $placement)
                            @php
                                $facultyAssignment = $placement->supervisorAssignments->firstWhere('assignment_type', 'faculty_advisor');
                                $mentorAssignment = $placement->supervisorAssignments->firstWhere('assignment_type', 'industry_mentor');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $placement->student?->user?->name ?? 'Unknown' }}</div>
                                    <small class="text-secondary font-monospace">
                                        {{ $placement->student?->student_number }}
                                    </small>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $placement->company?->company_name ?? '—' }}</div>
                                    <small class="text-secondary">
                                        {{ $placement->start_date?->format('M d, Y') }}
                                        &ndash;
                                        {{ $placement->end_date?->format('M d, Y') ?? 'Ongoing' }}
                                        &middot; {{ number_format($placement->required_hours, 0) }} hrs
                                    </small>
                                </td>
                                <td>
                                    <span class="small {{ $facultyAssignment ? 'fw-medium' : 'text-secondary fst-italic' }}">
                                        {{ $facultyAssignment?->supervisor?->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="small {{ $mentorAssignment ? 'fw-medium' : 'text-secondary fst-italic' }}">
                                        {{ $mentorAssignment?->supervisor?->name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm placement-status-select"
                                            aria-label="Internship status for {{ $placement->student?->user?->name }}"
                                            data-url="{{ route('faculty.placements.status', $placement) }}"
                                            data-previous="{{ $placement->status }}">
                                        @foreach ($statuses as $option)
                                            <option value="{{ $option }}" {{ $placement->status === $option ? 'selected' : '' }}>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary assign-supervisors-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#assignSupervisorsModal"
                                            data-action="{{ route('faculty.placements.assign', $placement) }}"
                                            data-student-name="{{ $placement->student?->user?->name }}"
                                            data-company-name="{{ $placement->company?->company_name }}"
                                            data-faculty-id="{{ $facultyAssignment?->supervisor_id ?? '' }}"
                                            data-mentor-id="{{ $mentorAssignment?->supervisor_id ?? '' }}">
                                        <i class="bi bi-people-fill me-1"></i>
                                        Assign
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                                    No internship placements yet. Click <strong>New Placement</strong> to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Placement Modal --}}
    <div class="modal fade" id="createPlacementModal" tabindex="-1"
         aria-labelledby="createPlacementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('faculty.placements.store') }}" id="createPlacementForm">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="createPlacementModalLabel">
                            <i class="bi bi-briefcase-fill me-2 text-warning"></i>
                            Create Internship Placement
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="placementStudentId" class="form-label fw-semibold">Student</label>
                                <select class="form-select" id="placementStudentId" name="student_id" required>
                                    <option value="">Select a student...</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->student_number }} — {{ $student->user?->name }} ({{ $student->program }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="placementCompanyId" class="form-label fw-semibold mb-0">Partner Company</label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-internity" id="toggleNewCompanyBtn">
                                        + Add new company
                                    </button>
                                </div>

                                <select class="form-select" id="placementCompanyId" name="company_id">
                                    <option value="">Select an existing company...</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 d-none" id="newCompanyFields">
                                <div class="border rounded-3 p-3 bg-body-tertiary">
                                    <div class="fw-semibold small mb-2">New Partner Company Details</div>
                                    <div class="row g-2">
                                        <div class="col-12 col-md-6">
                                            <input type="text"
                                                   class="form-control form-control-sm"
                                                   id="newCompanyName"
                                                   name="new_company_name"
                                                   maxlength="255"
                                                   value="{{ old('new_company_name') }}"
                                                   placeholder="Company name *">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="email"
                                                   class="form-control form-control-sm"
                                                   name="new_company_email"
                                                   maxlength="255"
                                                   value="{{ old('new_company_email') }}"
                                                   placeholder="Contact email">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text"
                                                   class="form-control form-control-sm"
                                                   name="new_company_contact"
                                                   maxlength="30"
                                                   value="{{ old('new_company_contact') }}"
                                                   placeholder="Contact number">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <input type="text"
                                                   class="form-control form-control-sm"
                                                   name="new_company_address"
                                                   maxlength="500"
                                                   value="{{ old('new_company_address') }}"
                                                   placeholder="Address">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="placementStartDate" class="form-label fw-semibold">Start Date</label>
                                <input type="date"
                                       class="form-control"
                                       id="placementStartDate"
                                       name="start_date"
                                       value="{{ old('start_date', now()->toDateString()) }}"
                                       required>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="placementEndDate" class="form-label fw-semibold">End Date (Optional)</label>
                                <input type="date"
                                       class="form-control"
                                       id="placementEndDate"
                                       name="end_date"
                                       value="{{ old('end_date') }}">
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="placementRequiredHours" class="form-label fw-semibold">Required Hours</label>
                                <input type="number"
                                       class="form-control"
                                       id="placementRequiredHours"
                                       name="required_hours"
                                       min="1"
                                       max="2000"
                                       step="0.5"
                                       value="{{ old('required_hours', 486) }}"
                                       required>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="placementStatus" class="form-label fw-semibold">Initial Status</label>
                                <select class="form-select" id="placementStatus" name="status" required>
                                    @foreach ($statuses as $option)
                                        <option value="{{ $option }}" {{ old('status', 'active') === $option ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="initialFacultyId" class="form-label fw-semibold">Faculty Advisor</label>
                                <select class="form-select" id="initialFacultyId" name="faculty_advisor_id">
                                    <option value="">Default (Assign to me)</option>
                                    @foreach ($facultyAdvisors as $advisor)
                                        <option value="{{ $advisor->id }}" {{ old('faculty_advisor_id') == $advisor->id ? 'selected' : '' }}>
                                            {{ $advisor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-sm-6">
                                <label for="initialMentorId" class="form-label fw-semibold">Industry Mentor</label>
                                <select class="form-select" id="initialMentorId" name="industry_mentor_id">
                                    <option value="">Unassigned</option>
                                    @foreach ($industryMentors as $mentor)
                                        <option value="{{ $mentor->id }}" {{ old('industry_mentor_id') == $mentor->id ? 'selected' : '' }}>
                                            {{ $mentor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-internity">Save Placement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Assign / Reassign Supervisors Modal --}}
    <div class="modal fade" id="assignSupervisorsModal" tabindex="-1"
         aria-labelledby="assignSupervisorsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="#" id="assignSupervisorsForm">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="assignSupervisorsModalLabel">
                            <i class="bi bi-people-fill me-2 text-warning"></i>
                            Assign / Reassign Supervisors
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-secondary small mb-3" id="assignPlacementSummary"></p>

                        <div class="mb-3">
                            <label for="assignFacultySelect" class="form-label fw-semibold">Faculty Advisor</label>
                            <select class="form-select" id="assignFacultySelect" name="faculty_advisor_id">
                                <option value="">Unassigned</option>
                                @foreach ($facultyAdvisors as $advisor)
                                    <option value="{{ $advisor->id }}">{{ $advisor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label for="assignMentorSelect" class="form-label fw-semibold">Industry Mentor</label>
                            <select class="form-select" id="assignMentorSelect" name="industry_mentor_id">
                                <option value="">Unassigned</option>
                                @foreach ($industryMentors as $mentor)
                                    <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-internity">Update Assignments</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Toast notification container for AJAX status updates --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="placementToast" class="toast align-items-center border-0" role="status" aria-live="polite" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="placementToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection
