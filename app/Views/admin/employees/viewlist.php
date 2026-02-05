<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>


<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Employees
    </h1>

    <a href="<?= site_url('/admin/employees') ?>"
        class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
        ← Back
    </a>
</div>
<div class="mb-4 h-1 bg-red-600"></div>


<table cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr class="bg-gray-200 text-center border-b-2 ">
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody class="table-group-divider text-center">
        <?php if (!empty($employees)): ?>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= esc($employee['id']) ?></td>
                    <td><?= esc($employee['first_name']) ?> <?= esc($employee['last_name']) ?></td>
                    <td><?= esc($employee['role']) ?></td>
                    
                    <td><?= esc($employee['status']) ?>
                        <?php if ($employee['status'] === 'active'): ?>
                            <span class="text-green-500 bg-green-100 px-2 py-1 rounded-full text-xs font-bold">●</span>
                        <?php else: ?>
                            <span class="text-red-500 bg-red-100 px-2 py-1 rounded-full text-xs font-bold">●</span>
                        <?php endif; ?>

                    </td>

                    <td>
                        <a href="<?= site_url('/admin/employees/view/' . $employee['id']) ?>"
                            class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            View
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center py-4">No employees found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection() ?>