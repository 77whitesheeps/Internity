/**
 * Internity - shared front-end helpers (Vanilla ES6+).
 *
 * Every fetch() that changes data MUST send the CSRF token. Laravel prints it in
 * <meta name="csrf-token" content="..."> inside the base template.
 */
'use strict';

const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

/**
 * Thin wrapper around fetch() for JSON APIs.
 * @param {string} url
 * @param {RequestInit} [options]
 * @returns {Promise<any>}
 */
async function apiFetch(url, options = {}) {
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
function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}
