<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>
<?php $this->include('components/ajax_toast') ?>

<div class="w-full px-6 py-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl text-gray-800 font-bold tracking-wide"><?= esc($title ?? 'My Attendance & HR') ?></h1>
        <div class="text-right text-sm font-semibold text-gray-500">
            Today: <span id="liveClock"><?= date('l, F j, Y | h:i A') ?></span>
        </div>
    </div>
    <div class="mb-6 h-1 bg-red-600"></div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 w-full">

        <div class="xl:col-span-2 space-y-6 w-full">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center flex flex-col items-center justify-center min-h-[250px] w-full">
                <?php if (!$todayRecord): ?>
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Ready to start your shift?</h2>
                    <p class="text-gray-500 mb-6">Make sure to check in so your hours are tracked.</p>
                    <button onclick="processCheckIn()" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all transform hover:scale-105">
                        Tap to Check In
                    </button>

                <?php elseif ($todayRecord && empty($todayRecord['check_out'])): ?>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">You are clocked in</h2>
                    <p class="text-gray-500 mb-6">Checked in at: <span class="font-bold text-gray-800"><?= date('h:i A', strtotime($todayRecord['check_in'])) ?></span></p>
                    <button onclick="confirmCheckOut()" class="px-8 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 shadow-md transition-all transform hover:scale-105">
                        Tap to Check Out
                    </button>

                <?php else: ?>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Shift Completed!</h2>
                    <p class="text-gray-500">You worked <span class="font-bold text-gray-800"><?= esc($todayRecord['worked_hours']) ?> hours</span> today.</p>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
                <div class="border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-md font-bold text-gray-900">Recent Attendance</h3>
                    </div>
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <button onclick="openFilterModal()" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-lg">
                            View Attendance History
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto max-h-[300px] overflow-y-auto w-full">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">In</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Out</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Hrs</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php if (empty($history)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No records found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-gray-900"><?= date('M d', strtotime($row['work_date'])) ?></td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-green-600 font-medium"><?= date('h:i A', strtotime($row['check_in'])) ?></td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-red-600 font-medium">
                                            <?= $row['check_out'] ? date('h:i A', strtotime($row['check_out'])) : '...' ?>
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-bold"><?= esc($row['worked_hours']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6 w-full">

            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden w-full">
                <div class="absolute top-0 right-0 opacity-10 p-4">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-300 uppercase tracking-wider mb-1">This Month's Earnings</h3>
                <h2 class="text-3xl font-extrabold mb-4">Rs. <?= number_format($estimatedSalary ?? 0, 2) ?></h2>

                <div class="flex justify-between border-t border-gray-700 pt-4 mt-2">
                    <div>
                        <p class="text-xs text-gray-400">Total Hours</p>
                        <p class="font-bold"><?= esc($totalHoursThisMonth ?? 0) ?> hrs</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Hourly Rate</p>
                        <p class="font-bold">Rs. <?= esc($hourlyRate ?? 0) ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Apply for Leave</h3>

                <form action="<?= site_url('employee/attendance/applyLeave') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Date</label>
                        <input type="date" name="leave_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Leave Type</label>
                        <select name="leave_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="sick">Sick Leave</option>
                            <option value="casual">Casual Leave</option>
                            <option value="annual">Annual Leave</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Reason</label>
                        <textarea name="reason" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Briefly explain..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 bg-gray-900 text-white font-bold rounded-lg hover:bg-gray-800 transition-colors">
                        Submit Leave Request
                    </button>
                </form>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6 w-full">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Leave Status</h3>

                    <div class="space-y-4">
                        <?php if (empty($leaveHistory)): ?>
                            <p class="text-sm text-gray-500 italic">No recent leave requests.</p>
                        <?php else: ?>
                            <?php foreach ($leaveHistory as $leave): ?>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">
                                            <?= date('M d', strtotime($leave['leave_date'])) ?>
                                            <span class="text-xs font-normal text-gray-500 ml-1">(<?= ucfirst($leave['leave_type']) ?>)</span>
                                        </p>
                                        <p class="text-xs text-gray-500 truncate w-32" title="<?= esc($leave['reason']) ?>">
                                            <?= esc($leave['reason']) ?>
                                        </p>
                                    </div>

                                    <div>
                                        <?php if ($leave['status'] == 'pending'): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase rounded-md">Pending</span>
                                        <?php elseif ($leave['status'] == 'approved'): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-md">Approved</span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded-md">Rejected</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>




            </div>

        </div>
    </div>
</div>

<div id="filterModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden flex flex-col">

        <div class="px-6 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Work & Leave History</h2>
                <p class="text-xs text-gray-500">Filter and view your logs by selecting a date range</p>
            </div>
            <button onclick="closeFilterModal()" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 text-gray-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="h-1 bg-red-600 w-full mb-4"></div>

        <div class="px-6 py-2 flex flex-wrap gap-4 items-center justify-between border-b pb-4">
            <div class="flex gap-2 p-1 bg-gray-100 rounded-xl">
                <button onclick="switchTab('attendance')" id="tab-attendance" class="px-4 py-2 rounded-lg text-sm font-bold bg-white text-blue-700 shadow-sm transition-all">Attendance Details</button>
                <button onclick="switchTab('leaves')" id="tab-leaves" class="px-4 py-2 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-200 transition-all">Leave Details</button>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    <input type="text" id="dateRangePicker" class="pl-10 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none w-72 shadow-sm" placeholder="Select Date Range (e.g. Mon to Fri)">
                </div>
            </div>
        </div>

        <div class="p-6 overflow-y-auto min-h-[450px] bg-gray-50" id="modalBody">
            <div id="emptyState" class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="italic">Please select a date range to load your data...</p>
            </div>
        </div>

        <div class="px-6 py-4 border-t flex justify-end bg-white">
            <button onclick="closeFilterModal()" class="px-8 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md transition-all">Close</button>
        </div>
    </div>
</div>

<div id="checkOutModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden items-center justify-center z-[100] p-4 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-95 duration-300" id="modalCard">

        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2">Ending Shift?</h3>
            <p class="text-sm text-gray-500">Are you sure you want to clock out now? Your working hours for today will be finalized.</p>
        </div>

        <div class="grid grid-cols-2 border-t border-gray-100">
            <button onclick="closeCheckOutModal()" class="py-4 text-sm font-bold text-gray-400 hover:bg-gray-50 border-r border-gray-100 transition-colors uppercase tracking-widest">
                Cancel
            </button>
            <button onclick="processCheckOut()" class="py-4 text-sm font-black text-red-600 hover:bg-red-50 transition-colors text-center uppercase tracking-widest w-full">
                Yes, Check Out
            </button>
        </div>
    </div>
</div>

<script>
    // 1. Live Clock Update
    setInterval(() => {
        const now = new Date();
        const options = {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        };
        const clockElement = document.getElementById('liveClock');
        if (clockElement) {
            clockElement.innerText = now.toLocaleString('en-US', options);
        }
    }, 1000);

    // 2. Global Variables for History Modal
    let currentTab = 'attendance';
    let historyData = {
        attendance: [],
        leaves: []
    };

    // 3. Initialize Flatpickr (Date Range Picker)
    flatpickr("#dateRangePicker", {
        mode: "range",
        dateFormat: "Y-m-d",
        maxDate: "today",
        onClose: function(selectedDates) {
            if (selectedDates.length === 2) {
                fetchFilteredData(selectedDates[0], selectedDates[1]);
            }
        }
    });

    // 4. Fetch History Data (AJAX GET Request)
    function fetchFilteredData(startObj, endObj) {
        const offset = startObj.getTimezoneOffset() * 60000;
        const start = new Date(startObj.getTime() - offset).toISOString().split('T')[0];
        const end = new Date(endObj.getTime() - offset).toISOString().split('T')[0];

        const body = document.getElementById('modalBody');
        body.innerHTML = '<div class="text-center py-20 text-blue-600 font-bold animate-pulse">Fetching records...</div>';

        fetch(`<?= site_url('employee/attendance/getFilteredHistory') ?>?start=${start}&end=${end}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                historyData = data;
                renderModalContent();
            })
            .catch(err => {
                console.error("Fetch Error: ", err);
                body.innerHTML = '<p class="text-center text-red-500 py-20 font-bold">Error loading data.</p>';
            });
    }

    // 5. Render History Cards in Modal
    function renderModalContent() {
        const container = document.getElementById('modalBody');
        let html = '';

        if (currentTab === 'attendance') {
            if (historyData.attendance.length === 0) {
                html = '<div class="text-center py-20 text-gray-500">No work records found.</div>';
            } else {
                historyData.attendance.forEach(row => {
                    html += `
                <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4 flex justify-between items-center shadow-sm">
                    <div class="grid grid-cols-2 gap-12 flex-1">
                        <div>
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase mb-1">Date</p>
                            <p class="font-bold text-gray-800">${row.work_date}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase mb-1">Logs (In/Out)</p>
                            <p class="text-sm font-medium">
                                <span class="text-green-600">${row.check_in.split(' ')[1]}</span>
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="text-red-600">${row.check_out ? row.check_out.split(' ')[1] : '---'}</span>
                            </p>
                        </div>
                    </div>
                    <div class="ml-6 flex items-center">
                        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-sm font-black border border-blue-100">
                            ${row.worked_hours} <span class="text-[10px] uppercase font-bold ml-1">Hrs</span>
                        </div>
                    </div>
                </div>`;
                });
            }
        } else {
            if (historyData.leaves.length === 0) {
                html = '<div class="text-center py-20 text-gray-500">No leave requests found.</div>';
            } else {
                historyData.leaves.forEach(row => {
                    const statusColor = row.status === 'approved' ? 'bg-green-100 text-green-700' : (row.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                    html += `
                <div class="bg-white border border-gray-100 rounded-2xl p-5 mb-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] text-gray-400 font-extrabold uppercase mb-1">Leave Date & Type</p>
                            <p class="font-bold text-gray-800">${row.leave_date} <span class="text-blue-500 text-xs ml-2 uppercase font-bold">(${row.leave_type})</span></p>
                            <p class="text-sm text-gray-500 mt-2 italic">"${row.reason}"</p>
                        </div>
                        <div class="text-right">
                            <span class="${statusColor} px-3 py-1 rounded-lg text-[10px] font-black uppercase">${row.status}</span>
                        </div>
                    </div>
                </div>`;
                });
            }
        }
        container.innerHTML = html;
    }

    // 6. Tab Switching Logic
    function switchTab(tab) {
        currentTab = tab;
        const attTab = document.getElementById('tab-attendance');
        const leaTab = document.getElementById('tab-leaves');

        if (tab === 'attendance') {
            attTab.className = "px-4 py-2 rounded-lg text-sm font-bold bg-white text-blue-700 shadow-sm transition-all";
            leaTab.className = "px-4 py-2 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-200 transition-all";
        } else {
            leaTab.className = "px-4 py-2 rounded-lg text-sm font-bold bg-white text-blue-700 shadow-sm transition-all";
            attTab.className = "px-4 py-2 rounded-lg text-sm font-bold text-gray-600 hover:bg-gray-200 transition-all";
        }
        renderModalContent();
    }

    // 7. CSRF Helper Data
    function getCsrfData() {
        return {
            tokenName: '<?= csrf_token() ?>',
            tokenHash: '<?= csrf_hash() ?>'
        };
    }

    // 8. Process Check-In (AJAX POST Request)
    function processCheckIn() {
        const csrf = getCsrfData();
        const formData = new FormData();
        formData.append(csrf.tokenName, csrf.tokenHash);

        // Prevent double clicking
        const btn = document.querySelector('button[onclick="processCheckIn()"]');
        if (btn) btn.disabled = true;

        fetch('<?= site_url('employee/attendance/checkIn') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // CORRECT AJAX TOAST CALL: window.showToast(Message, Type);
                window.showToast(data.message, data.status || (data.success ? 'success' : 'error'));

                if (data.success || data.status === 'success' || data.status === 'info') {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    if (btn) btn.disabled = false;
                }
            })
            .catch(err => {
                console.error("AJAX Error:", err);
                window.showToast('Failed to connect to server', 'error');
                if (btn) btn.disabled = false;
            });
    }

    // 9. Process Check-Out (AJAX POST Request)
    function processCheckOut() {
        const csrf = getCsrfData();
        const formData = new FormData();
        formData.append(csrf.tokenName, csrf.tokenHash);

        const btn = document.querySelector('button[onclick="processCheckOut()"]');
        if (btn) btn.disabled = true;

        fetch('<?= site_url('employee/attendance/checkOut') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                closeCheckOutModal();

                // CORRECT AJAX TOAST CALL: window.showToast(Message, Type);
                window.showToast(data.message, data.status || (data.success ? 'success' : 'error'));

                if (data.success || data.status === 'success') {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    if (btn) btn.disabled = false;
                }
            })
            .catch(err => {
                closeCheckOutModal();
                console.error("AJAX Error:", err);
                window.showToast('Failed to connect to server', 'error');
                if (btn) btn.disabled = false;
            });
    }

    // 10. Modal Control Functions
    function openFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
        document.getElementById('filterModal').classList.add('flex');
    }

    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
        document.getElementById('filterModal').classList.remove('flex');
    }

    function confirmCheckOut() {
        const modal = document.getElementById('checkOutModal');
        const card = document.getElementById('modalCard');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    function closeCheckOutModal() {
        const modal = document.getElementById('checkOutModal');
        const card = document.getElementById('modalCard');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // 11. Close Modals on Backdrop Click
    window.onclick = function(event) {
        const checkOutModal = document.getElementById('checkOutModal');
        const filterModal = document.getElementById('filterModal');
        if (event.target == checkOutModal) closeCheckOutModal();
        if (event.target == filterModal) closeFilterModal();
    }
</script>
<?= $this->endSection(); ?>