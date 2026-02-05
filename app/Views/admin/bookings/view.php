<?= $this->extend('admin/layout/main') ?>
<?= $this->section('content') ?>




<div class="max-w-5xl mx-auto p-6">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
      Booking Details
    </h1>

    <a href="<?= site_url('admin/bookings') ?>"
      class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
      ← Back
    </a>
  </div>

  <!-- Main Card -->
  <div class="bg-white rounded-xl shadow p-6">

    <!-- Status -->
    <div class="mb-6">
      <span class="text-sm font-semibold mr-2">Status:</span>

      <span class="px-3 py-1 rounded-full text-white text-sm
        <?= $booking['status'] === 'pending' ? 'bg-yellow-500' : '' ?>
        <?= $booking['status'] === 'approved' ? 'bg-blue-500' : '' ?>
        <?= $booking['status'] === 'completed' ? 'bg-green-600' : '' ?>
        <?= $booking['status'] === 'rejected' ? 'bg-red-600' : '' ?>">
        <?= ucfirst($booking['status']) ?>
      </span>
    </div>

    <!-- Booking Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

      <div>
        <p class="text-gray-500">Customer Name</p>
        <p class="font-semibold text-gray-800"><?= esc($booking['name']) ?></p>
      </div>

      <div>
        <p class="text-gray-500">Phone</p>
        <p class="font-semibold text-gray-800"><?= esc($booking['phone']) ?></p>
      </div>

      <div>
        <p class="text-gray-500">Service</p>
        <p class="font-semibold text-gray-800"><?= esc($booking['service']) ?></p>
      </div>

      <div>
        <p class="text-gray-500">Vehicle Model</p>
        <p class="font-semibold text-gray-800"><?= esc($booking['vehicle_model']) ?></p>
      </div>

      <div>
        <p class="text-gray-500">Booking Date</p>
        <p class="font-semibold text-gray-800"><?= esc($booking['booking_date']) ?></p>
      </div>

      <div>
        <p class="text-gray-500">Booking ID</p>
        <p class="font-semibold text-gray-800">#<?= esc($booking['id']) ?></p>
      </div>

    </div>

    <!-- Message -->
    <div class="mt-6">
      <p class="text-gray-500 mb-1">Customer Message</p>
      <div class="bg-gray-100 p-4 rounded text-gray-800">
        <?= esc($booking['message']) ?: '—' ?>
      </div>
    </div>

    <!-- Reject Reason -->
    <?php if ($booking['status'] === 'rejected'): ?>
      <div class="mt-6 border-l-4 border-red-600 bg-red-50 p-4 rounded">
        <p class="font-semibold text-red-700 mb-1">
          Reject Reason
        </p>
        <p class="text-red-800">
          <?= esc($booking['reject_reason']) ?>
        </p>
      </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="mt-8 flex gap-3">

      <?php if ($booking['status'] === 'pending'): ?>
        <a href="<?= site_url('admin/bookings/approve/' . $booking['id']) ?>"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
          Approve
        </a>

        <button onclick="openRejectModal(<?= $booking['id'] ?>)"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
          Reject
        </button>
      <?php endif; ?>

    </div>

  </div>
</div>

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
  function openRejectModal(id) {
    document.getElementById('rejectBookingId').value = id;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
  }

  function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
  }
</script>
<?= $this->endSection() ?>