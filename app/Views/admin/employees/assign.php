<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('admin/components/toast') ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Assign Employees to Stations</h1>
    <a href="<?= site_url('admin/employees') ?>" class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">← Back</a>
</div>

<div class="h-1 bg-red-600 flex-grow mb-6"></div>

<div class="flex justify-end mb-4">
    <div class="w-1/3">
        <input id="employeeSearch" type="text" placeholder="Search employees by name or role..." class="border p-2 rounded w-full" />
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2">
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="font-semibold mb-4">Employees (<?= count($employees ?? []) ?>)</h2>

            <div id="employeesList" class="grid grid-cols-1 gap-3">
                <?php foreach ($employees ?? [] as $emp): ?>
                    <?php $fullName = esc($emp['first_name'] . ' ' . $emp['last_name']); ?>
                    <div class="employee-card bg-gray-50 p-3 rounded flex items-center justify-between" data-name="<?= strtolower($fullName) ?>" data-role="<?= strtolower($emp['role'] ?? '') ?>">
                        <div>
                            <span class="text-lg font-medium text-black-800 hover:underline" style="cursor: pointer;" onclick="fetchEmployeeDetails(<?= $emp['id'] ?>)">
                                <?= $fullName ?>
                            </span>
                            <div class="text-sm text-gray-500">Role: <?= esc($emp['role'] ?? 'Staff') ?></div>
                            <?php $assigned = $assignments[$emp['id']] ?? null;
                            if ($assigned):
                                $st = $stationsMap[$assigned['station_id']] ?? null; ?>
                                <div class="text-sm text-gray-500 assigned-info">Assigned: <?= esc($st['name'] ?? 'Station #' . $assigned['station_id']) ?><?php if (!empty($st['bay_no'])): ?> — Bay <?= esc($st['bay_no']) ?><?php endif; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div>
                                <?php $status = $emp['status'] ?? 'inactive'; ?>
                                <span class="px-2 py-1 rounded text-white text-xs <?= $status === 'active' ? 'bg-green-600' : ($status === 'maintenance' ? 'bg-yellow-500' : 'bg-red-600') ?>">
                                    <?= ucfirst($status) ?>
                                </span>
                            </div>
                            <div>
                                <form class="assignForm" method="post" action="<?= site_url('admin/employees/assign') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="employee_id" value="<?= $emp['id'] ?>" />
                                    <select name="station_id" class="border rounded p-1 text-sm" required>
                                        <option value="">Assign to station...</option>
                                        <?php foreach ($stations ?? [] as $st): ?>
                                            <option value="<?= $st['id'] ?>"><?= esc($st['name']) ?> - Bay <?= esc($st['bay_no']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="ml-2 bg-blue-600 text-white text-sm px-3 py-1 rounded">Assign</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <aside class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold mb-3">Stations</h2>
        <div class="space-y-2">
            <?php if (!empty($stations)): ?>
                <?php foreach ($stations as $st): ?>
                    <div class="p-2 border rounded flex justify-between items-center">
                        <div>
                            <div class="font-medium"><?= esc($st['name']) ?></div>
                            <div class="text-sm text-gray-500">Bay <?= esc($st['bay_no']) ?> — Capacity <?= esc($st['capacity']) ?></div>
                        </div>
                        <div class="text-sm text-gray-600">Status: 
                        <?php if ($st['status'] === 'active'): ?>
                            <span class="px-2 py-1 rounded text-white text-xs bg-green-600">Active</span>
                        <?php endif; ?>
                        <?php if ($st['status'] === 'maintenance'): ?>
                            <span class="px-2 py-1 rounded text-white text-xs bg-yellow-500">Maintenance</span>
                        <?php endif; ?>
                        <?php if ($st['status'] === 'inactive'): ?>
                            <span class="px-2 py-1 rounded text-white text-xs bg-red-600">Inactive</span>
                        <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-gray-500">No stations found</div>
            <?php endif; ?>
        </div>
    </aside>
</div>


<div id="employeeDetails"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">

    <div class="bg-white rounded-xl w-full max-w-3xl mx-4 overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-xl font-semibold">Employee Details</h2>
            <button onclick="closeDetails()" class="text-gray-500 hover:text-gray-800 text-2xl leading-none">&times;</button>
        </div>

        <!-- Tabs -->
        <div class="px-6 pt-4">
            <div class="flex gap-3">
                <button id="tabProfile"
                    onclick="showTab('profile')"
                    class="px-4 py-2 rounded-lg bg-gray-900 text-white">
                    Profile
                </button>

                <button id="tabAssign"
                    onclick="showTab('assign')"
                    class="px-4 py-2 rounded-lg bg-gray-200 text-gray-800">
                    Assigned Details
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-5">

            <!-- PROFILE TAB -->
            <div id="profileTab">
                <div class="space-y-2">
                    <div id="employeeName" class="text-lg font-medium"></div>
                    <div id="employeeId" class="text-gray-600"></div>
                    <div id="employeeRole" class="text-gray-600"></div>
                    <div id="employeeEmail" class="text-gray-600"></div>
                    <div id="employeePhone" class="text-gray-600"></div>
                    <div id="employeeStatus" class="text-gray-600"></div>



                </div>
            </div>

            <!-- ASSIGN TAB -->
            <div id="assignTab" class="hidden">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg font-semibold">Assignment History</div>
                    <div id="currentAssignBadge" class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-700 hidden">
                        Current assignment
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="p-3">Station</th>
                                <th class="p-3">Bay</th>
                                <th class="p-3">Primary</th>
                                <th class="p-3">Assigned At</th>
                                <th class="p-3">Station Status</th>
                            </tr>
                        </thead>
                        <tbody id="assignTableBody">
                            <!-- rows injected by JS -->
                        </tbody>
                    </table>
                </div>

                <div id="noAssignMsg" class="text-red-600 mt-3 hidden">
                    No assignments found for this employee.
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t flex justify-end">
            <button onclick="closeDetails()" class="bg-red-600 text-white px-5 py-2 rounded-lg">
                Close
            </button>
        </div>

    </div>
</div>










<script>
    // Client-side search/filter
    (function() {
        const search = document.getElementById('employeeSearch');
        const list = document.getElementById('employeesList');
        if (!search || !list) return;

        search.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            const cards = list.querySelectorAll('.employee-card');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                const role = card.getAttribute('data-role') || '';
                if (!q || name.includes(q) || role.includes(q)) card.style.display = '';
                else card.style.display = 'none';
            });
        });
    })();

    // AJAX submit with robust JSON handling
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.assignForm');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const currentForm = this;
                const formData = new FormData(this);

                fetch('<?= site_url('admin/employees/assign') ?>', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.text())
                    .then(text => {
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                showToast(data.message, 'success');
                                // Update assigned station in the UI for this employee
                                const assignment = data.assignment || {};
                                const empId = assignment.employee_id || currentForm.querySelector('input[name="employee_id"]').value;
                                const card = currentForm.closest('.employee-card');
                                if (card) {
                                    let info = card.querySelector('.assigned-info');
                                    if (!info) {
                                        info = document.createElement('div');
                                        info.className = 'text-sm text-gray-500 assigned-info';
                                        // append under the left column (first div inside card)
                                        const left = card.querySelector('div');
                                        if (left) left.appendChild(info);
                                        else card.appendChild(info);
                                    }
                                    const stationLabel = assignment.station_name ? assignment.station_name + (assignment.bay_no ? ' — Bay ' + assignment.bay_no : '') : ('Station #' + (assignment.station_id || ''));
                                    info.textContent = 'Assigned: ' + stationLabel;
                                }
                                currentForm.reset();
                            } else {
                                showToast(data.message, 'error');
                            }
                        } catch (err) {
                            console.error('Non-JSON response from server:', text);
                            showToast('Server returned non-JSON response. Check devtools network.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        showToast('An error occurred: ' + error.message, 'error');
                    });
            });
        });
    });

    function showTab(tab) {
        const profileTab = document.getElementById('profileTab');
        const assignTab = document.getElementById('assignTab');

        const tabProfileBtn = document.getElementById('tabProfile');
        const tabAssignBtn = document.getElementById('tabAssign');

        if (tab === 'profile') {
            profileTab.classList.remove('hidden');
            assignTab.classList.add('hidden');

            tabProfileBtn.classList.add('bg-gray-900', 'text-white');
            tabProfileBtn.classList.remove('bg-gray-200', 'text-gray-800');

            tabAssignBtn.classList.add('bg-gray-200', 'text-gray-800');
            tabAssignBtn.classList.remove('bg-gray-900', 'text-white');
        } else {
            assignTab.classList.remove('hidden');
            profileTab.classList.add('hidden');

            tabAssignBtn.classList.add('bg-gray-900', 'text-white');
            tabAssignBtn.classList.remove('bg-gray-200', 'text-gray-800');

            tabProfileBtn.classList.add('bg-gray-200', 'text-gray-800');
            tabProfileBtn.classList.remove('bg-gray-900', 'text-white');
        }
    }

    function fetchEmployeeDetails(employeeId) {
        fetch(`<?= site_url('admin/employees/getEmployeeDetails') ?>/${employeeId}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    return;
                }

                // --- PROFILE ---
                const e = data.employee;
                document.getElementById('employeeName').textContent = `Name: ${e.first_name} ${e.last_name}`;
                document.getElementById('employeeId').textContent = `Employee ID: ${e.id}`;
                document.getElementById('employeeRole').textContent = `Role: ${e.role}`;
                document.getElementById('employeeEmail').textContent = `Email: ${e.email}`;
                document.getElementById('employeePhone').textContent = `Phone: ${e.phone}`;



                const statusWrap = document.getElementById('employeeStatus');

                const rawStatus = e.status ?? ''; // don't trim text (as you asked)
                const statusCheck = String(rawStatus).trim().toLowerCase(); // only for checking

                statusWrap.innerHTML = `Status: <span id="statusBadge">${rawStatus}</span>`;

                const badgenew = document.getElementById('statusBadge');

                // reset classes (important when changing employee)
                badgenew.className = 'px-2 py-1 rounded text-white inline-block';

                if (statusCheck === 'active') {
                    badgenew.classList.add('bg-green-700');
                } else {
                    badgenew.classList.add('bg-red-700');
                }




                // --- ASSIGN HISTORY ---
                const tbody = document.getElementById('assignTableBody');
                const noAssignMsg = document.getElementById('noAssignMsg');
                const badge = document.getElementById('currentAssignBadge');


                const assignments = data.assignments || [];
                const currentId = data.current_assignment_id; // from backend

                if (assignments.length === 0) {
                    noAssignMsg.classList.remove('hidden');
                    badge.classList.add('hidden');
                } else {
                    noAssignMsg.classList.add('hidden');
                    badge.classList.remove('hidden');

                    assignments.forEach(a => {
                        const isCurrent = (currentId && Number(a.id) === Number(currentId));

                        const tr = document.createElement('tr');
                        tr.className = isCurrent ? 'bg-green-50 border-l-4 border-green-500' : 'border-t';

                        tr.innerHTML = `
            <td class="p-3 font-medium ${isCurrent ? 'text-green-800' : ''}">${a.station_name ?? 'N/A'}</td>
            <td class="p-3">${a.bay_no ?? 'N/A'}</td>
            <td class="p-3">${Number(a.is_primary) === 1 ? 'Yes' : 'No'}</td>
            <td class="p-3">${a.assigned_at ?? 'N/A'}</td>
            <td class="p-3">${a.station_status ?? 'N/A'}</td>
          `;

                        tbody.appendChild(tr);
                    });
                }

                // Default show Profile tab when opening
                showTab('profile');

                // ✅ show modal (keep centered)
                document.getElementById('employeeDetails').classList.remove('hidden');
            })
            .catch(err => {
                console.error(err);
                alert('Error fetching employee details');
            });
    }

    function closeDetails() {
        document.getElementById('employeeDetails').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>