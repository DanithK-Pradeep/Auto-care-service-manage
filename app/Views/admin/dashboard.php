<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="p-6 space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">
                Welcome, <span class="font-semibold"><?= esc(session()->get('admin_username')) ?></span>
            </p>
        </div>

        <div class="flex gap-2">
            <a href="<?= base_url('admin/bookings/create') ?>"
               class="px-4 py-2 rounded-lg bg-gray-900 text-white hover:bg-gray-800">
                + New Booking
            </a>
            <a href="<?= base_url('admin/services') ?>"
               class="px-4 py-2 rounded-lg bg-gray-100 text-gray-900 hover:bg-gray-200">
                Manage Services
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
            // For now default to 0 so page works even before controller sends real data
            $stats = $stats ?? [
                'totalBookings' => 0,
                'todayBookings' => 0,
                'pendingBookings' => 0,
                'totalServices' => 0,
            ];
        ?>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['totalBookings']) ?></p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Today’s Bookings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['todayBookings']) ?></p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Pending Bookings</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['pendingBookings']) ?></p>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <p class="text-sm text-gray-500">Total Services</p>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['totalServices']) ?></p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white border rounded-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <h2 class="font-semibold text-gray-900">Recent Bookings</h2>
                <a href="<?= base_url('admin/bookings') ?>" class="text-sm text-gray-700 hover:underline">View all</a>
            </div>

            <?php $recentBookings = $recentBookings ?? []; ?>

            <?php if (empty($recentBookings)): ?>
                <div class="p-5 text-gray-500">No bookings yet.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="text-left px-5 py-3">Date</th>
                                <th class="text-left px-5 py-3">Customer</th>
                                <th class="text-left px-5 py-3">Service</th>
                                <th class="text-left px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentBookings as $b): ?>
                                <tr class="border-t">
                                    <td class="px-5 py-3"><?= esc($b['date'] ?? '-') ?></td>
                                    <td class="px-5 py-3"><?= esc($b['customer'] ?? '-') ?></td>
                                    <td class="px-5 py-3"><?= esc($b['service'] ?? '-') ?></td>
                                    <td class="px-5 py-3">
                                        <?php $st = strtolower(trim($b['status'] ?? 'pending')); ?>
                                        <span class="inline-block px-2 py-1 rounded text-white
                                            <?= $st === 'active' || $st === 'completed' ? 'bg-green-700' : ($st === 'cancelled' ? 'bg-red-700' : 'bg-yellow-600') ?>">
                                            <?= esc($b['status'] ?? 'Pending') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Alerts / Quick Info -->
        <div class="bg-white border rounded-xl">
            <div class="px-5 py-4 border-b">
                <h2 class="font-semibold text-gray-900">Alerts</h2>
                <p class="text-sm text-gray-500 mt-1">Things that need attention</p>
            </div>

            <?php
                $alerts = $alerts ?? [
                    ['label' => 'Pending bookings', 'value' => $stats['pendingBookings'], 'link' => base_url('admin/bookings?status=pending')],
                    ['label' => 'Services need update', 'value' => 0, 'link' => base_url('admin/services')],
                    ['label' => 'Unassigned employees', 'value' => 0, 'link' => base_url('admin/assign')],
                ];
            ?>

            <div class="p-5 space-y-3">
                <?php foreach ($alerts as $a): ?>
                    <a href="<?= esc($a['link']) ?>" class="block p-4 rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-gray-800 font-medium"><?= esc($a['label']) ?></div>
                            <div class="text-gray-900 font-bold"><?= esc($a['value']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
