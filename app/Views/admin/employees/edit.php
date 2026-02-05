<?= $this->extend('admin/layout/main') ?>
<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto p-6">


    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Edit Employee Details
        </h1>

        <a href="<?= site_url('admin/employees/view/' . $employee['id']) ?>"
            class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>
    <div class="mb-4 h-1 bg-red-600"></div>

    <form action="<?= site_url('admin/employees/update/' . $employee['id']) ?>" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        <?= csrf_field() ?>
       <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Name</label>
            <input type="text"  name="first_name"  value="<?= $employee['first_name']  ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" value="<?= $employee['last_name'] ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="<?= $employee['email'] ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" value="<?= $employee['phone'] ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>



        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Role</label>
            <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
                <option value="Technician" <?= $employee['role'] === 'Technician' ? 'selected' : '' ?>>Technician</option>
                <option value="Washer" <?= $employee['role'] === 'Washer' ? 'selected' : '' ?>>Washer</option>
                <option value="Painter" <?= $employee['role'] === 'Painter' ? 'selected' : '' ?>>Painter</option>
                <option value="Inspector" <?= $employee['role'] === 'Inspector' ? 'selected' : '' ?>>Inspector</option>
                <option value="Supervisor" <?= $employee['role'] === 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                <option value="Manager" <?= $employee['role'] === 'Manager' ? 'selected' : '' ?>>Manager</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500 focus:outline-none">
                <option value="Active" <?= $employee['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $employee['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        </div>
        <div class=" mt-4 flex justify-end">
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600  ">Update Employee</button>
        </div>
         
    </form>
</div>
<?= $this->endSection() ?>