<?= $this->extend($main_layout); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/ajax_toast') ?>


<!-- Bookings -->
<div class="container mx-auto p-6">
    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title) ?></h1>
    <div class="mb-4 h-1 bg-red-600"></div>
    <!-- Table -->
    <div class="overflow-hidden border border-white/20 rounded-2xl bg-white/70 backdrop-blur-md shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">ID</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">Vehicle Name</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">Service</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">Booking Date</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">Station</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider">Assigned At</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider text-center">Status</th>
                    <th class="p-4 text-[11px] font-black uppercase text-gray-500 tracking-wider text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($assignbookings)) : ?>
                    <?php foreach ($assignbookings as $booking) : ?>
                        <tr class="hover:bg-white/50 transition-colors duration-200">
                            <td class="p-4 text-sm font-bold text-gray-700">#<?= esc($booking['id']) ?></td>
                            <td class="p-4 text-sm font-semibold text-gray-800"><?= esc($booking['vehicle_model']) ?></td>
                            <td class="p-4 text-sm text-gray-600 font-medium"><?= esc($booking['service']) ?></td>
                            <td class="p-4 text-sm text-gray-600"><?= esc($booking['booking_date']) ?></td>
                            <td class="p-4 text-sm font-medium text-blue-600"><?= esc($booking['station_name'] ?? 'N/A') ?></td>
                            <td class="p-4 text-xs text-gray-500 font-mono"><?= esc($booking['assigned_at']) ?></td>

                            <td class="p-4 text-center">
                                <?php
                                $status = strtolower($booking['status'] ?? '');
                                $statusClass = match ($status) {
                                    'assigned'    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'handed_over' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'completed'   => 'bg-green-50 text-green-700 border-green-200',
                                    'cancelled'   => 'bg-red-50 text-red-700 border-red-200',
                                    default       => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                                ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border whitespace-nowrap shadow-sm <?= $statusClass ?>">
                                    <?= str_replace('_', ' ', $status) ?>
                                </span>
                            </td>

                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                    <?php if ($booking['status'] === 'assigned'): ?>
                                        <button type="button"
                                            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-[11px] font-bold text-white rounded-lg shadow-sm transition-all transform active:scale-95"
                                            onclick="openApproveModal(<?= (int)$booking['id'] ?>)">
                                            Approve
                                        </button>
                                    <?php endif; ?>

                                    <button type="button"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-[11px] font-bold text-white rounded-lg shadow-sm transition-all transform active:scale-95"
                                        onclick="openViewModal(<?= (int)$booking['id'] ?>)">
                                        View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-400 italic font-medium">
                            No bookings assigned yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->section('modals'); ?>
<div id="approveModal"
    class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <!-- Approve Modal -->
    <div class="bg-white rounded-xl p-8 text-center max-w-sm w-full">

        <h2 class="text-xl font-bold mb-4 text-gray-800">
            Approve Booking
        </h2>

        <p class="mb-6 text-gray-600">
            Are you sure you want to approve this booking?
        </p>

        <form id="approveForm"
            action="<?= site_url('employee/approve') ?>"
            method="POST">

            <input type="hidden" name="booking_assign_id" id="approveAssignId">
            <input type="hidden" id="approveRowBookingId">
            <input type="hidden"
                name="<?= csrf_token() ?>"
                value="<?= csrf_hash() ?>">

            <div class="flex justify-center space-x-4">

                <button id="approveSubmitBtn"
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Yes, Approve
                </button>

                <button type="button"
                    onclick="closeApproveModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
                    Cancel
                </button>

            </div>
        </form>
    </div>
</div>

<!-- Booking + Service Details Modal -->

<div id="viewModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto bg-black/60 backdrop-blur-sm p-4 flex justify-center items-start">
    <div class="bg-white shadow rounded-2xl border border-gray-200 w-full max-w-6xl my-8">

        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-wide">Booking Details</h2>
                    <p class="text-sm text-gray-500">View booking and service workflow details</p>
                </div>

                <button type="button"
                    onclick="closeViewModal()"
                    class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-red-700 text-gray-700 font-bold">
                    ✕
                </button>
            </div>

            <div class="mt-4 h-1 bg-red-600"></div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button id="tabBtnBooking"
                    type="button"
                    onclick="switchViewTab('booking')"
                    class="px-4 py-2 rounded-xl text-sm font-bold border bg-blue-100 text-blue-700 border-blue-200">
                    Booking Details
                </button>

                <button id="tabBtnService"
                    type="button"
                    onclick="switchViewTab('service')"
                    class="px-4 py-2 rounded-xl text-sm font-bold border bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200">
                    Service Details
                </button>
            </div>
        </div>

        <div class="p-6">
            <div id="tabBookingPanel" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Customer & Booking</h3>
                            <p class="text-sm text-gray-500">General booking information</p>
                        </div>

                        <span id="bookingStatusBadge"
                            class="px-3 py-1 rounded-full text-sm font-semibold border bg-gray-100 text-gray-700 border-gray-200">
                            Loading...
                        </span>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <tbody id="bookingDetailsRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Booking Notes</h3>
                            <span class="text-sm text-gray-500">Admin notes and remarks</span>
                        </div>
                    </div>

                    <div class="border rounded-xl p-4 bg-gray-50 min-h-[150px]">
                        <p id="bookingAdminNote" class="text-gray-700 whitespace-pre-line">
                            Loading...
                        </p>
                    </div>
                </div>
            </div>

            <div id="tabServicePanel" class="hidden space-y-6">

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Service Summary</h3>
                            <span class="text-sm text-gray-500">Process status and timings</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <tbody id="serviceSummaryRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Assigned Details</h3>
                            <span class="text-sm text-gray-500">Station / Bay Assignment History</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="p-3">Station</th>
                                    <th class="p-3">Bay</th>
                                    <th class="p-3">Employee</th>
                                    <th class="p-3">Assigned At</th>
                                    <th class="p-3">Started At</th>
                                    <th class="p-3">Finished At</th>
                                    <th class="p-3">Note</th>
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody id="assignmentHistoryRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="8" class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Job Step Details</h3>
                            <span class="text-sm text-gray-500">All station steps with completion status</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="p-3">Station</th>
                                    <th class="p-3">Bay</th>
                                    <th class="p-3">Step No</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Employee</th>
                                    <th class="p-3">End Time</th>
                                </tr>
                            </thead>
                            <tbody id="jobStepRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="6" class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Spare Part Usage</h3>
                            <span class="text-sm text-gray-500">Used spare parts by station</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="p-3">Part</th>
                                    <th class="p-3">Station</th>
                                    <th class="p-3">Bay</th>
                                    <th class="p-3">Qty</th>
                                    <th class="p-3">Used At</th>
                                </tr>
                            </thead>
                            <tbody id="spareUsageRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end mr-4">
            <button type="button"
                onclick="closeViewModal()"
                class="px-4 py-2 bg-red-500 rounded hover:bg-red-600 text-white font-bold">
                Close
            </button>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<script>
    // --- 1. Global Variables ---
    // URL එක PHP වලින් echo කරනවා
    var VIEW_DATA_URL = "<?php echo site_url('employee/bookings/view-data'); ?>";

    // --- 2. Modal Controls (Global Scope එකේ තියෙන්න ඕනේ onclick වැඩ කරන්න) ---

    window.openApproveModal = function(assignId) {
        var assignInput = document.getElementById('approveAssignId');
        var bookingInput = document.getElementById('approveRowBookingId');

        if (assignInput) assignInput.value = assignId;
        if (bookingInput) bookingInput.value = assignId;

        var modal = document.getElementById("approveModal");
        if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
    };

    window.closeApproveModal = function() {
        var modal = document.getElementById("approveModal");
        if (modal) {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    };

    window.openViewModal = function(bookingId) {
        var modal = document.getElementById("viewModal");
        if (modal) {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        window.switchViewTab('booking');
        resetModalLoadingStates();
        loadBookingViewData(bookingId);
    };

    window.closeViewModal = function() {
        var modal = document.getElementById("viewModal");
        if (modal) {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    };

    window.switchViewTab = function(tab) {
        var bookingPanel = document.getElementById("tabBookingPanel");
        var servicePanel = document.getElementById("tabServicePanel");
        var btnBooking = document.getElementById("tabBtnBooking");
        var btnService = document.getElementById("tabBtnService");

        var activeClass = "px-4 py-2 rounded-xl text-sm font-bold border bg-blue-100 text-blue-700 border-blue-200";
        var inactiveClass = "px-4 py-2 rounded-xl text-sm font-bold border bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200";

        if (tab === "booking") {
            if (bookingPanel) bookingPanel.classList.remove("hidden");
            if (servicePanel) servicePanel.classList.add("hidden");
            if (btnBooking) btnBooking.className = activeClass;
            if (btnService) btnService.className = inactiveClass;
        } else {
            if (bookingPanel) bookingPanel.classList.add("hidden");
            if (servicePanel) servicePanel.classList.remove("hidden");
            if (btnService) btnService.className = activeClass;
            if (btnBooking) btnBooking.className = inactiveClass;
        }
    };

    function resetModalLoadingStates() {
        var loaders = ["bookingDetailsRows", "serviceSummaryRows", "assignmentHistoryRows", "jobStepRows", "spareUsageRows"];
        for (var i = 0; i < loaders.length; i++) {
            var el = document.getElementById(loaders[i]);
            if (el) el.innerHTML = '<tr><td colspan="10" class="p-4 text-gray-500">Loading...</td></tr>';
        }
        var statusBadge = document.getElementById("bookingStatusBadge");
        var adminNote = document.getElementById("bookingAdminNote");
        if (statusBadge) statusBadge.textContent = "Loading...";
        if (adminNote) adminNote.textContent = "Loading...";
    }

    // --- 3. AJAX Data Operations ---

    function loadBookingViewData(bookingId) {
        fetch(VIEW_DATA_URL + "/" + bookingId, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                if (!data.success) throw new Error(data.message);
                renderBookingTab(data.booking);
                renderServiceTab(data.serviceSummary, data.assignmentHistory, data.jobSteps, data.spareUsage);
            })
            .catch(function(e) {
                console.error(e);
                closeViewModal();
            });
    }

    // --- 4. Rendering ---

    function renderBookingTab(b) {
        setStatusBadge("bookingStatusBadge", b ? b.status : "");
        var rows = [
            ["Booking ID", b ? b.id : ""],
            ["Customer", b ? b.name : ""],
            ["Phone", b ? b.phone : ""],
            ["Service Type", b ? b.service : ""],
            ["Vehicle", b ? b.vehicle_model : ""],
            ["Booking Date", b ? b.booking_date : ""],
            ["Created At", b ? b.created_at : ""]

        ];

        var html = "";
        for (var i = 0; i < rows.length; i++) {
            html += '<tr><td class="p-4 text-gray-500 font-semibold w-48">' + esc(rows[i][0]) + '</td>' +
                '<td class="p-4 text-gray-800">' + esc(val(rows[i][1])) + '</td></tr>';
        }
        document.getElementById("bookingDetailsRows").innerHTML = html;
        document.getElementById("bookingAdminNote").textContent = val(b ? (b.admin_note || b.reject_reason) : "No notes available.");
    }

    function renderServiceTab(summary, assignments, steps, spares) {
        // 1. Service Summary
        var summaryHtml = "";
        var sumRows = [
            ["Status", summary ? summary.status : "-"],
            ["Current Station", summary ? summary.current_station : "-"],
            ["Bay No", summary ? summary.bay_no : "-"],
            ["Employee", summary ? summary.current_employee : "-"],
            ["Started At", summary ? summary.started_at : "-"]
        ];
        for (var j = 0; j < sumRows.length; j++) {
            summaryHtml += '<tr><td class="p-4 text-gray-500 font-semibold w-48">' + esc(sumRows[j][0]) + '</td>' +
                '<td class="p-4 text-gray-800">' + esc(val(sumRows[j][1])) + '</td></tr>';
        }
        document.getElementById("serviceSummaryRows").innerHTML = summaryHtml;

        // 2. Assignment History
        var assignHtml = "";
        if (assignments && assignments.length > 0) {
            for (var k = 0; k < assignments.length; k++) {
                var a = assignments[k];
                assignHtml += '<tr>' +
                    '<td class="p-3">' + esc(a.station_name) + '</td>' +
                    '<td class="p-3">' + esc(a.bay_no) + '</td>' + // bay_no
                    '<td class="p-3">' + esc(a.employee_name) + '</td>' +
                    '<td class="p-3">' + esc(a.assigned_at) + '</td>' +
                    '<td class="p-3">' + esc(a.started_at) + '</td>' +
                    '<td class="p-3">' + esc(a.completed_at) + '</td>' + // completed_at
                    '<td class="p-3">' + esc(a.notes) + '</td>' + // notes
                    '<td class="p-3">' + statusPill(a.status) + '</td>' +
                    '</tr>';
            }
        } else {
            assignHtml = '<tr><td colspan="8" class="p-4 text-center text-gray-400">No history found.</td></tr>';
        }
        document.getElementById("assignmentHistoryRows").innerHTML = assignHtml;

        // 3. Job Steps
        var stepsHtml = "";
        if (steps && steps.length > 0) {
            for (var l = 0; l < steps.length; l++) {
                var s = steps[l];
                stepsHtml += '<tr>' +
                    '<td class="p-3">' + esc(s.station_name) + '</td>' +
                    '<td class="p-3">' + esc(s.bay_no) + '</td>' + // bay_no
                    '<td class="p-3">' + esc(s.sequence_no) + '</td>' + // sequence_no
                    '<td class="p-3">' + statusPill(s.status) + '</td>' +
                    '<td class="p-3">' + esc(s.employee_name) + '</td>' +
                    '<td class="p-3">' + esc(s.end_time) + '</td>' +
                    '</tr>';
            }
        } else {
            stepsHtml = '<tr><td colspan="6" class="p-4 text-center text-gray-400">No steps found.</td></tr>';
        }
        document.getElementById("jobStepRows").innerHTML = stepsHtml;

        // 4. Spare Part Usage
        var sparesHtml = "";
        if (spares && spares.length > 0) {
            for (var m = 0; m < spares.length; m++) {
                var p = spares[m];
                sparesHtml += '<tr>' +
                    '<td class="p-3">' + esc(p.part_name) + '</td>' +
                    '<td class="p-3">' + esc(p.station_name) + '</td>' +
                    '<td class="p-3">' + esc(p.bay_no) + '</td>' + // bay_no
                    '<td class="p-3">' + esc(p.qty) + '</td>' +
                    '<td class="p-3">' + esc(p.created_at) + '</td>' +
                    '</tr>';
            }
        } else {
            sparesHtml = '<tr><td colspan="5" class="p-4 text-center text-gray-400">No spares found.</td></tr>';
        }
        document.getElementById("spareUsageRows").innerHTML = sparesHtml;
    }

    // --- 5. Utilities ---

    function setStatusBadge(id, status) {
        var el = document.getElementById(id);
        if (!el) return;
        var s = String(status || "").toLowerCase();
        var cls = "px-3 py-1 rounded-full text-sm font-semibold border ";

        if (["approved", "completed", "done"].indexOf(s) > -1) cls += "bg-green-100 text-green-700 border-green-200";
        else if (s === "in_progress") cls += "bg-blue-100 text-blue-700 border-blue-200";
        else if (["rejected", "cancelled", "handed_over"].indexOf(s) > -1) cls += "bg-red-100 text-red-700 border-red-200";
        else cls += "bg-gray-100 text-gray-700 border-gray-200";

        el.className = cls;
        el.textContent = status ? status.toUpperCase() : "UNKNOWN";
    }

    function statusPill(status) {
        var s = String(status || "pending").toLowerCase();
        var cls = "px-2 py-1 rounded-full text-xs font-bold border ";
        if (["done", "completed"].indexOf(s) > -1) cls += "bg-green-100 text-green-700 border-green-200";
        else if (s === "handed_over" || s === "in_progress") cls += "bg-blue-100 text-blue-700 border-blue-200";
        else cls += "bg-gray-100 text-gray-700 border-gray-200";

        return '<span class="' + cls + '">' + s.toUpperCase() + '</span>';
    }

    function esc(str) {
        if (!str) return "";
        return String(str).replace(/[&<>"']/g, function(m) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            } [m];
        });
    }

    function val(v) {
        return (v === null || v === undefined || v === "") ? "-" : v;
    }

    // Form Submit logic

    document.addEventListener('submit', function(e) {

        if (e.target && e.target.id === 'approveForm') {
            e.preventDefault(); // 

            const approveForm = e.target;
            const btn = document.getElementById('approveSubmitBtn');

            btn.disabled = true;
            btn.innerText = "Processing...";

            // CSRF Token එක සහ Hash එක dynamic විදිහට ගන්නවා
            const csrfHash = '<?= csrf_hash() ?>';

            fetch(approveForm.action, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": csrfHash // Header එකට Token එක දානවා
                    },
                    body: new FormData(approveForm)
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || "Failed");
                        btn.disabled = false;
                        btn.innerText = "Yes, Approve";
                    }
                })
                .catch(function(err) {
                    console.error("Error:", err);
                    btn.disabled = false;
                    btn.innerText = "Yes, Approve";
                });
        }
    });
</script>

<?= $this->endSection(); ?>