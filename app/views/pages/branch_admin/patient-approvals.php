<?php
/**
 * Patient Sign-up Approvals View (Branch Admin)
 * Backend logic handled by PatientApprovalsController.php
 */
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-gray-900">
        <div>
            <h2 class="text-xl font-bold tracking-tight">Patient Sign-up Approvals</h2>
            <p class="text-sm text-gray-500 mt-1">Review and approve new patient registrations to grant them portal
                access.</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div
            class="p-4 rounded-lg flex items-center gap-3 <?= $messageType === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
            <i data-lucide="<?= $messageType === 'success' ? 'check-circle' : 'alert-circle' ?>" class="w-5 h-5"></i>
            <span class="text-sm font-medium"><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>


    <!-- Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-2" aria-label="Tabs" id="status-tabs">
            <button
                class="tab-btn border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                data-tab="Pending">
                Pending Approvals <span
                    class="bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs ml-2"><?= count($pendingPatients ?? []) ?></span>
            </button>
            <button
                class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                data-tab="Rejected">
                Rejected Accounts <span
                    class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs ml-2"><?= count($rejectedPatients ?? []) ?></span>
            </button>
        </nav>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-1 group" style="position: relative; flex: 1 1 0%;">
            <div
                style="position: absolute; inset-y: 0; left: 0; padding-left: 1rem; display: flex; align-items: center; pointer-events: none; height: 100%; top: 0;">
                <i data-lucide="search" class="text-gray-400 group-hover:text-red-500 transition-colors"
                    style="width: 1.1rem; height: 1.1rem;"></i>
            </div>
            <input type="text" id="search-input" placeholder="Search by name or email..."
                style="padding-left: 2.75rem !important;"
                class="block w-full pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-900 outline-none focus:ring-2 focus:ring-red-500/10 focus:border-red-500 transition-all shadow-sm">
        </div>
        <select id="sort-date"
            class="w-full md:w-48 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-red-500/10 focus:border-red-500 transition-all cursor-pointer shadow-sm">
            <option value="Newest">Newest First</option>
            <option value="Oldest">Oldest First</option>
        </select>
    </div>

    <!-- Table Container -->
    <div id="patient-approvals-table"
        class="realtime-update rounded-xl border border-gray-300 bg-white shadow-sm overflow-hidden min-h-[400px] flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-gray-600">
                        <th class="text-left font-semibold px-4 py-3.5 whitespace-nowrap">Patient Details</th>
                        <th class="text-left font-semibold px-4 py-3.5">Contact Info</th>
                        <th class="text-left font-semibold px-4 py-3.5">Branch</th>
                        <th class="text-left font-semibold px-4 py-3.5">Date Registered</th>
                        <th class="text-center font-semibold px-4 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="text-gray-800 divide-y divide-gray-100">
                    <?php
                    $allPatients = array_merge(
                        array_map(function ($p) {
                            $p['queue_status'] = 'Pending';
                            return $p;
                        }, $pendingPatients ?? []),
                        array_map(function ($p) {
                            $p['queue_status'] = 'Rejected';
                            return $p;
                        }, $rejectedPatients ?? [])
                    );
                    ?>
                    <?php if (empty($allPatients)): ?>
                        <tr>
                            <td colspan="5" class="py-24">
                                <div class="flex flex-col items-center justify-center text-center text-gray-500">
                                    <i data-lucide="user-round-check" class="w-12 h-12 mb-3 opacity-20"></i>
                                    <p class="text-base font-medium text-gray-900">No records found</p>
                                    <p class="text-sm">There are no patient registrations.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($allPatients as $patient): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors record-row"
                                data-name="<?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>"
                                data-email="<?= htmlspecialchars($patient['email']) ?>"
                                data-date="<?= htmlspecialchars($patient['created_at']) ?>"
                                data-status="<?= $patient['queue_status'] ?>">
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-9 w-9 flex-shrink-0 bg-blue-50 border border-blue-200 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold">
                                            <?= htmlspecialchars(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)) ?>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-gray-900">
                                                <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>
                                            </div>
                                            <div class="text-[11px] text-gray-500">
                                                <?= htmlspecialchars($patient['age']) ?> yrs old •
                                                <?= htmlspecialchars($patient['sex']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="text-[13px] font-medium text-gray-900">
                                        <?= htmlspecialchars($patient['email']) ?>
                                    </div>
                                    <div class="text-[11px] text-gray-500">
                                        <?= htmlspecialchars($patient['contact_number'] ?: 'No contact number') ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-400">
                                        <?= htmlspecialchars($patient['branch_name'] ?: 'Not Specified') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-[13px] text-gray-500">
                                    <div class="font-medium text-gray-900">
                                        <?= date('F j, Y', strtotime($patient['created_at'])) ?>
                                    </div>
                                    <div class="text-[11px] opacity-70"><?= date('h:i A', strtotime($patient['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <form method="POST" action="" class="flex items-center justify-center gap-2">
                                        <input type="hidden" name="user_id" value="<?= $patient['user_id'] ?>">
                                        <?php if ($patient['queue_status'] === 'Pending'): ?>
                                            <button type="submit" name="action" value="Approve"
                                                class="p-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                                                title="Approve Registration">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                            <button type="submit" name="action" value="Reject"
                                                class="p-1.5 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Are you sure you want to reject this patient registration?');"
                                                title="Reject Registration">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        <?php else: ?>
                                            <div class="flex items-center gap-2">
                                                <button type="submit" name="action" value="Restore"
                                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors flex items-center gap-1"
                                                    onclick="return confirm('Are you sure you want to restore this rejected registration to pending?');"
                                                    title="Restore to Pending">
                                                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Undo Reject
                                                </button>
                                                <button type="submit" name="action" value="Delete"
                                                    class="p-1.5 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm"
                                                    onclick="return confirm('PERMANENT DELETE: This will completely remove this registration and free up the email. This action cannot be undone. Are you sure?');"
                                                    title="Permanently Delete Registration">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-6 py-4 mt-auto">
            <span id="record-count" class="text-xs font-medium text-gray-500">
                Found <?= count($pendingPatients) ?> records
            </span>
            <div class="flex items-center gap-4">
                <button id="prev-btn"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Previous
                </button>
                <span id="page-info" class="text-xs font-bold text-gray-700">Page 1 of 1</span>
                <button id="next-btn"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                    Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ROWS_PER_PAGE = 8;
        let currentPage = 1;
        let totalPages = 1;
        let currentTab = 'Pending';

        // Store all initial data rows in memory once
        let allRows = Array.from(document.querySelectorAll('tr.record-row'));

        const searchInput = document.getElementById('search-input');
        const sortDate = document.getElementById('sort-date');

        // Tab switching logic (outside realtime update boundary)
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.className = 'tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
                });
                e.currentTarget.className = 'tab-btn border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm';
                currentTab = e.currentTarget.getAttribute('data-tab');
                currentPage = 1;
                renderPage();
            });
        });

        // Static listeners
        if (searchInput) searchInput.addEventListener('input', () => { currentPage = 1; renderPage(); });
        if (sortDate) sortDate.addEventListener('change', () => { currentPage = 1; renderPage(); });

        // Event Delegation for pagination buttons (inside realtime-update boundary)
        document.addEventListener('click', (e) => {
            const prevBtn = e.target.closest('#prev-btn');
            const nextBtn = e.target.closest('#next-btn');

            if (prevBtn && !prevBtn.disabled) {
                if (currentPage > 1) { currentPage--; renderPage(); }
            } else if (nextBtn && !nextBtn.disabled) {
                if (currentPage < totalPages) { currentPage++; renderPage(); }
            }
        });

        // Listen to AJAX realtime updates
        document.addEventListener('realtime:updated', function () {
            // Capture freshly injected rows from PHP, but keep current page & tab state
            allRows = Array.from(document.querySelectorAll('tr.record-row'));
            renderPage();
        });

        function renderPage() {
            const tableBody = document.getElementById('table-body');
            if (!tableBody) return;

            const pageInfo = document.getElementById('page-info');
            const recordCountInfo = document.getElementById('record-count');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');

            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const sortOrder = sortDate ? sortDate.value : 'Newest';

            // Filter based on tab and search
            let filteredRows = allRows.filter(row => {
                if (row.getAttribute('data-status') !== currentTab) return false;
                const name = (row.getAttribute('data-name') || '').toLowerCase();
                const email = (row.getAttribute('data-email') || '').toLowerCase();
                return name.includes(searchTerm) || email.includes(searchTerm);
            });

            // Sort by date
            filteredRows.sort((a, b) => {
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return sortOrder === 'Newest' ? dateB - dateA : dateA - dateB;
            });

            const totalFiltered = filteredRows.length;
            totalPages = Math.max(1, Math.ceil(totalFiltered / ROWS_PER_PAGE));

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIdx = (currentPage - 1) * ROWS_PER_PAGE;
            const endIdx = startIdx + ROWS_PER_PAGE;
            const visibleRows = filteredRows.slice(startIdx, endIdx);

            // Clear existing DOM completely
            while (tableBody.firstChild) {
                tableBody.removeChild(tableBody.firstChild);
            }

            // Render empty state or rows
            if (totalFiltered === 0) {
                const emptyState = document.createElement('tr');
                emptyState.innerHTML = `
                <td colspan="5" class="py-24">
                    <div class="flex flex-col items-center justify-center text-center text-gray-500">
                        <i data-lucide="search-x" class="w-12 h-12 mb-3 opacity-20"></i>
                        <p class="text-base font-medium text-gray-900">No records to show</p>
                        <p class="text-sm">There are no patients in this view.</p>
                    </div>
                </td>`;
                tableBody.appendChild(emptyState);
                if (window.lucide) window.lucide.createIcons();
            } else {
                visibleRows.forEach(row => {
                    row.style.display = 'table-row';
                    tableBody.appendChild(row);
                });
            }

            // Update footer info
            if (pageInfo) pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            if (recordCountInfo) recordCountInfo.textContent = `Found ${totalFiltered} records`;

            if (prevBtn) prevBtn.disabled = currentPage <= 1;
            if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
        }

        renderPage();
    });
</script>