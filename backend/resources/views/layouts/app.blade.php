
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | Internity</title>

    <!-- Bootstrap 5.3 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])

    <style>
        :root {
            --internity-onyx: #0E1011;
            --internity-gold: #FAC05E;
            --internity-ghost: #F7F5FB;
            --internity-green: #4DAA57;

            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--internity-ghost);
            color: var(--internity-onyx);
            min-height: 100vh;
        }

        /* Sidebar */

        .dashboard-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--internity-onyx);
            color: white;
            transition: width 0.25s ease, transform 0.25s ease;
            overflow-x: hidden;
        }

        .sidebar-brand {
            height: 76px;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 12px;
            color: var(--internity-gold);
            font-size: 1.25rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .sidebar-nav {
            padding: 20px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            margin-bottom: 6px;
            border-radius: 10px;
            color: #c8c8c8;
            text-decoration: none;
            white-space: nowrap;
            transition: 0.2s ease;
        }

        .sidebar-link:hover {
            background-color: #25292b;
            color: white;
        }

        .sidebar-link.active {
            background-color: var(--internity-gold);
            color: var(--internity-onyx);
            font-weight: 700;
        }

        .sidebar-link i {
            font-size: 1.2rem;
            min-width: 22px;
            text-align: center;
        }

        .sidebar-section-label {
            padding: 16px 14px 8px;
            color: #888;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* Main content */

        .dashboard-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.25s ease;
        }

        .dashboard-header {
            height: 76px;
            padding: 0 30px;
            background-color: #ffffff;
            border-bottom: 1px solid #e9e7ed;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .dashboard-content {
            padding: 30px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon-button {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: var(--internity-onyx);
            font-size: 1.2rem;
        }

        .header-icon-button:hover {
            background-color: #f0eef3;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--internity-gold);
            color: var(--internity-onyx);
            font-weight: 800;
        }

        .sidebar-toggle {
            border: none;
            background: transparent;
            font-size: 1.3rem;
            color: inherit;
        }

        /* Collapsed sidebar */

        body.sidebar-collapsed .dashboard-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .dashboard-main {
            margin-left: var(--sidebar-collapsed-width);
        }

        body.sidebar-collapsed .sidebar-brand span,
        body.sidebar-collapsed .sidebar-link span,
        body.sidebar-collapsed .sidebar-section-label {
            display: none;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding: 0;
        }

        body.sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }

        /* Dark mode */

        body.dark-mode {
            background-color: #181b1d;
            color: var(--internity-ghost);
        }

        body.dark-mode .dashboard-header {
            background-color: #202426;
            border-color: #34393b;
            color: white;
        }

        body.dark-mode .header-icon-button {
            color: white;
        }

        body.dark-mode .header-icon-button:hover {
            background-color: #34393b;
        }

        body.dark-mode .dashboard-content {
            color: var(--internity-ghost);
        }

        body.dark-mode .card,
        body.dark-mode .dropdown-menu,
        body.dark-mode .modal-content {
            background-color: #202426;
            color: var(--internity-ghost);
            border-color: #34393b;
        }

        body.dark-mode .text-muted {
            color: #b4b4b4 !important;
        }

        body.dark-mode .dropdown-item {
            color: var(--internity-ghost);
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #34393b;
            color: white;
        }

        /* Mobile layout */

        @media (max-width: 768px) {
            .dashboard-sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            body.mobile-sidebar-open .dashboard-sidebar {
                transform: translateX(0);
            }

            .dashboard-main {
                margin-left: 0 !important;
            }

            body.sidebar-collapsed .dashboard-sidebar {
                width: var(--sidebar-width);
            }

            body.sidebar-collapsed .sidebar-brand span,
            body.sidebar-collapsed .sidebar-link span,
            body.sidebar-collapsed .sidebar-section-label {
                display: initial;
            }

            body.sidebar-collapsed .sidebar-brand {
                justify-content: flex-start;
                padding: 0 24px;
            }

            body.sidebar-collapsed .sidebar-link {
                justify-content: flex-start;
                padding: 12px 14px;
            }

            .dashboard-header {
                padding: 0 16px;
            }

            .dashboard-content {
                padding: 20px 16px;
            }
        }
    </style>
</head>

<body>

    @php
        $user = auth()->user();

        $dashboardRoute = match ($user->role->name) {
            'administrator' => 'admin.dashboard',
            'faculty_advisor' => 'faculty.dashboard',
            'industry_mentor' => 'mentor.dashboard',
            'student' => 'student.dashboard',
            default => 'login',
        };
    @endphp

    <!-- Sidebar -->
    <aside class="dashboard-sidebar">

        <a href="{{ route($dashboardRoute) }}" class="sidebar-brand text-decoration-none">
            <i class="bi bi-hexagon-fill fs-4"></i>
            <span>INTERNITY</span>
        </a>

        <nav class="sidebar-nav">

            @switch($user->role->name)

                {{-- ========================================= --}}
                {{-- STUDENT NAVIGATION                       --}}
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
                        <span>Attendance & Time Tracking</span>
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
                        <span>Deliverables & Submissions</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Evaluations & Feedback</span>
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

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-people"></i>
                        <span>My Students</span>
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
                        <span>Milestones & Evaluations</span>
                    </a>

                    <div class="sidebar-section-label">Analytics</div>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Reports & Analytics</span>
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

                    <a href="#" class="sidebar-link sidebar-placeholder">
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
                {{-- ADMINISTRATOR PLACEHOLDER                --}}
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
                        <i class="bi bi-handshake-fill"></i>
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
                        <span>Roles & Permissions</span>
                    </a>

                    <a href="#" class="sidebar-link sidebar-placeholder">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Reports & Analytics</span>
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
                    <h5 class="mb-0 fw-bold">
                        @yield('page-heading', 'Dashboard')
                    </h5>
                    <small class="text-muted d-none d-sm-block">
                        Internity Management System
                    </small>
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
                                {{ ucwords(str_replace('_', ' ', $user->role->name)) }}
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

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            // Prevent navigation on unfinished sidebar links
            document.querySelectorAll('.sidebar-placeholder').forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                });
            });

            // Restore saved sidebar preference
            if (localStorage.getItem('internity-sidebar') === 'collapsed') {
                body.classList.add('sidebar-collapsed');
            }

            // Restore saved theme preference
            const savedTheme = localStorage.getItem('internity-theme');

            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                themeIcon.className = 'bi bi-sun-fill';
            }

            // Sidebar toggle
            sidebarToggle.addEventListener('click', function () {

                if (window.innerWidth <= 768) {
                    body.classList.toggle('mobile-sidebar-open');
                    return;
                }

                body.classList.toggle('sidebar-collapsed');

                localStorage.setItem(
                    'internity-sidebar',
                    body.classList.contains('sidebar-collapsed')
                        ? 'collapsed'
                        : 'expanded'
                );
            });

            // Dark mode toggle
            themeToggle.addEventListener('click', function () {

                body.classList.toggle('dark-mode');

                const isDark = body.classList.contains('dark-mode');

                document.documentElement.setAttribute(
                    'data-bs-theme',
                    isDark ? 'dark' : 'light'
                );

                themeIcon.className = isDark
                    ? 'bi bi-sun-fill'
                    : 'bi bi-moon-stars-fill';

                localStorage.setItem(
                    'internity-theme',
                    isDark ? 'dark' : 'light'
                );
            });

            // Close mobile sidebar when clicking outside it
            document.addEventListener('click', function (event) {

                if (
                    window.innerWidth <= 768 &&
                    body.classList.contains('mobile-sidebar-open') &&
                    !event.target.closest('.dashboard-sidebar') &&
                    !event.target.closest('#sidebarToggle')
                ) {
                    body.classList.remove('mobile-sidebar-open');
                }
            });

        });
    </script>

    @vite(['resources/js/app.js'])

    @stack('scripts')

</body>
</html>
