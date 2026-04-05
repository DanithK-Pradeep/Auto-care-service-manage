<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>

<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-wide">Service History</h1>
            <p class="text-gray-500 text-sm mt-1">View all completed and released bookings.</p>
        </div>
        <a href="<?= site_url('employee/dashboard') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">&larr; Back to Dashboard</a>
    </div>

    <div class="mb-4 h-1 bg-red-600"></div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
        <form action="<?= site_url('employee/supervisor/history') ?>" method="GET" class="flex flex-wrap md:flex-nowrap gap-4 items-end">
            
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-bold text-gray-700 mb-1">Search Vehicle / ID</label>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="e.g. Maruti or 21" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-gray-700 mb-1">From Date</label>
                <input type="date" name="start_date" value="<?= esc($startDate ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-gray-700 mb-1">To Date</label>
                <input type="date" name="end_date" value="<?= esc($endDate ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">Filter</button>
                <a href="<?= site_url('employee/supervisor/history') ?>" class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Vehicle</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Dates</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Parts Cost</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Net Total</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 font-medium">No records found for your search criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $row): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">#<?= esc($row['id']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium"><?= esc($row['vehicle_model'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="text-xs"><b>In:</b> <?= date('Y-m-d', strtotime($row['created_at'])) ?></div>
                                    <div class="text-xs text-green-600"><b>Out:</b> <?= $row['released_at'] ? date('Y-m-d', strtotime($row['released_at'])) : 'Pending' ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-700">Rs. <?= number_format($row['spare_parts_cost'] ?? 0, 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-gray-900">Rs. <?= number_format($row['net_total'] ?? 0, 2) ?>
                                    <span class="block text-xs font-normal text-gray-400"><?= esc($row['payment_method'] ?? '') ?></span>
                               </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if ($row['status'] === 'released'): ?>
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">Released</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">Completed (Unpaid)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>