<!-- IT Global Audit Logs -->
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">System Audit logs</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time global monitoring of all system events and administrative
                actions.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 border border-indigo-100 rounded-full">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span class="text-[10px] font-black text-indigo-700 uppercase tracking-widest leading-none">Live
                    Monitoring</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <input type="hidden" name="page" value="audit-logs">

            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Search</label>
                <div class="relative group">
                    <i data-lucide="search"
                        class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    <input type="text" name="search" id="filter-search" value="<?= htmlspecialchars($filters['search']) ?>"
                        placeholder="Action, user, or details..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Role</label>
                <select name="role" id="filter-role"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none cursor-pointer">
                    <option value="">All Roles</option>
                    <?php foreach ($distinctRoles as $roleOption): ?>
                        <option value="<?= $roleOption ?>" <?= $filters['role'] == $roleOption ? 'selected' : '' ?>>
                            <?= strtoupper($roleOption) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Module Filter -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Category</label>
                <select name="module" id="filter-module"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none cursor-pointer">
                    <option value="">All Categories</option>
                    <?php foreach ($distinctModules as $modOption): ?>
                        <option value="<?= $modOption ?>" <?= $filters['module'] == $modOption ? 'selected' : '' ?>>
                            <?= strtoupper($modOption) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">From Date</label>
                <input type="date" name="start_date" id="filter-start" value="<?= htmlspecialchars($filters['start_date']) ?>"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 outline-none">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">To Date</label>
                <input type="date" name="end_date" id="filter-end" value="<?= htmlspecialchars($filters['end_date']) ?>"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 outline-none">
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Order</label>
                <select name="sort" id="filter-sort"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none cursor-pointer">
                    <option value="desc" <?= $filters['sort'] == 'desc' ? 'selected' : '' ?>>Newest First</option>
                    <option value="asc" <?= $filters['sort'] == 'asc' ? 'selected' : '' ?>>Oldest First</option>
                </select>
            </div>

            <!-- Reset -->
            <div class="flex items-end">
                <a href="?page=audit-logs"
                    class="w-full py-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition flex items-center justify-center gap-2 group"
                    title="Reset Filters">
                    <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest lg:hidden xl:inline">Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Actor</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Branch</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Category
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                            Info</th>
                    </tr>
                </thead>
                <tbody id="audit-logs-body" class="divide-y divide-gray-50">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-30">
                                    <i data-lucide="clipboard-list" class="w-16 h-16"></i>
                                    <p class="text-sm font-black uppercase tracking-widest">No audit records found matching
                                        your filters</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-black text-gray-900 tabular-nums">
                                    <?= date('M d, Y', strtotime($log['created_at'])) ?>
                                </span>
                                <p class="text-[10px] text-gray-400 font-bold">
                                    <?= date('h:i:s A', strtotime($log['created_at'])) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        <?= strtoupper(substr($log['user_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-gray-800 tracking-tight leading-none mb-1"><?= htmlspecialchars($log['user_name'] ?? 'System') ?></span>
                                        <span
                                            class="text-[9px] font-black <?= $log['user_role'] == 'it_admin' ? 'text-rose-500' : 'text-gray-400' ?> uppercase tracking-widest"><?= $log['user_role'] ?? 'AUTOMATED' ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 bg-gray-100 rounded text-[9px] font-black text-gray-500 uppercase tracking-widest"><?= htmlspecialchars($log['branch_name'] ?? 'GLOBAL') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="text-[10px] font-bold text-gray-600 uppercase tracking-tight"><?= htmlspecialchars($log['module'] ?? 'Unknown') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-gray-700 leading-relaxed max-w-xs">
                                    <?= htmlspecialchars($log['action']) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="showDetails(<?= htmlspecialchars(json_encode($log)) ?>)"
                                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                    <i data-lucide="info" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Showing <?= count($logs) ?> of <?= $total_count ?> entries
                </span>
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=audit-logs&p=<?= $i ?>&<?= http_build_query(array_filter($filters)) ?>"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all
                       <?= $page_num == $i ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white border border-gray-200 text-gray-500 hover:border-indigo-200' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Details Modal -->
<div id="logModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Action Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i data-lucide="x"
                    class="w-5 h-5"></i></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">IP Address</p>
                    <p id="modalIp" class="text-xs font-bold text-gray-700 tabular-nums">0.0.0.0</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Target Module</p>
                    <p id="modalModule" class="text-xs font-bold text-gray-700"></p>
                </div>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Detailed Logs</p>
                <div id="modalDetails"
                    class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-[11px] text-gray-600 font-medium leading-relaxed whitespace-pre-wrap">
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50/50 flex justify-end">
            <button onclick="closeModal()"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Close</button>
        </div>
    </div>
</div>

<script>
    function showDetails(log) {
        document.getElementById('modalIp').textContent = log.ip_address || 'Unknown';
        document.getElementById('modalModule').textContent = log.module || 'System';
        document.getElementById('modalDetails').textContent = log.details || 'No additional parameters provided.';

        const modal = document.getElementById('logModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('logModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- AJAX and Automatic Filtering ---
    let filterTimeout;
    const filterForm = document.querySelector('form');
    const tableBody = document.getElementById('audit-logs-body');
    const paginationContainer = document.querySelector('.bg-gray-50.border-t');

    function applyFilters(isAutoPoll = false) {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        // Don't show loading pulse for auto-polling to make it seamless
        if (!isAutoPoll) tableBody.classList.add('opacity-50');
        
        fetch(`index.php?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newBody = doc.getElementById('audit-logs-body');
            if (newBody) {
                // Only update if content actually changed (to prevent flickering)
                if (newBody.innerHTML !== tableBody.innerHTML) {
                    tableBody.innerHTML = newBody.innerHTML;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            }
            
            const newPagination = doc.querySelector('.bg-gray-50.border-t');
            if (paginationContainer && newPagination) {
                if (newPagination.innerHTML !== paginationContainer.innerHTML) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
            }
            
            if (!isAutoPoll) tableBody.classList.remove('opacity-50');
        })
        .catch(err => {
            console.error('Filter error:', err);
            if (!isAutoPoll) tableBody.classList.remove('opacity-50');
        });
    }

    // Real-time Polling (Every 5 seconds)
    const pollInterval = setInterval(() => {
        // Only poll if on page 1 (no 'p' param or p=1)
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('p') || '1';
        
        // Also don't poll if user is currently typing in search
        const isTyping = document.activeElement === document.getElementById('filter-search');
        
        if (currentPage === '1' && !isTyping) {
            applyFilters(true);
        }
    }, 5000);

    // Add listeners to all form fields
    filterForm.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', () => {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 300);
        });
        el.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            applyFilters();
        });
    });

    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        applyFilters();
    });

    // Handle pagination via AJAX
    document.addEventListener('click', (e) => {
        const pageLink = e.target.closest('.flex.gap-2 a');
        if (pageLink && pageLink.href.includes('page=audit-logs')) {
            e.preventDefault();
            const url = new URL(pageLink.href);
            const params = url.searchParams;
            
            // Update URL in browser for reference
            window.history.pushState({}, '', `index.php?${params.toString()}`);
            
            tableBody.classList.add('opacity-50');
            fetch(`index.php?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newBody = doc.getElementById('audit-logs-body');
                if (newBody) tableBody.innerHTML = newBody.innerHTML;
                const newPagination = doc.querySelector('.bg-gray-50.border-t');
                if (paginationContainer && newPagination) paginationContainer.innerHTML = newPagination.innerHTML;
                tableBody.classList.remove('opacity-50');
                if (typeof lucide !== 'undefined') lucide.createIcons();
                tableBody.closest('.bg-white').scrollIntoView({ behavior: 'smooth' });
            });
        }
    });

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>