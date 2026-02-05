<aside class="w-64 bg-white border-r min-h-screen">
    <nav class="p-4 space-y-2">

        <!-- Dashboard -->
        <a href="/admin/dashboard"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'dashboard'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            📊 Dashboard
        </a>

        <!-- Bookings -->
        <a href="/admin/bookings"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'bookings'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            📋 Bookings
        </a>
        <!-- employees -->
        <a href="/admin/employees"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'employees'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            👷 Employees
        </a>
        <!-- Services -->
        <a href="/admin/stations"
            class="block px-4 py-2 rounded
           <?= ($activeMenu ?? '') === 'services'
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:bg-gray-100' ?>">
            🛠️ Services
        </a>

    </nav>
</aside>