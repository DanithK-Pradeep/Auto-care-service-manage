<?= $this->extend('admin/layout/main') ?>
<?= $this->section('content') ?>

<?= view('admin/components/toast') ?>

<div class="max-w-5xl mx-auto p-6">


    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Employee Details
        </h1>

        <a href="<?= site_url('admin/employees/list') ?>"
            class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            ← Back
        </a>
    </div>

    <div class="mb-4 h-1 bg-red-600"></div>



    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-gray-500">First Name</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['first_name']) ?></p>
            </div>
            <div>
                <p class="text-gray-500">Last Name</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['last_name']) ?></p>
            </div>
            <div>
                <p class="text-gray-500">Phone</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['phone']) ?></p>
            </div>
            <div>
                <p class="text-gray-500">Email</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['email']) ?></p>
            </div>
            <div>
                <p class="text-gray-500">Role</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['role']) ?></p>
            </div>

            <div class="md:row-span-2">
                <p class="text-gray-500">Status</p>

                <div class="flex gap-3">
                    <button id="activeBtn"
                        onclick="changeStatus('active')"
                        class="px-4 py-2 rounded-full text-sm font-bold transition
            <?= $employee['status'] === 'active'
                ? 'bg-green-500 text-white opacity-60'
                : 'bg-gray-300 text-gray-700 hover:bg-green-500 hover:text-white' ?>">
                        Active
                    </button>

                    <button id="inactiveBtn"
                        onclick="changeStatus('inactive')"
                        class="px-4 py-2 rounded-full text-sm font-bold transition
                        <?= $employee['status'] === 'inactive'
                            ? 'bg-red-500 text-white opacity-60'
                            : 'bg-gray-300 text-gray-700 hover:bg-red-500 hover:text-white' ?>">
                        Inactive
                    </button>
                </div>
            </div>

            <div>
                <p class="text-gray-500">Created At</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['created_at']) ?></p>
            </div>
            <div>
                <p class="text-gray-500">Updated At</p>
                <p class="font-semibold text-gray-800"><?= esc($employee['updated_at']) ?></p>
            </div>
        </div>

        <div class="flex justify-end mt-4 gap-4">

            <a href="<?= site_url('admin/employees/edit/' . $employee['id']) ?>"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Edit
            </a>
        </div>
    </div>

    <script>
        // Step 3: JavaScript function to handle AJAX requests with toast notifications
        function changeStatus(status) {
            const employeeId = '<?= $employee['id'] ?>';
            const url = '<?= site_url('/admin/employees/change-status/') ?>' + employeeId;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message || 'Status updated successfully!', 'success');
                        
                        // Update button states
                        const activeBtn = document.getElementById('activeBtn');
                        const inactiveBtn = document.getElementById('inactiveBtn');
                        
                        if (status === 'active') {
                            // Style active button as selected, inactive as unselected
                            activeBtn.classList.add('bg-green-500', 'text-white', 'opacity-60');
                            activeBtn.classList.remove('bg-gray-300', 'text-gray-700', 'hover:bg-green-500', 'hover:text-white');
                            
                            inactiveBtn.classList.remove('bg-red-500', 'text-white', 'opacity-60');
                            inactiveBtn.classList.add('bg-gray-300', 'text-gray-700', 'hover:bg-red-500', 'hover:text-white');
                        } else if (status === 'inactive') {
                            // Style inactive button as selected, active as unselected
                            inactiveBtn.classList.add('bg-red-500', 'text-white', 'opacity-60');
                            inactiveBtn.classList.remove('bg-gray-300', 'text-gray-700', 'hover:bg-red-500', 'hover:text-white');
                            
                            activeBtn.classList.remove('bg-green-500', 'text-white', 'opacity-60');
                            activeBtn.classList.add('bg-gray-300', 'text-gray-700', 'hover:bg-green-500', 'hover:text-white');
                        }
                    } else {
                        showToast(data.message || 'Failed to update status', 'error');
                    }
                })
                .catch(error => {
                    showToast('Network error: ' + error.message, 'error');
                });
        }
    </script>
</div>





<?= $this->endSection() ?>