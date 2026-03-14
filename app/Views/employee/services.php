<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/ajax_toast') ?>

<div class="container mx-auto  p-6 ">
    <!-- Page Header -->
    <div class=" flex items-center justify-between">
        <div>
            <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title ?? 'Services') ?></h1>
        </div>
    </div>
    <div class="mb-4 h-1 bg-red-600"></div>

    <?php if (empty($active)) : ?>
        <!-- Empty State -->
        <div class="container mx-auto p-6  ">
            <div class="mt-8 bg-white border rounded-2xl p-10 text-center shadow-sm md ">
                <div class="mx-auto w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h2m-6 6h6m-6 0a3 3 0 01-3-3V7a3 3 0 013-3h6a3 3 0 013 3v7a3 3 0 01-3 3H9z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mt-4">No active job in progress</h2>
                <p class="text-gray-600 mt-2">Approve a booking to start the bay processing steps.</p>
            </div>
        </div>
    <?php else : ?>

        <!-- FULL-WIDTH HERO CARD -->
        <div class="mt-8 rounded-2xl border bg-white shadow-sm overflow-hidden mb-6">
            <div class="p-6 md:p-8 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <!-- Left: Station + Booking Summary -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    <?= esc($active['station_name'] ?? 'Station') ?>
                                    <?php if (!empty($active['bay_no'])) : ?>
                                        <span class="text-base font-semibold text-gray-500">(Bay <?= esc($active['bay_no']) ?>)</span>
                                    <?php endif; ?>
                                </h2>
                                <p class="text-gray-500 mt-1">Active job details</p>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border bg-blue-100 text-blue-700 border-blue-200">
                                <?= esc($active['assignment_status'] ?? '-') ?>
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                            <div class="rounded-xl border bg-white p-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Vehicle</p>
                                <p class="mt-1 text-lg font-bold text-gray-900"><?= esc($active['vehicle_model'] ?? '-') ?></p>
                            </div>
                            <div class="rounded-xl border bg-white p-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Service</p>
                                <p class="mt-1 text-lg font-bold text-gray-900"><?= esc($active['service'] ?? '-') ?></p>
                            </div>
                            <div class="rounded-xl border bg-white p-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Booking Date</p>
                                <p class="mt-1 text-lg font-bold text-gray-900"><?= esc($active['booking_date'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Times + Actions -->
                    <div class="w-full lg:w-[420px]">
                        <div class="rounded-2xl border bg-white p-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="rounded-xl bg-gray-50 p-4 border">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Started At</p>
                                    <p id="startedAtText" class="mt-1 font-bold text-gray-900">
                                        <?= esc($active['started_at'] ?? '-') ?>
                                    </p>
                                </div>
                                <div class="rounded-xl bg-gray-50 p-4 border">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Finished At</p>
                                    <p id="finishedAtText" class="mt-1 font-bold text-gray-900">
                                        <?= esc($active['completed_at'] ?? '-') ?>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <button id="startProcessBtn"
                                    type="button"
                                    class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 disabled:opacity-60"
                                    <?= !empty($active['started_at']) ? 'disabled' : '' ?>
                                    onclick="startProcess(<?= esc($active['assignment_id']) ?>)">
                                    Start Process
                                </button>


                                <button id="assignNextBtn" type="button"
                                    class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white bg-yellow-500 hover:bg-yellow-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                    <?= ($active['assignment_status'] !== 'completed') ? 'disabled' : '' ?>
                                    onclick="assignNext(<?= esc($active['assignment_id']) ?>)">
                                    Assign Next
                                </button>


                                <button id="finishProcessBtn"
                                    type="button"
                                    class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    <?= (empty($active['started_at']) || $active['assignment_status'] === 'completed') ? 'disabled' : '' ?>
                                    onclick="finishProcess(<?= esc($active['assignment_id']) ?>)">
                                    Finish Process
                                </button>


                            </div>

                            <p class="text-xs text-gray-500 mt-3">
                                Finish is allowed only when all steps are <span class="font-semibold">Done</span> or <span class="font-semibold">Skipped</span> and assigned to next employee.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Steps + Spare Parts (2-column) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 ">

            <!-- LEFT: Steps (2 columns) -->
            <div class="lg:col-span-2">
                <div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Process Steps</h3>
                            <p class="text-gray-500 text-sm mt-1">Mark each step as done or skip when not applicable.</p>
                        </div>

                        <div class="text-sm font-semibold text-gray-700">
                            <span id="doneCount">0</span>/<span id="totalCount"><?= count($steps ?? []) ?></span> completed
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-t border-b border-gray-200 uppercase text-xs tracking-wide">
                                <tr>
                                    <th class="p-4 text-gray-700 font-bold w-20">Step</th>
                                    <th class="p-4 text-gray-700 font-bold">Process</th>
                                    <th class="p-4 text-gray-700 font-bold w-48">Status</th>
                                    <th class="p-4 text-gray-700 font-bold w-56 text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                <?php if (!empty($steps)) : ?>
                                    <?php foreach ($steps as $step) : ?>
                                        <?php
                                        $status = strtolower($step['status'] ?? 'pending');

                                        $statusClass = match ($status) {
                                            'pending' => 'bg-gray-100 text-gray-700 border-gray-200',
                                            'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'done' => 'bg-green-100 text-green-700 border-green-200',
                                            'skipped' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };

                                        $jobStepId = (int)($step['job_step_id'] ?? 0);
                                        $isFinal   = in_array($status, ['done', 'skipped'], true);
                                        ?>

                                        <tr class="hover:bg-gray-50" id="row-<?= esc($jobStepId) ?>" data-status="<?= esc($status) ?>">
                                            <td class="p-4 font-extrabold text-gray-900"><?= esc($step['sequence_no'] ?? '-') ?></td>
                                            <td class="p-4">
                                                <div class="font-bold text-gray-900"><?= esc($step['title'] ?? '-') ?></div>
                                                <div class="text-xs text-gray-500 mt-1">Step #<?= esc($step['sequence_no'] ?? '-') ?> in workflow</div>
                                            </td>

                                            <td class="p-4">
                                                <span id="badge-<?= esc($jobStepId) ?>"
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border <?= $statusClass ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                                </span>
                                            </td>

                                            <td class="p-4">
                                                <div id="actions-<?= esc($jobStepId) ?>" class="flex justify-center gap-2">
                                                    <button type="button"
                                                        class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 disabled:opacity-60"
                                                        <?= $isFinal ? 'disabled' : '' ?>
                                                        onclick="doneStep(<?= esc($jobStepId) ?>)">
                                                        Done
                                                    </button>

                                                    <button type="button"
                                                        class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-yellow-500 hover:bg-yellow-600 disabled:opacity-60"
                                                        <?= $isFinal ? 'disabled' : '' ?>
                                                        onclick="skipStep(<?= esc($jobStepId) ?>)">
                                                        Skip
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-500">
                                            No steps configured for this station type.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- RIGHT: Spare Part Usage -->
            <div class="lg:col-span-1">
                <div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
                    <div class="p-5 border-b">
                        <h3 class="text-lg font-bold text-gray-900">Spare Part Usage</h3>
                        <p class="text-sm text-gray-500 mt-1">Select category → item → qty → add</p>
                    </div>

                    <div class="p-5 space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 uppercase gap-2">Category</label>
                            <select id="spCategory" class="w-full border rounded px-3 py-2 text-sm ">
                                <option value=""> -- Select Category --</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-600 uppercase">Item</label>
                            <select id="spItem" class="w-full border rounded px-3 py-2 text-sm ">
                                <option value=""> -- Select Item --</option>
                            </select>
                            <div class="text-xs text-gray-500 mt-1" id="spStockInfo"></div>
                        </div>

                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-600 uppercase">Qty</label>
                                <input id="spQty" type="number" min="1" value="1" class="mt-1 w-full rounded-xl border-gray-300" />
                            </div>
                            <div class="flex items-end">
                                <button id="btnAddSpare"
                                    class="px-4 py-2 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700">
                                    Add
                                </button>
                            </div>
                        </div>

                        <div class="pt-3 border-t">
                            <div class="font-bold text-gray-900 mb-2">Added Items</div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="text-xs uppercase text-gray-500 border-b">
                                        <tr>
                                            <th class="py-2 text-left">Item</th>
                                            <th class="py-2 text-center w-14">Qty</th>
                                            <th class="py-2 text-right w-16">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody id="usageRows" class="divide-y"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

</div>

<!-- Use Part Modal -->
<div id="usePartModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-lg w-full shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-gray-900">Add Used Part</h2>
                <p class="text-gray-500 text-sm mt-1">Record part usage for this booking.</p>
            </div>
            <button type="button" onclick="closeUsePartModal()" class="text-gray-500 hover:text-gray-700 font-bold">✕</button>
        </div>

        <div class="h-1 bg-red-600 mx-auto mt-4 mb-6"></div>

        <form id="usePartForm" action="<?= site_url('employee/spare/use') ?>" method="POST" class="space-y-4">
            <input type="hidden" name="booking_id" value="<?= esc($active['booking_id'] ?? '') ?>">
            <input type="hidden" name="station_id" value="<?= esc($active['station_id'] ?? '') ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Part</label>
                <select name="spare_part_id" class="w-full border rounded-xl p-3">
                    <option value="">Select part</option>
                    <?php foreach (($stationParts ?? []) as $p) : ?>
                        <option value="<?= esc($p['id']) ?>">
                            <?= esc($p['name']) ?> (Stock: <?= esc($p['stock_qty']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Qty</label>
                    <input type="number" min="1" name="qty" value="1" class="w-full border rounded-xl p-3">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Note</label>
                    <input type="text" name="note" class="w-full border rounded-xl p-3" placeholder="Optional">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button"
                    class="px-5 py-3 bg-gray-100 text-gray-800 rounded-xl font-bold hover:bg-gray-200"
                    onclick="closeUsePartModal()">
                    Cancel
                </button>

                <button id="usePartSubmitBtn" type="submit"
                    class="px-5 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Assign Next Modal -->
<div id="openassignNextModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-xl w-full shadow-lg">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Assign Next Station</h2>
                <p class="text-gray-500 text-sm mt-1">Handover this booking to another station and employee.</p>
            </div>
            <button type="button" onclick="closeassignNextModal()" class="text-gray-500 hover:text-gray-700 font-bold">✕</button>
        </div>

        <div class="h-1 bg-red-600 mx-auto mt-4 mb-6"></div>

        <form id="assignForm" action="<?= site_url('employee/assign_next') ?>" method="POST">
            <input type="hidden" name="assignment_id" id="assignmentIdInput" value="">
            <input type="hidden" name="booking_id" value="<?= esc($active['booking_id'] ?? '') ?>">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Station</label>
                    <select name="station_id" id="stationSelect" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200">
                        <option value="">Select station</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Employee</label>
                    <select name="employee_id" id="employeeSelect" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200">
                        <option value="">Select employee</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Note</label>
                <textarea name="note" class="w-full border rounded-xl p-3 focus:ring-2 focus:ring-red-200" rows="4"
                    placeholder="Add note for next station..."></textarea>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    class="px-5 py-3 bg-gray-100 text-gray-800 rounded-xl font-bold hover:bg-gray-200"
                    onclick="closeassignNextModal()">
                    Cancel
                </button>

                <button id="assignSubmitBtn" type="submit"
                    class="px-5 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">
                    Assign
                </button>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Utility function for POST actions
        async function postAction(url, payload) {
            const fd = new FormData();
            Object.keys(payload).forEach(k => fd.append(k, payload[k]));
            fd.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");

            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: fd
            });

            return res.json();
        }



        function updateProgressCounter() {
            const rows = document.querySelectorAll('tr[id^="row-"]');
            let total = 0,
                done = 0;
            rows.forEach(r => {
                total++;
                const s = (r.getAttribute('data-status') || '').toLowerCase();
                if (s === 'done' || s === 'skipped') done++;
            });
            const doneEl = document.getElementById('doneCount');
            const totalEl = document.getElementById('totalCount');
            if (doneEl) doneEl.innerText = done;
            if (totalEl) totalEl.innerText = total;


        }

        // --- 1. Start Process Logic ---
        window.startProcess = async function(assignmentId) {
            try {
                const data = await postAction("<?= site_url('employee/process/start') ?>", {
                    assignment_id: assignmentId
                });

                if (data.success) {
                    document.getElementById('startedAtText').innerText = data.started_at || '-';
                    document.getElementById('startProcessBtn').disabled = true;
                    // Enable the Finish button now that we started
                    document.getElementById('finishProcessBtn').disabled = false;
                    showToast(data.message, "success");
                }
            } catch (e) {
                console.error(e);
            }
        };

        // --- 2. Finish Process Logic (Your specific idea) ---
        window.finishProcess = async function(assignmentId) {
            // Local Validation: check counter before even sending the request
            const done = parseInt(document.getElementById('doneCount').innerText);
            const total = parseInt(document.getElementById('totalCount').innerText);

            if (done < total) {
                showToast("Please complete or skip all steps first!", "error");
                return;
            }

            try {
                const data = await postAction("<?= site_url('employee/process/finish') ?>", {
                    assignment_id: assignmentId
                });

                if (data.success) {

                    const finishedText = document.getElementById('finishedAtText');
                    if (finishedText) finishedText.innerText = data.completed_at;


                    // Disable Spare Part Adding
                    const btnAddSpare = document.getElementById('btnAddSpare');
                    if (btnAddSpare) {
                        btnAddSpare.disabled = true;
                        btnAddSpare.classList.add("opacity-50", "cursor-not-allowed");
                    }

                    // Disable the Finish button itself
                    const finishBtn = document.getElementById('finishProcessBtn');
                    finishBtn.disabled = true;

                    // IMPORTANT: Enable the Assign Next button
                    const assignNextBtn = document.getElementById('assignNextBtn');
                    if (assignNextBtn) assignNextBtn.disabled = false;

                    showToast("Station finished! You can now handover.", "success");

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showToast(data.message, "error");
                }
            } catch (e) {
                console.error(e);
            }
        };


        // Step actions
        window.doneStep = async function(stepId) {
            try {
                const data = await postAction("<?= site_url('employee/jobstep/done') ?>", {
                    job_step_id: stepId
                });

                if (data.success) {
                    updateBadge(stepId, 'done');
                    disableStepActions(stepId);
                    showToast(data.message || "Step done", "success");
                } else {
                    showToast(data.message || "Failed", "error");
                }
            } catch (e) {
                console.error(e);
                showToast(e.message || "Error", "error");
            }
        };

        window.skipStep = async function(stepId) {
            try {
                const data = await postAction("<?= site_url('employee/jobstep/skip') ?>", {
                    job_step_id: stepId
                });

                if (data.success) {
                    updateBadge(stepId, 'skipped');
                    disableStepActions(stepId);
                    showToast(data.message || "Step skipped", "success");
                } else {
                    showToast(data.message || "Failed", "error");
                }
            } catch (e) {
                console.error(e);
                showToast(e.message || "Error", "error");
            }
        };


        function updateBadge(stepId, status) {
            const badge = document.getElementById('badge-' + stepId);
            const row = document.getElementById('row-' + stepId);
            if (!badge || !row) return;

            const s = (status || '').toLowerCase();
            let cls = 'bg-gray-100 text-gray-700 border-gray-200';
            if (s === 'done') cls = 'bg-green-100 text-green-700 border-green-200';
            if (s === 'skipped') cls = 'bg-yellow-100 text-yellow-700 border-yellow-200';

            badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border ' + cls;
            badge.innerText = s.toUpperCase();
            row.setAttribute('data-status', s);

            updateProgressCounter();
        }

        function disableStepActions(stepId) {
            const container = document.getElementById('actions-' + stepId);
            if (container) {
                container.querySelectorAll('button').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50');
                });
            }
        }
        // Modal open/close 


        // Submit (AJAX)
        const assignForm = document.getElementById('assignForm');
        const stationsSelect = document.getElementById('stationSelect');
        const employeesSelect = document.getElementById('employeeSelect');
        const assignModal = document.getElementById('openassignNextModal');

        function csrfFormData() {
            const fd = new FormData();
            fd.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");
            return fd;
        }

        async function loadStations() {
            try {
                const res = await fetch("<?= site_url('employee/services/stations') ?>", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: csrfFormData()
                });
                const data = await res.json();

                if (data.success) {
                    stationsSelect.innerHTML = `<option value="">-- Select Station --</option>`;
                    data.stations.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = `${s.name} - Bay ${s.bay_no}`;
                        stationsSelect.appendChild(opt);
                    });
                }
            } catch (e) {
                showToast("Error loading stations", "error");
            }
        }

        async function loadEmployees(stationId) {
            if (!stationId) return;
            try {
                const res = await fetch("<?= site_url('employee/services/employees') ?>?station_id=" + stationId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();

                if (data.success) {
                    employeesSelect.innerHTML = `<option value="">-- Select Employee --</option>`;
                    data.employees.forEach(emp => {
                        const opt = document.createElement('option');
                        opt.value = emp.id;
                        opt.textContent = `${emp.first_name} ${emp.last_name}`;
                        employeesSelect.appendChild(opt);
                    });
                }
            } catch (e) {
                showToast("Error loading employees", "error");
            }
        }

        // --- 3. Modal Handover Logic ---

        window.assignNext = async function(assignmentId) {
            const finishBtn = document.getElementById('finishProcessBtn');

            // Logic Guard: Only handover if Finished
            if (finishBtn && !finishBtn.disabled) {
                showToast("You must click Finish Process before Handover.", "error");
                return;
            }

            const inputId = document.getElementById('assignmentIdInput');
            if (inputId) inputId.value = assignmentId;

            assignModal.classList.replace('hidden', 'flex');
            await loadStations(); // Trigger first load
        };

        window.closeassignNextModal = () => {
            assignModal.classList.replace('flex', 'hidden');
        };

        // When station dropdown changes, fetch its employees
        stationsSelect?.addEventListener('change', (e) => loadEmployees(e.target.value));

        // Handle the final Assign POST
        if (assignForm) {
            assignForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const btn = document.getElementById('assignSubmitBtn');
                btn.disabled = true;
                btn.innerText = 'Assigning...';

                try {
                    const res = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(this)
                    });
                    const data = await res.json();

                    if (data.success) {
                        showToast("Handover Successful!", "success");
                        closeassignNextModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message, "error");
                        btn.disabled = false;
                        btn.innerText = 'Assign';
                    }
                } catch (e) {
                    showToast("Network Error", "error");
                    btn.disabled = false;
                }
            });
        }





        // Spare part usage logic
        const bookingId = <?= (int)($active['booking_id'] ?? 0) ?>;
        const stationId = <?= (int)($active['station_id'] ?? 0) ?>;

        const spCategory = document.getElementById('spCategory');
        const spItem = document.getElementById('spItem');
        const spQty = document.getElementById('spQty');
        const spStockInfo = document.getElementById('spStockInfo');
        const btnAddSpare = document.getElementById('btnAddSpare');
        const usageRows = document.getElementById('usageRows');

        function csrfFormData() {
            const fd = new FormData();
            fd.append("<?= csrf_token() ?>", "<?= csrf_hash() ?>");
            return fd;
        }

        async function loadCategories() {
            const res = await fetch("<?= site_url('employee/spare/categories') ?>?station_id=" + stationId, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            const data = await res.json();
            if (!data.success) return showToast(data.message || "Failed to load categories", "error");

            spCategory.innerHTML = `<option value="">-- Select Category --</option>`;
            (data.categories || []).forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                spCategory.appendChild(opt);
            });
        }

        async function loadItems(categoryId) {
            spItem.innerHTML = `<option value="">-- Select Item --</option>`;
            spStockInfo.textContent = "";

            if (!categoryId) return;

            const url = "<?= site_url('employee/spare/items') ?>" + "?category_id=" + categoryId + "&station_id=" + stationId;
            const res = await fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            const data = await res.json();

            if (!data.success) return showToast(data.message || "Failed to load items", "error");

            (data.items || []).forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.dataset.stock = p.stock_qty;
                opt.dataset.price = p.price;
                opt.textContent = `${p.name} (${p.sku ?? ''})`;
                spItem.appendChild(opt);
            });
        }

        function updateStockInfo() {
            const selected = spItem.options[spItem.selectedIndex];
            if (!selected || !selected.value) {
                spStockInfo.textContent = "";
                return;
            }

            const stock = parseInt(selected.dataset.stock ?? 0);
            const price = selected.dataset.price ?? "-";

            const stockColor = (stock < 5) ? 'text-red-600 font-bold' : 'text-gray-500';

            spStockInfo.innerHTML = `
        <span class="${stockColor}">Stock: ${stock}</span> | 
        <span class="text-gray-500">Price: LKR ${price}</span>
    `;
        }

        async function loadUsage() {
            const url = "<?= site_url('employee/spare/usage') ?>" + "?booking_id=" + bookingId + "&station_id=" + stationId;
            const res = await fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });
            const data = await res.json();
            if (!data.success) return;

            renderUsageRows(data.rows || []);
        }

        function renderUsageRows(rows) {
            usageRows.innerHTML = "";
            rows.forEach(r => addUsageRow(r.id, r.part_name, r.qty));
        }

        function addUsageRow(usageId, partName, qty) {
            const tr = document.createElement('tr');
            tr.id = "usage-row-" + usageId;
            tr.innerHTML = `
                       <td class="py-2 text-left font-semibold text-gray-800">${partName}</td>
                       <td class="py-2 text-center font-semibold text-gray-800">${qty}</td>
                       <td class="py-2 text-right">
                          <button class="text-red-600 font-bold hover:text-red-700" onclick="removeUsage(${usageId})">×</button>
                          </td>
                             `;
            usageRows.appendChild(tr);
        }

        // expose remove function
        window.removeUsage = async function(usageId) {
            try {
                const fd = csrfFormData();
                fd.append('usage_id', usageId);

                const res = await fetch("<?= site_url('employee/spare/use/remove') ?>", {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: fd
                });

                const data = await res.json();
                if (!data.success) return showToast(data.message || "Failed to remove", "error");

                const row = document.getElementById("usage-row-" + usageId);
                if (row) row.remove();

                showToast(data.message || "Removed", "success");

                // refresh items to update stock display
                const catId = spCategory.value;
                await loadItems(catId);
                updateStockInfo();

            } catch (e) {
                console.error(e);
                showToast(e.message || "Error", "error");
            }
        }

        // Add usage
        btnAddSpare?.addEventListener('click', async () => {
            const partId = spItem.value;
            const qty = parseInt(spQty.value || "1", 10);

            if (!partId) return showToast("Select an item first", "error");
            if (qty < 1) return showToast("Qty must be at least 1", "error");

            btnAddSpare.disabled = true;
            btnAddSpare.textContent = "Adding...";

            try {
                const fd = csrfFormData();
                fd.append('booking_id', bookingId);
                fd.append('station_id', stationId);
                fd.append('spare_part_id', partId);
                fd.append('qty', qty);

                const res = await fetch("<?= site_url('employee/spare/use') ?>", {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: fd
                });

                const data = await res.json();
                if (!data.success) return showToast(data.message || "Failed to add", "error");

                addUsageRow(data.usage.id, data.usage.part_name, data.usage.qty);
                showToast(data.message || "Added", "success");

                // refresh item list/stock view
                const catId = spCategory.value;
                await loadItems(catId);
                updateStockInfo();

            } catch (e) {
                console.error(e);
                showToast(e.message || "Error", "error");
            } finally {
                btnAddSpare.disabled = false;
                btnAddSpare.textContent = "Add";
            }
        });

        // Events
        spCategory?.addEventListener('change', async () => {
            await loadItems(spCategory.value);
        });

        spItem?.addEventListener('change', () => {
            updateStockInfo();
        });

        // Init
        if (bookingId && stationId) {
            loadCategories();
            loadUsage();
        }


    })
</script>

<?= $this->endSection(); ?>