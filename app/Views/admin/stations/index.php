<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('admin/components/toast') ?>


<div class="max-w-7xl mx-auto p-6">

  <h1 class="text-2xl font-bold mb-6">Service Stations / Bays</h1>
  <div class="mb-4 h-1 bg-red-600"></div>

  <!-- ADD STATION -->
  <form method="post" action="<?= site_url('admin/stations/store') ?>"
        class="  bg-white p-4 rounded-xl shadow mb-6 grid grid-cols-1 md:grid-cols-6 gap-3">

    <?= csrf_field() ?>

    <select name="station_type_id" required class="border rounded p-2">
      <option value="">Select Type</option>
      

      <?php foreach ($types as $type): ?>
        <option value="<?= $type['id'] ?>">
          <?= esc($type['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input name="name" placeholder="Station Name" class="border p-2 rounded" required>
    <input name="bay_no" type="number" placeholder="Bay No" class="border p-2 rounded" required>
    <input name="capacity" type="number" placeholder="Capacity" class="border p-2 rounded" required>

    <button class="bg-blue-600 text-white rounded px-4">
      Add Station
    </button>
  </form>

  <!-- STATIONS TABLE -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3">Name</th>
          <th class="p-3">Type</th>
          <th class="p-3">Bay No</th>
          <th class="p-3">Capacity</th>
          <th class="p-3">Status</th>
          <th class="p-3">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($stations as $s): ?>
          <tr class="border-t text-center">
            <td class="p-3"><?= esc($s['name']) ?></td>
            <td class="p-3"><?= esc($s['type_name']) ?></td>
            <td class="p-3"><?= $s['bay_no'] ?></td>
            <td class="p-3"><?= $s['capacity'] ?></td>
            <td class="p-3">
                
              <span class="px-2 py-1 rounded text-white text-xs
                <?= $s['status'] === 'active' ? 'bg-green-600' : '' ?>
                <?= $s['status'] === 'inactive' ? 'bg-red-600' : '' ?>
                <?= $s['status'] === 'maintenance' ? 'bg-yellow-500' : '' ?>">
                <?= ucfirst($s['status']) ?>
              </span>
            </td>
            <td class="p-3">
              <form method="post" action="<?= site_url('admin/stations/status/'.$s['id']) ?>" class="ajax-form inline">
                <?= csrf_field() ?>
                <button type="submit" class="text-blue-600 bg-transparent border border-blue-600 hover:bg-blue-600 hover:text-white px-4 py-1 rounded">Change Status</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<?= $this->endSection() ?>




