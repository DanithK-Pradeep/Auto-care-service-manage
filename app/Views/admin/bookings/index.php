<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<h1 class=" text-2xl mb-4 text-gray-800 font-bold tracking-wide">Bookings</h1>
<div class="mb-4 h-1 bg-red-600"></div>

<table cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr class="bg-gray-200 text-center border-b-2 ">
            <th>ID</th>
            <th>Customer Name</th>
            <th>Service</th>
            <th>Vehicle</th>
            <th>Message</th>
            <th>Service Date</th>
            <th>Status</th>
            <th>Actions</th>

        </tr>
    </thead>

    <tbody class="table-group-divider text-center">
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= esc($booking['id']) ?></td>
                    <td><?= esc($booking['name']) ?></td>
                    <td><?= esc($booking['service']) ?></td>
                    <td><?= esc($booking['vehicle_model']) ?></td>
                    <td><?= esc($booking['message']) ?></td>
                    <td><?= esc($booking['booking_date']) ?></td>
                    <td>
                        <span class="px-3 py-1 rounded-full text-white text-sm
                        <?= $booking['status'] === 'pending' ? 'bg-yellow-500' : '' ?>
                        <?= $booking['status'] === 'approved' ? 'bg-blue-500' : '' ?>
                        <?= $booking['status'] === 'completed' ? 'bg-green-600' : '' ?>
                        <?= $booking['status'] === 'rejected' ? 'bg-red-600' : '' ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>

                    </td>
                    <td class="flex justify-center items-center gap-2">

                        <!-- VIEW (Always visible) -->


                        <?php if ($booking['status'] === 'pending'): ?>

                            <!-- ACCEPT -->
                            <button
                                onclick="openApproveModal(<?= $booking['id'] ?>)"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-2 py-1 rounded">
                                Approve
                            </button>

                            <!-- REJECT -->
                            <button
                                onclick="openRejectModal(<?= $booking['id'] ?>)"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold px-2 py-1 rounded">
                                Reject
                            </button>

                        <?php endif; ?>

                        <a href="<?= site_url('admin/bookings/view/' . $booking['id']) ?>"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-2 py-1 rounded">
                            View
                        </a>

                    </td>


                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center py-4">No bookings found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Approve Modal -->
<div id="approveModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-green-600">
            Approve & Assign Booking
        </h2>

        <form method="post" action="<?= site_url('admin/bookings/approve') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="booking_id" id="approveBookingId">

            <label class="block mb-2 text-sm font-medium text-gray-700">
                Select Station
            </label>

            <select name="station_id" id="approveStationId" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">

                <option value="">-- Select Station --</option>
                <?php foreach (($stations ?? []) as $station): ?>
                    <option value="<?= esc($station['id']) ?>">
                        <?= esc($station['name']) ?>
                        <?= esc($station['bay_no'] ? " (Bay: {$station['bay_no']})" : "") ?>
                    </option>
                <?php endforeach; ?>

            </select>

            <label class="block mt-4 mb-2 text-sm font-medium text-gray-700">Select Employee</label>
            <select name="employee_id" id="approveEmployeeId" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                <option value="">-- Select Employee --</option>
            </select>

            <!-- Notes -->
            <label class="block mt-4 mb-2 text-sm font-medium text-gray-700"> Notes (optional) </label>
            <textarea name="notes" id="approveNotes" rows="3"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                </textarea>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button"
                    onclick="closeApproveModal()"
                    class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit" id="approveSubmitBtn"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                    Approve & Assign
                </button>

            </div>

        </form>

    </div>

</div>



<!-- Reject Modal -->
<div id="rejectModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-red-600">
            Reject Booking
        </h2>

        <form method="post" action="<?= site_url('admin/bookings/reject') ?>">
            <?= csrf_field() ?>

            <input type="hidden" name="booking_id" id="rejectBookingId">

            <label class="block mb-2 text-sm font-medium text-gray-700">
                Reject Reason
            </label>


            <select name="reject_reason" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">

                <option value="">-- Select Reason --</option>
                <option>No slots available on selected date</option>
                <option>Required spare parts not available</option>
                <option>Vehicle model not supported</option>
                <option>Service not offered for this vehicle</option>
                <option>Incomplete or incorrect details</option>
                <option>Payment issue / not confirmed</option>
                <option>Booking outside service area</option>
                <option>Workshop closed on selected date</option>
                <option>Emergency bookings only today</option>
                <option>Duplicate booking request</option>
                <option>Customer not reachable</option>
                <option>Other</option>
            </select>


            <div class="mt-6 flex justify-end gap-2">
                <div class="flex justify-end gap-2">
                    <button type="button"
                        onclick="closeRejectModal()"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Reject
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    //
    document.addEventListener('DOMContentLoaded', () => {

        function openRejectModal(id) {
            document.getElementById('rejectBookingId').value = id;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        window.openRejectModal = openRejectModal;

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
        window.closeRejectModal = closeRejectModal;

        function openApproveModal(bookingId) {
            document.getElementById('approveBookingId').value = bookingId;
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }
        window.openApproveModal = openApproveModal;

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }
        window.closeApproveModal = closeApproveModal;


        // ✅ Approve form submit
        const approveForm = document.getElementById('approveForm');
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
                        body: new FormData(approveForm),
                    });

                    const data = await res.json();

                    if (data.success) {
                        closeApproveModal();
                        alert("Booking approved and assigned successfully!");
                        location.reload();
                    } else {
                        alert(data.message || "Failed");
                    }
                } catch (err) {
                    console.error(err);
                    alert("Request failed");
                } finally {
                    btn.disabled = false;
                    btn.innerText = "Approve & Assign";
                }
            });
        } else {
            console.warn("approveForm not found. Add id='approveForm' to your form.");
        }


        // ✅ Station change -> load employees
        const stationSelect = document.getElementById('approveStationId');
        const empSelect = document.getElementById('approveEmployeeId');

        if (stationSelect && empSelect) {
            stationSelect.addEventListener('change', async function() {
                const stationId = this.value;

                empSelect.innerHTML = `<option value="">-- Select Employee --</option>`;
                if (!stationId) return;

                empSelect.innerHTML = `<option value="">Loading employees...</option>`;

                try {
                    const url = `<?= site_url('admin/stations') ?>/${stationId}/employees`;
                    console.log("Fetching employees:", url);

                    const res = await fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    });

                    const text = await res.text();
                    console.log("Status:", res.status);
                    console.log("Raw:", text);

                    const data = JSON.parse(text);

                    empSelect.innerHTML = `<option value="">-- Select Employee --</option>`;

                    if (!data.success) {
                        alert(data.message || 'Failed to load employees');
                        return;
                    }

                    if (!data.employees || data.employees.length === 0) {
                        empSelect.innerHTML = `<option value="">No employees assigned to this station</option>`;
                        return;
                    }

                    data.employees.forEach(emp => {
                        const opt = document.createElement('option');
                        opt.value = emp.id;
                        opt.textContent = `${emp.first_name} ${emp.last_name}`;
                        empSelect.appendChild(opt);
                    });

                } catch (err) {
                    console.error(err);
                    alert('Error loading employees');
                    empSelect.innerHTML = `<option value="">-- Select Employee --</option>`;
                }
            });
        } else {
            console.warn("approveStationId or approveEmployeeId not found. Check your select IDs.");
        }

    });
</script>




<?= $this->endSection() ?>