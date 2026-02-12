<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/ajax_toast') ?>



<div class="container mx-auto p-6">
    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title) ?></h1>
    <div class="mb-4 h-1 bg-red-600"></div>

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
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                                ?>
                                <span id="status-badge-<?= $booking['id'] ?>"
                                    class="px-3 py-1 rounded-full text-sm font-semibold border <?= $statusClass ?>">
                                    <?= ucfirst(str_replace('_', ' ', $status)) ?>
                                </span>

                            </td>


                            <td class="p-4">
                                <div id="action-buttons-<?= $booking['id'] ?>" class="flex justify-center gap-2">

                                    <?php if ($booking['status'] !== 'in_progress'): ?>

                                        <!-- Show Approve button only if NOT in progress -->
                                        <button type="button"
                                            class="px-4 py-2 bg-green-600 text-sm text-white rounded hover:bg-green-700"
                                            onclick="openApproveModal(<?= esc($booking['id']) ?>)">
                                            Approve
                                        </button>

                                    <?php endif; ?>


                                    <button type="button"
                                        class="px-4 py-2 bg-blue-600 text-sm text-white rounded hover:bg-blue-700"
                                        onclick="openViewModal(<?= $booking['id'] ?>)">
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

            <input type="hidden" name="booking_id" id="approveBookingId">

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

<div id="viewModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <!-- View Booking Modal -->
    <div class="bg-white rounded-xl p-8 max-w-lg w-full relative">

        <h2 class="text-xl font-bold mb-4 text-black-800 text-center">
            Booking Details
        </h2>

        <div class=" h-1 bg-red-600 mx-auto mt-2 mb-2"></div>



        <div id="viewBookingContent" class="text-gray-700 space-y-2">
            <!-- AJAX content will load here -->
        </div>

        <div class="flex justify-end mt-6">
            <button type="button"
                onclick="closeViewModal()"
                class="px-4 py-2 bg-red-500 rounded hover:bg-red-600 text-black-800">
                Close
            </button>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ===============================
        // MODAL FUNCTIONS
        // ===============================

        function openApproveModal(booking_id) {
            document.getElementById('approveBookingId').value = booking_id;
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }



        window.openApproveModal = openApproveModal;
        window.closeApproveModal = closeApproveModal;


        // ===============================
        // HANDLE FORM SUBMISSION (AJAX)
        // ===============================

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
                        body: new FormData(approveForm)
                    });

                    const data = await res.json();

                    if (data.success) {

                        const bookingId = document.getElementById('approveBookingId').value;

                        const badge = document.getElementById('status-badge-' + bookingId);

                        if (badge) {
                            badge.innerText = data.status || 'in_progress';
                            badge.className =
                                'px-3 py-1 rounded-full text-sm font-semibold border ' +
                                'bg-blue-100 text-blue-700 border-blue-200';
                        }

                        const buttonsContainer =
                            document.getElementById('action-buttons-' + bookingId);

                        if (buttonsContainer) {
                            const buttons =
                                buttonsContainer.querySelectorAll('button');

                            if (buttons.length >= 2) {
                                buttons[0].style.display = 'none';
                                buttons[1].style.display = '';
                            }
                        }

                        closeApproveModal();
                        showToast("Successfully approved booking!", "success");

                    } else {
                        showToast(data.message || "Failed to approve booking", "error");
                    }

                } catch (err) {
                    console.error(err);
                    showToast(err.message || "An error occurred", "error");
                } finally {
                    btn.disabled = false;
                    btn.innerText = "Yes, Approve";
                }

            });
        }

        // ===============================
        // VIEW BOOKING (AJAX)
        // ===============================

        function openViewModal(bookingId) {

            const modal = document.getElementById('viewModal');
            const content = document.getElementById('viewBookingContent');

            content.innerHTML = "Loading...";

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            fetch("<?= site_url('employee/getBookingDetails') ?>/" + bookingId, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        const booking = data.booking;

                        content.innerHTML = `
                         <p><strong>Name:</strong> ${booking.name ?? '-'}</p>
                         <p><strong>Phone:</strong> ${booking.phone ?? '-'}</p>
                         <p><strong>Vehicle:</strong> ${booking.vehicle_model ?? '-'}</p>
                         <p><strong>Service:</strong> ${booking.service ?? '-'}</p>
                         <p><strong>Date:</strong> ${booking.booking_date ?? '-'}</p>
                        <p><strong>Assignment Status:</strong> ${booking.status ?? '-'}</p>

                    <hr class="my-3">

                         <p><strong>Admin Note:</strong></p>
                        <p class="bg-gray-100 p-3 rounded">
                                ${booking.notes ?? 'No note added'}
                             </p>
                                    `;
                    } else {
                        showToast("Failed to fetch booking details", "error");
                    }

                })
                .catch(() => {
                    showToast("An error occurred while fetching booking details", "error");
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        window.openViewModal = openViewModal;
        window.closeViewModal = closeViewModal;


    });
</script>

<?= $this->endSection(); ?>