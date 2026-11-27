/**
 * Phase 3 — Faculty Advisor / Industry Mentor Student & Placement interactions.
 * Uses Vanilla ES6+ and the shared apiFetch() / escapeHtml() helpers.
 */
import { apiFetch, escapeHtml } from './app.js';

const STATUS_BADGES = {
    pending: 'bg-warning text-dark',
    active: 'bg-success',
    completed: 'bg-primary',
    cancelled: 'bg-secondary',
};

function capitalize(value) {
    return value ? value.charAt(0).toUpperCase() + value.slice(1) : '';
}

function showToast(message, isError = false) {
    const toastEl = document.getElementById('placementToast');
    const bodyEl = document.getElementById('placementToastBody');
    if (!toastEl || !bodyEl || !window.bootstrap) {
        return;
    }

    toastEl.classList.remove('text-bg-success', 'text-bg-danger');
    toastEl.classList.add(isError ? 'text-bg-danger' : 'text-bg-success');
    bodyEl.textContent = message;

    window.bootstrap.Toast.getOrCreateInstance(toastEl, { delay: 3000 }).show();
}

/**
 * Live AJAX search & internship status filtering on My Students / My Interns.
 */
function initAssignedStudentSearch() {
    const form = document.getElementById('studentFilterForm');
    const searchInput = document.getElementById('studentSearchInput');
    const statusSelect = document.getElementById('studentStatusFilter');
    const resetBtn = document.getElementById('studentFilterReset');
    const tbody = document.getElementById('assignedStudentsTableBody');
    const countEl = document.getElementById('assignedStudentCount');
    const errorEl = document.getElementById('studentSearchError');

    if (!form || !searchInput || !statusSelect || !tbody) {
        return;
    }

    const searchUrl = form.dataset.searchUrl;
    let debounceTimer = null;

    const renderRows = (records) => {
        if (!records.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-5 text-secondary">
                        <i class="bi bi-people fs-1 d-block mb-2"></i>
                        No assigned students match your current filter.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = records.map((record) => {
            const badgeClass = STATUS_BADGES[record.status] ?? 'bg-secondary';
            const approved = Number(record.progress?.approved_hours ?? 0).toFixed(1);
            const required = Number(record.progress?.required_hours ?? 0).toFixed(0);
            const pct = Number(record.progress?.percentage ?? 0);

            return `
                <tr>
                    <td class="ps-4">
                        <div class="fw-semibold">${escapeHtml(record.student_name)}</div>
                        <small class="text-secondary">
                            ${escapeHtml(record.program)} &middot; Year ${escapeHtml(String(record.year_level))}
                        </small>
                    </td>
                    <td>
                        <span class="font-monospace small">${escapeHtml(record.student_number)}</span>
                    </td>
                    <td>
                        <div class="fw-medium">${escapeHtml(record.company_name)}</div>
                        <small class="text-secondary">
                            ${escapeHtml(record.start_date)} &ndash; ${escapeHtml(record.end_date)}
                        </small>
                    </td>
                    <td>
                        <span class="badge ${badgeClass}">${escapeHtml(capitalize(record.status))}</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-between small mb-1">
                            <span>${escapeHtml(approved)} / ${escapeHtml(required)} hrs</span>
                            <span class="fw-semibold">${escapeHtml(pct.toFixed(0))}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning"
                                 role="progressbar"
                                 style="width: ${pct}%"
                                 aria-valuenow="${pct}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <a href="${escapeHtml(record.detail_url)}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye me-1"></i>
                            View Profile
                        </a>
                    </td>
                </tr>
            `;
        }).join('');
    };

    const runSearch = async () => {
        const params = new URLSearchParams();
        if (searchInput.value.trim()) {
            params.set('search', searchInput.value.trim());
        }
        if (statusSelect.value) {
            params.set('status', statusSelect.value);
        }

        const url = params.toString() ? `${searchUrl}?${params.toString()}` : searchUrl;
        errorEl?.classList.add('d-none');

        try {
            const payload = await apiFetch(url, { method: 'GET' });
            renderRows(payload.data ?? []);
            if (countEl) {
                countEl.textContent = String(payload.count ?? 0);
            }
        } catch (error) {
            if (errorEl) {
                errorEl.textContent = error.message || 'Unable to load assigned students.';
                errorEl.classList.remove('d-none');
            }
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        runSearch();
    });

    searchInput.addEventListener('input', () => {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(runSearch, 250);
    });

    statusSelect.addEventListener('change', runSearch);

    resetBtn?.addEventListener('click', () => {
        searchInput.value = '';
        statusSelect.value = '';
        runSearch();
    });
}

/**
 * Placement management interactions: AJAX status updates, new company toggle,
 * and supervisor assignment modal hydration.
 */
function initPlacementManagement() {
    // Inline AJAX status select
    document.querySelectorAll('.placement-status-select').forEach((select) => {
        select.addEventListener('change', async () => {
            const url = select.dataset.url;
            const previous = select.dataset.previous;
            const nextStatus = select.value;

            select.disabled = true;
            try {
                const payload = await apiFetch(url, {
                    method: 'PATCH',
                    body: JSON.stringify({ status: nextStatus }),
                });
                select.dataset.previous = payload.status;
                showToast(payload.message || 'Internship status updated.');
            } catch (error) {
                select.value = previous;
                showToast(error.message || 'Failed to update status.', true);
            } finally {
                select.disabled = false;
            }
        });
    });

    // Toggle inline new company fields inside Create Placement modal
    const toggleNewCompanyBtn = document.getElementById('toggleNewCompanyBtn');
    const newCompanyFields = document.getElementById('newCompanyFields');
    const companySelect = document.getElementById('placementCompanyId');
    const newCompanyName = document.getElementById('newCompanyName');

    toggleNewCompanyBtn?.addEventListener('click', () => {
        const showingNew = newCompanyFields.classList.toggle('d-none') === false;
        if (showingNew) {
            companySelect.value = '';
            companySelect.disabled = true;
            newCompanyName.required = true;
            toggleNewCompanyBtn.textContent = 'Select existing company';
        } else {
            companySelect.disabled = false;
            newCompanyName.value = '';
            newCompanyName.required = false;
            toggleNewCompanyBtn.textContent = '+ Add new company';
        }
    });

    // Client-side validation for Create Placement form (company required & date range)
    const createForm = document.getElementById('createPlacementForm');
    createForm?.addEventListener('submit', (event) => {
        const hasExisting = companySelect && !companySelect.disabled && companySelect.value !== '';
        const hasNew = newCompanyName && newCompanyName.value.trim() !== '';
        const startDate = document.getElementById('placementStartDate')?.value;
        const endDate = document.getElementById('placementEndDate')?.value;

        if (!hasExisting && !hasNew) {
            event.preventDefault();
            showToast('Select a partner company or add a new one.', true);
            return;
        }

        if (startDate && endDate && endDate < startDate) {
            event.preventDefault();
            showToast('End date must be on or after the start date.', true);
        }
    });

    // Hydrate Assign / Reassign Supervisors modal
    const assignForm = document.getElementById('assignSupervisorsForm');
    const summaryEl = document.getElementById('assignPlacementSummary');
    const facultySelect = document.getElementById('assignFacultySelect');
    const mentorSelect = document.getElementById('assignMentorSelect');

    document.querySelectorAll('.assign-supervisors-btn').forEach((button) => {
        button.addEventListener('click', () => {
            if (!assignForm) {
                return;
            }
            assignForm.action = button.dataset.action || '#';
            if (summaryEl) {
                summaryEl.textContent = `${button.dataset.studentName || 'Student'} — ${button.dataset.companyName || 'Company'}`;
            }
            if (facultySelect) {
                facultySelect.value = button.dataset.facultyId || '';
            }
            if (mentorSelect) {
                mentorSelect.value = button.dataset.mentorId || '';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initAssignedStudentSearch();
    initPlacementManagement();
});
