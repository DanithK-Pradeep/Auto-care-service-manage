<?php $this->extend('admin/layout/main'); ?>
<?= $this->section('content'); ?>




<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Employees Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- View Employees -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Employees</h2>
            <p class="text-gray-600 mb-4">
                View and manage all service center employees.
            </p>
            <a href="<?= site_url('admin/employees/list') ?>"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                View All Employees
            </a>
        </div>

        <!-- Assign Employees -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-2">Assignments</h2>
            <p class="text-gray-600 mb-4">
                Assign employees to service stations.
            </p>
            <a href="<?= site_url('admin/employees/assign') ?>"
               class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Assign Employees
            </a>
        </div>

        <!-- Add Employee -->
        <div class="bg-white shadow rounded-lg p-6 ">
            <h2 class="text-xl font-semibold mb-2">Add Employee</h2>
            <p class="text-gray-600 mb-4">Create a new employee profile.</p>
            <a href="<?= site_url('admin/employees/create') ?>"
               class=" inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                + Add Employee
            </a>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>