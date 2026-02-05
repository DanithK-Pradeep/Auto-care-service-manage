<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>


<div class="p-6 max-w-3xl mx-auto">


    <h1 class="text-2xl font-bold mb-6">Add New Employee</h1>

    <div class="mb-4 h-1 bg-red-600"></div>


    <form method="post" action="<?= site_url('admin/employees/store') ?>"class="bg-white shadow rounded-lg p-6 space-y-4">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block text-sm font-medium mb-1">First Name</label>
                <input type="text" name="first_name" required
                    value="<?= old('first_name') ?>"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Last Name</label>
                <input type="text" name="last_name" required
                    value="<?= old('last_name') ?>"
                    class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" name="phone" required
                    value="<?= old('phone') ?>"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required
                    value="<?= old('email') ?>"
                    class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" value="<?= old('password') ?>" id="password"
                        required class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-500 pr-10">
                    <button type="button" onclick="togglePassword('password', 'eye-icon-password')" class="absolute right-3 top-2 text-gray-400">
                        <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zm1.84-.5a9.03 9.03 0 00-7.68-3.98A9.08 9.08 0 0012 3c-2.76 0-5.26 1.3-6.84 3.5L4.5 6.5a9.038 9.038 0 0113.68 0l-.34.34z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" value="<?= old('password_confirmation') ?>" id="confirm_password"
                        required class="w-full border rounded px-3 py-2 focus:ring focus:ring-red-500 pr-10">
                    <button type="button" onclick="togglePassword('confirm_password', 'eye-icon-confirm-password')" class="absolute right-3 top-2 text-gray-400">
                        <svg id="eye-icon-confirm-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zm1.84-.5a9.03 9.03 0 00-7.68-3.98A9.08 9.08 0 0012 3c-2.76 0-5.26 1.3-6.84 3.5L4.5 6.5a9.038 9.038 0 0113.68 0l-.34.34z" />
                        </svg>
                    </button>
                </div>
            </div>


            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" required
                    class="w-full border rounded px-3 py-2">
                    <option value="">-- Select Role --</option>
                    <option value="Technician">Technician</option>
                    <option value="Washer">Washer</option>
                    <option value="Painter">Painter</option>
                    <option value="Inspector">Inspector</option>
                    <option value="Supervisor">Supervisor</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status"
                    class="w-full border rounded px-3 py-2">
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>

        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="<?= site_url('admin/employees') ?>"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit"
                class="px-5 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Save Employee
            </button>
        </div>



    </form>


</div>

<script>
    function closeSuccessPopup() {
        const overlay = document.getElementById('successOverlay');

        if (overlay) {
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.remove(), 300);
        }
    }

    // Auto close after 3 seconds
    setTimeout(closeSuccessPopup, 3000);


    function closeErrorPopup() {
        const overlay = document.getElementById('errorOverlay');

        if (overlay) {
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.remove(), 300);
        }
    }

    // Auto close after 3 seconds
    setTimeout(closeErrorPopup, 3000);



    // Toggle password visibility
    function togglePassword(fieldId, iconId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text"; // Show password
            eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l2 2m0 0l-2 2m2-2H9" />
        `;
        } else {
            passwordField.type = "password"; // Hide password
            eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12c0 2.21-1.79 4-4 4s-4-1.79-4-4 1.79-4 4-4 4 1.79 4 4zm1.84-.5a9.03 9.03 0 00-7.68-3.98A9.08 9.08 0 0012 3c-2.76 0-5.26 1.3-6.84 3.5L4.5 6.5a9.038 9.038 0 0113.68 0l-.34.34z" />
        `;
        }
    }
</script>





<?= $this->endSection() ?>