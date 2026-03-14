<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/ajax_toast') ?>


<!-- Bookings -->
<div class="container mx-auto p-6">
    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title) ?></h1>
    <div class="mb-4 h-1 bg-red-600"></div>
    <!-- Table -->
    <div class="overflow-x-auto border rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-gray-100 border-b border-gray-200 uppercase text-sm justify-center">
                <tr>
                    <th class="p-4 text-gray-800 font-medium">ID</th>
                    <th class="p-4 text-gray-800 font-medium">Vehicle Name</th>
                    <th class="p-4 text-gray-800 font-medium">Service</th>
                    <th class="p-4 text-gray-800 font-medium">Booking Date</th>
                    <th class="p-4 text-gray-800 font-medium">Station</th>
                    <th class="p-4 text-gray-800 font-medium">Assigned At</th>
                    <th class="p-4 text-gray-800 font-medium">Status</th>
                    <th class="p-4 text-gray-800 font-medium text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($assignbookings)) : ?>
                    <?php foreach ($assignbookings as $booking) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['id']) ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['vehicle_model']) ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['service']) ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['booking_date']) ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['station_name'] ?? 'N/A') ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($booking['assigned_at']) ?></td>
                            <td class="p-4 font-semibold text-gray-800">
                                <?php
                                $status = strtolower($booking['status'] ?? '');
                                $statusClass = match ($status) {
                                    'assigned' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'completed' => 'bg-green-100 text-green-700 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                    default => 'bg-gray-300 text-gray-700 blorder-gray-200',
                                };
                                ?>
                                <span id="status-badge-<?= $booking['id'] ?>"
                                    class="px-3 py-1 rounded-full text-sm font-semibold border <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                </span>

                            </td>


                            <td class="p-4">
                                <div id="action-buttons-<?= $booking['id'] ?>" class="flex justify-center gap-2">
                                    <?php if ($booking['status'] !== 'in_progress' && $booking['status'] !== 'completed' && $booking['status'] !== 'handed_over'): ?>
                                        <button type="button"
                                            class="px-4 py-2 bg-green-600 text-sm text-white rounded hover:bg-green-700"
                                            onclick="openApproveModal(<?= (int)$booking['id'] ?>, <?= (int)$booking['booking_id'] ?>)">
                                            Approve
                                        </button>
                                    <?php endif; ?>

                                    <button type="button"
                                        class="px-4 py-2 bg-blue-600 text-sm text-white rounded hover:bg-blue-700"
                                        onclick="openViewModal(<?= (int)$booking['booking_id'] ?>)">
                                        View
                                    </button>
                                </div>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">No bookings assigned yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<div id="approveModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

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
<div id="viewModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white shadow rounded-2xl border border-gray-200 w-full max-w-6xl max-h-[92vh] overflow-hidden">

        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-wide">Booking Details</h2>
                    <p class="text-sm text-gray-500">View booking and service workflow details</p>
                </div>

                <button type="button"
                    onclick="closeViewModal()"
                    class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">
                    ✕
                </button>
            </div>

            <div class="mt-4 h-1 bg-red-600"></div>

            <!-- Tab Buttons -->
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

        <!-- Body -->
        <div class="p-6 overflow-y-auto max-h-[50vh]">

            <!-- Tab Panels -->
            <div id="tabBookingPanel" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Booking Basic -->
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

                <!-- Notes / Extra -->
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

            <!-- Service Panel -->
            <div id="tabServicePanel" class="hidden space-y-6">

                <!-- Service Summary -->
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

                <!-- Station Assignment History -->
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
                                    <th class="p-3">Status</th>
                                </tr>
                            </thead>
                            <tbody id="assignmentHistoryRows" class="divide-y divide-gray-200">
                                <tr>
                                    <td colspan="7" class="p-4 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Job Step Details -->
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

                <!-- Spare Part Usage -->
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

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200 flex justify-end mr-4">
            <button type="button"
                onclick="closeViewModal()"
                class="px-4 py-2 bg-red-500 rounded hover:bg-red-600 text-white font-bold">
                Close
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- 1. Variable Initializations ---
        const approveForm = document.getElementById('approveForm');
        const VIEW_DATA_URL = "<?= site_url('employee/bookings/view-data') ?>";

        // --- 2. Modal Controls ---

        window.openApproveModal = function(assignId, bookingId) {
            document.getElementById('approveAssignId').value = assignId;
            document.getElementById('approveRowBookingId').value = bookingId;

            const modal = document.getElementById("approveModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        };

        window.closeApproveModal = function() {
            const modal = document.getElementById("approveModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        };

        window.openViewModal = function(bookingId) {
            const modal = document.getElementById("viewModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            window.switchViewTab('booking');
            resetModalLoadingStates();
            loadBookingViewData(bookingId);
        };

        window.closeViewModal = function() {
            const modal = document.getElementById("viewModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        };

        // --- 3. UI Helpers ---

        window.switchViewTab = function(tab) {
            const bookingPanel = document.getElementById("tabBookingPanel");
            const servicePanel = document.getElementById("tabServicePanel");
            const btnBooking = document.getElementById("tabBtnBooking");
            const btnService = document.getElementById("tabBtnService");

            const activeClass = "px-4 py-2 rounded-xl text-sm font-bold border bg-blue-100 text-blue-700 border-blue-200";
            const inactiveClass = "px-4 py-2 rounded-xl text-sm font-bold border bg-gray-100 text-gray-700 border-gray-200 hover:bg-gray-200";

            if (tab === "booking") {
                bookingPanel.classList.remove("hidden");
                servicePanel.classList.add("hidden");
                btnBooking.className = activeClass;
                btnService.className = inactiveClass;
            } else {
                bookingPanel.classList.add("hidden");
                servicePanel.classList.remove("hidden");
                btnService.className = activeClass;
                btnBooking.className = inactiveClass;
            }
        };

        function resetModalLoadingStates() {
            const loaders = ["bookingDetailsRows", "serviceSummaryRows", "assignmentHistoryRows", "jobStepRows", "spareUsageRows"];
            loaders.forEach(id => {
                document.getElementById(id).innerHTML = `<tr><td colspan="10" class="p-4 text-gray-500">Loading...</td></tr>`;
            });
            document.getElementById("bookingStatusBadge").textContent = "Loading...";
            document.getElementById("bookingAdminNote").textContent = "Loading...";
        }

        // --- 4. AJAX Data Operations ---

        // Handle Approval Submission
        if (approveForm) {
            approveForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const btn = document.getElementById('approveSubmitBtn');
                btn.disabled = true;
                btn.innerText = "Processing...";

                try {
                    const res = await fetch(approveForm.action, {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        },
                        body: new FormData(approveForm)
                    });

                    const data = await res.json();

                    if (data.success) {
                        showToast(data.message, "success");
                        // Delay redirect by 1.5s to let the user see the toast
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1500);
                    } else {
                        showToast(data.message || "Failed to approve", "error");
                        btn.disabled = false;
                        btn.innerText = "Yes, Approve";
                    }
                } catch (err) {
                    showToast("Network error. Please try again.", "error");
                    btn.disabled = false;
                }
            });
        }

        async function loadBookingViewData(bookingId) {
            try {
                const res = await fetch(`${VIEW_DATA_URL}/${bookingId}`, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const data = await res.json();

                if (!data.success) throw new Error(data.message);

                renderBookingTab(data.booking);
                renderServiceTab(data.serviceSummary, data.assignmentHistory, data.jobSteps, data.spareUsage);

            } catch (e) {
                showToast(e.message || "Could not load data", "error");
                closeViewModal();
            }
        }

        // --- 5. Data Rendering Engines ---

        function renderBookingTab(b) {
            setStatusBadge("bookingStatusBadge", b?.status);
            const rows = [
                ["Booking ID", b?.id],
                ["Customer", b?.name],
                ["Phone", b?.phone],
                ["Service Type", b?.service],
                ["Vehicle", b?.vehicle_model],
                ["Booking Date", b?.booking_date],
                ["Created At", b?.created_at]
            ];

            document.getElementById("bookingDetailsRows").innerHTML = rows.map(([k, v]) => `
                <tr>
                    <td class="p-4 text-gray-500 font-semibold w-48">${esc(k)}</td>
                    <td class="p-4 text-gray-800">${esc(val(v))}</td>
                </tr>
            `).join("");

            document.getElementById("bookingAdminNote").textContent = val(b?.notes || b?.reject_reason || "No notes available.");
        }

        function renderServiceTab(summary, assignments, steps, spares) {
            // Summary
            const sumRows = [
                ["Status", summary?.status],
                ["Current Station", summary?.current_station],
                ["Employee", summary?.current_employee],
                ["Started At", summary?.started_at]
            ];
            document.getElementById("serviceSummaryRows").innerHTML = sumRows.map(([k, v]) => `
                <tr>
                    <td class="p-4 text-gray-500 font-semibold w-48">${esc(k)}</td>
                    <td class="p-4 text-gray-800">${esc(val(v))}</td>
                </tr>
            `).join("");

            // History
            document.getElementById("assignmentHistoryRows").innerHTML = assignments.length ? assignments.map(a => `
                <tr>
                    <td class="p-3">${esc(val(a.station_name))}</td>
                    <td class="p-3">${esc(val(a.bay_no))}</td>
                    <td class="p-3">${esc(val(a.employee_name))}</td>
                    <td class="p-3">${esc(val(a.assigned_at))}</td>
                    <td class="p-3">${esc(val(a.started_at))}</td>
                    <td class="p-3">${esc(val(a.completed_at))}</td>
                    <td class="p-3 text-center">${statusPill(a.status)}</td>
                </tr>
            `).join("") : '<tr><td colspan="7" class="p-4 text-center">No history</td></tr>';

            // Job Steps
            document.getElementById("jobStepRows").innerHTML = steps.length ? steps.map(s => `
                <tr>
                    <td class="p-3">${esc(val(s.station_name))}</td>
                    <td class="p-3">${esc(val(s.bay_no))}</td>
                    <td class="p-3">${esc(val(s.sequence_no))}</td>
                    <td class="p-3">${statusPill(s.status)}</td>
                    <td class="p-3">${esc(val(s.employee_name))}</td>
                    <td class="p-3">${esc(val(s.end_time))}</td>
                </tr>
            `).join("") : '<tr><td colspan="6" class="p-4 text-center">No steps</td></tr>';

            // Spare Usage
            document.getElementById("spareUsageRows").innerHTML = spares.length ? spares.map(p => `
                <tr>
                    <td class="p-3">${esc(val(p.part_name))}</td>
                    <td class="p-3">${esc(val(p.station_name))}</td>
                    <td class="p-3">${esc(val(p.bay_no))}</td>
                    <td class="p-3">${esc(val(p.qty))}</td>
                    <td class="p-3">${esc(val(p.created_at))}</td>
                </tr>
            `).join("") : '<tr><td colspan="5" class="p-4 text-center">No spare parts used</td></tr>';
        }

        // --- 6. Global Utility Functions ---

        function setStatusBadge(id, status) {
            const el = document.getElementById(id);
            const s = String(status || "").toLowerCase();
            let cls = "px-3 py-1 rounded-full text-sm font-semibold border ";

            if (["approved", "completed", "done"].includes(s)) cls += "bg-green-100 text-green-700 border-green-200";
            else if (s === "in_progress") cls += "bg-blue-100 text-blue-700 border-blue-200";
            else if (["rejected", "cancelled"].includes(s)) cls += "bg-red-100 text-red-700 border-red-200";
            else cls += "bg-gray-100 text-gray-700 border-gray-200";

            el.className = cls;
            el.textContent = status ? status.toUpperCase() : "UNKNOWN";
        }

        function statusPill(status) {
            const s = String(status || "pending").toLowerCase();
            let cls = "px-2 py-1 rounded-full text-xs font-bold border ";
            if (["done", "completed"].includes(s)) cls += "bg-green-100 text-green-700 border-green-200";
            else if (s === "in_progress") cls += "bg-blue-100 text-blue-700 border-blue-200";
            else cls += "bg-gray-100 text-gray-700 border-gray-200";

            return `<span class="${cls}">${s.toUpperCase()}</span>`;
        }

        function esc(str) {
            return String(str ?? "").replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[m]);
        }

        function val(v) {
            return (v === null || v === undefined || v === "") ? "-" : v;
        }
    });
</script>

<?= $this->endSection(); ?>