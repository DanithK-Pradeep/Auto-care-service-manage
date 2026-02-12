<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>

<div class="container mx-auto p-6">


    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide">Employee Details</h1>
    <div class="mb-4 h-1 bg-red-600"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <?= esc($employee['first_name'] . ' ' . $employee['last_name']) ?>
                    </h2>
                    <p class="text-sm text-gray-500">Employee Profile</p>
                </div>

                <?php
                $status = strtolower($employee['status'] ?? '');
                $statusClass = match ($status) {
                    'active' => 'bg-green-100 text-green-700 border-green-200',
                    'inactive' => 'bg-red-100 text-red-700 border-red-200',
                };
                ?>
                <span class="px-3 py-1 rounded-full text-sm font-semibold border <?= $statusClass ?>">
                    <?= ucfirst($status) ?>
                </span>
            </div>

            <div class="overflow-x-auto border rounded-xl">
                <table class="w-full text-left">
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <th class="w-1/3 p-4 text-gray-500 font-medium">First Name</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['first_name']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Last Name</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['last_name']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Phone</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['phone']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Email</th>
                            <td class="p-4 font-semibold text-gray-800 break-words"><?= esc($employee['email']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Role</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['role']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Created At</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['created_at']) ?></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <th class="p-4 text-gray-500 font-medium">Updated At</th>
                            <td class="p-4 font-semibold text-gray-800"><?= esc($employee['updated_at']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-6 border border-gray-200 w-full">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Assigned Details</h2>
                    <span class="text-sm text-gray-500">Station / Bay Assignment History</span>
                </div>
            </div>

            <?php if (!empty($assignments)): ?>
                <?php $currentId = $assignments[0]['id'] ?? null; // latest row 
                ?>
                <div class="overflow-x-auto border rounded-xl">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="p-3">Station</th>
                                <th class="p-3">Bay</th>
                                <th class="p-3">Assigned At</th>
                                <th class="p-3">Station Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($assignments as $a): ?>
                                <?php
                                $isCurrent = ($currentId && $a['id'] == $currentId);

                                $stStatus = strtolower($a['station_status'] ?? '');
                                $badge = match ($stStatus) {
                                    'active' => 'bg-green-100 text-green-700 border-green-200',
                                    'inactive' => 'bg-red-100 text-red-700 border-red-200',
                                    'maintenance' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                                ?>

                                <tr class="<?= $isCurrent ? 'bg-green-50 border-l-4 border-green-500' : 'hover:bg-gray-50' ?>">
                                    <td class="p-3 font-semibold <?= $isCurrent ? 'text-green-900' : 'text-gray-800' ?>">
                                        <?= esc($a['station_name'] ?? 'N/A') ?>
                                        <?php if ($isCurrent): ?>
                                            <span class="ml-2 text-xs font-semibold text-green-700 bg-green-100 px-2 py-1 rounded-full border border-green-200">
                                                Current
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="p-3"><?= esc($a['bay_no'] ?? 'N/A') ?></td>
                                    <td class="p-3"><?= esc($a['assigned_at'] ?? 'N/A') ?></td>

                                    <td class="p-3">
                                        <span class="px-2 py-1 text-xs rounded-full border <?= $badge ?>">
                                            <?= ucfirst((string)($a['station_status'] ?? 'N/A')) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            <?php else: ?>
                <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
                    No assignment history found for this employee.
                </div>
            <?php endif; ?>
        </div>

    </div>


    <?= $this->endSection(); ?>