/**
 * Internity - shared front-end helpers and dashboard UI behavior (Vanilla ES6+).
 *
 * Every fetch() that mutates data MUST send the CSRF token from
 * <meta name="csrf-token" content="..."> rendered in the layout templates.
 */

const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

/**
 * Thin wrapper around fetch() for JSON APIs.
 * @param {string} url
 * @param {RequestInit} [options]
 * @returns {Promise<any>}
 */
export async function apiFetch(url, options = {}) {
    const response = await fetch(url, {
        credentials: 'same-origin',
        ...options,
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken(),
            ...(options.body && !(options.body instanceof FormData)
                ? { 'Content-Type': 'application/json' }
                : {}),
            ...options.headers,
        },
    });

    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
        const error = new Error(data.message || `Request failed (${response.status})`);
        error.status = response.status;
        error.errors = data.errors ?? {};
        throw error;
    }
    return data;
}

/**
 * Escape untrusted text before injecting into the DOM (XSS protection).
 * Prefer element.textContent where possible; use this for template strings.
 */
export function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

// Expose helpers globally for inline/page-specific scripts
window.apiFetch = apiFetch;
window.escapeHtml = escapeHtml;

/**
 * Initialize dashboard shell interactions (sidebar toggle, dark mode, placeholders).
 */
function initDashboardShell() {
    const body = document.body;
    const sidebarToggle = document.getElementById('sidebarToggle');
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Prevent navigation on unfinished placeholder sidebar links
    document.querySelectorAll('.sidebar-placeholder').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
        });
    });

    if (!sidebarToggle || !themeToggle || !themeIcon) {
        return;
    }

    // Restore saved sidebar preference
    if (localStorage.getItem('internity-sidebar') === 'collapsed') {
        body.classList.add('sidebar-collapsed');
    }

    // Restore saved theme preference
    if (localStorage.getItem('internity-theme') === 'dark') {
        body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        themeIcon.className = 'bi bi-sun-fill';
    }

    // Sidebar toggle
    sidebarToggle.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            body.classList.toggle('mobile-sidebar-open');
            return;
        }

        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(
            'internity-sidebar',
            body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded'
        );
    });

    // Dark mode toggle
    themeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');

        document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
        themeIcon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        localStorage.setItem('internity-theme', isDark ? 'dark' : 'light');
    });

    // Close mobile sidebar when clicking outside it
    document.addEventListener('click', (event) => {
        if (
            window.innerWidth <= 768 &&
            body.classList.contains('mobile-sidebar-open') &&
            !event.target.closest('.dashboard-sidebar') &&
            !event.target.closest('#sidebarToggle')
        ) {
            body.classList.remove('mobile-sidebar-open');
        }
    });
}

document.addEventListener('DOMContentLoaded', initDashboardShell);
