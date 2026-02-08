<aside class="w-64 bg-white border-r min-h-screen">
    <nav class="p-4 space-y-2">

        <!-- Dashboard -->
        <a href="<?= site_url('employee/dashboard') ?>"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'dashboard'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            📊 Dashboard
        </a>

        <!-- employees Detail    -->
        <a href="<?= site_url('employee/empdetail') ?>"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'empdetail'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            📋 Employee Details
        </a>
        <!-- employees 
        <a href="<?= site_url('employee/employees') ?>"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'employees'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            👷 Employees
        </a>

-->
        <!-- Services -->
        <a href="<?= site_url('employee/services') ?>"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'services'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            🛠️ Services
        </a>

    </nav>
</aside>