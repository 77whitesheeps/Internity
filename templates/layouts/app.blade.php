<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | Internity</title>

    <!-- Google Fonts: Inter (falls back to system fonts when offline) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Vite bundle: local Bootstrap 5, Bootstrap Icons, Internity CSS & Vanilla JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="dashboard-body">

    @php
        $user = auth()->user();
        $roleName = $user?->role?->name;
        $dashboardRoute = $user?->dashboardRoute() ?? 'home';
    @endphp

    <!-- Sidebar -->
    <aside class="dashboard-sidebar">

        <a href="{{ route($dashboardRoute) }}" class="sidebar-brand text-decoration-none">
            <i class="bi bi-hexagon-fill fs-4"></i>
            <span>INTERNITY</span>
        </a>

        <nav class="sidebar-nav">

            @switch($roleName)

                {{-- ========================================= --}}
                {{-- STUDENT NAVIGATION                        --}}
                {{-- ========================================= --}}
                @case('student')

                    <div class="sidebar-section-label">Workspace</div>

                    <a href="{{ route('student.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="sidebar-section-label">OJT Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-person-circle"></i>
                        <span>My Profile</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-building"></i>
                        <span>Internship Information</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance &amp; Time Tracking</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-journal-text"></i>
                        <span>Daily Accomplishment Logs</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Weekly Reports</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>OJT Progress</span>
                    </a>

                    <div class="sidebar-section-label">Capstone Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-kanban"></i>
                        <span>My Capstone Project</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-people"></i>
                        <span>Project Members</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-flag"></i>
                        <span>Project Milestones</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <span>Deliverables &amp; Submissions</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Evaluations &amp; Feedback</span>
                    </a>

                    @break

                {{-- ========================================= --}}
                {{-- FACULTY ADVISOR NAVIGATION                --}}
                {{-- ========================================= --}}
                @case('faculty_advisor')

                    <div class="sidebar-section-label">Workspace</div>

                    <a href="{{ route('faculty.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="sidebar-section-label">Student Management</div>

                    <a href="{{ route('faculty.students.index') }}"
                       class="sidebar-link {{ request()->routeIs('faculty.students.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>My Students</span>
                    </a>

                    <a href="{{ route('faculty.placements.index') }}"
                       class="sidebar-link {{ request()->routeIs('faculty.placements.*') ? 'active' : '' }}">
                        <i class="bi bi-briefcase"></i>
                        <span>Placements &amp; Assignments</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Student Risk Monitoring</span>
                    </a>

                    <div class="sidebar-section-label">OJT Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance Approvals</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-journal-text"></i>
                        <span>Daily Log Reviews</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Weekly Report Reviews</span>
                    </a>

                    <div class="sidebar-section-label">Capstone Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-kanban"></i>
                        <span>Capstone Projects</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-flag"></i>
                        <span>Milestones &amp; Evaluations</span>
                    </a>

                    <div class="sidebar-section-label">Analytics</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Reports &amp; Analytics</span>
                    </a>

                    @break

                {{-- ========================================= --}}
                {{-- INDUSTRY MENTOR NAVIGATION                --}}
                {{-- ========================================= --}}
                @case('industry_mentor')

                    <div class="sidebar-section-label">Workspace</div>

                    <a href="{{ route('mentor.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('mentor.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="sidebar-section-label">Intern Management</div>

                    <a href="{{ route('mentor.interns.index') }}"
                       class="sidebar-link {{ request()->routeIs('mentor.interns.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>My Interns</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clock-history"></i>
                        <span>Attendance Approvals</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-journal-text"></i>
                        <span>Daily Log Reviews</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-file-earmark-pdf"></i>
                        <span>Weekly Report Reviews</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Intern Progress</span>
                    </a>

                    <div class="sidebar-section-label">Evaluation</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Performance Evaluations</span>
                    </a>

                    @break

                {{-- ========================================= --}}
                {{-- ADMINISTRATOR NAVIGATION                  --}}
                {{-- ========================================= --}}
                @case('administrator')

                    <div class="sidebar-section-label">Workspace</div>

                    <a href="{{ route('admin.dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="sidebar-section-label">User Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-people-fill"></i>
                        <span>Students</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-person-workspace"></i>
                        <span>Faculty Advisors</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Industry Mentors</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>Administrators</span>
                    </a>

                    <div class="sidebar-section-label">Organization Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-buildings-fill"></i>
                        <span>Companies</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-diagram-3-fill"></i>
                        <span>Industry Partnerships</span>
                    </a>

                    <div class="sidebar-section-label">OJT Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-briefcase-fill"></i>
                        <span>Internship Placements</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clipboard-data-fill"></i>
                        <span>OJT Monitoring</span>
                    </a>

                    <div class="sidebar-section-label">Capstone Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-kanban-fill"></i>
                        <span>Capstone Projects</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Project Monitoring</span>
                    </a>

                    <div class="sidebar-section-label">System Management</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-person-gear"></i>
                        <span>Roles &amp; Permissions</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Reports &amp; Analytics</span>
                    </a>

                    @break

            @endswitch

            {{-- ACCOUNT SETTINGS --}}
            <div class="sidebar-section-label">Account</div>

            <button type="button"
                    class="sidebar-link w-100 border-0 bg-transparent text-start"
                    data-bs-toggle="modal"
                    data-bs-target="#settingsModal">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
            </button>

        </nav>

    </aside>

    <!-- Main area -->
    <div class="dashboard-main">

        <!-- Header -->
        <header class="dashboard-header">

            <div class="d-flex align-items-center gap-3">

                <button type="button"
                        class="sidebar-toggle"
                        id="sidebarToggle"
                        aria-label="Toggle sidebar">
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    @hasSection('page-heading')
                        @yield('page-heading')
                    @else
                        <h5 class="mb-0 fw-bold">Dashboard</h5>
                        <small class="text-muted d-none d-sm-block">
                            Internity Management System
                        </small>
                    @endif
                </div>

            </div>

            <div class="header-actions">

                <!-- Dark mode -->
                <button type="button"
                        class="header-icon-button"
                        id="themeToggle"
                        aria-label="Toggle dark mode"
                        title="Toggle dark mode">
                    <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                </button>

                <!-- Profile dropdown -->
                <div class="dropdown">

                    <button type="button"
                            class="btn d-flex align-items-center gap-2 border-0"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                        <div class="profile-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div class="text-start d-none d-sm-block">
                            <div class="fw-semibold small">
                                {{ $user->name }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ ucwords(str_replace('_', ' ', $roleName ?? 'unassigned')) }}
                            </div>
                        </div>

                        <i class="bi bi-chevron-down small"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2">

                        <li class="px-3 py-2">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <button type="button"
                                    class="dropdown-item rounded"
                                    data-bs-toggle="modal"
                                    data-bs-target="#settingsModal">
                                <i class="bi bi-gear me-2"></i>
                                Settings
                            </button>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="dropdown-item rounded text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Log out
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>

            </div>
        </header>

        <!-- Page content -->
        <main class="dashboard-content">
            @yield('content')
        </main>

    </div>

    <!-- Settings modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1"
         aria-labelledby="settingsModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="settingsModalLabel">
                        <i class="bi bi-gear me-2"></i>
                        Settings
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-1 fw-semibold">Appearance</p>
                    <p class="text-muted small mb-0">
                        Use the moon or sun button in the header to switch
                        between light and dark mode.
                    </p>

                    <hr>

                    <p class="mb-1 fw-semibold">Account settings</p>
                    <p class="text-muted small mb-0">
                        Profile and account preferences will be available here
                        in a future update.
                    </p>
                </div>

            </div>

        </div>
    </div>

    @stack('scripts')

</body>
</html>
