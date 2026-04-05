<aside class="w-64 min-h-screen bg-white/10 backdrop-blur-xl border-r border-white/10 text-white shadow-2xl transition-all duration-300">
    <?php $role = strtolower(trim(session()->get('employee_role') ?? '')); ?>

    <nav class="p-4 space-y-2"
        hx-target="#main-content"
        hx-select="#main-content"
        hx-push-url="true"
        hx-swap="innerHTML ">

        <?php if ($role === 'supervisor'): ?>
            <a href="<?= site_url('employee/dashboard') ?>"
                hx-get="<?= site_url('employee/dashboard') ?>"
                class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                📊 Dashboard
            </a>

            <a href="<?= site_url('employee/bookings') ?>"
                hx-get="<?= site_url('employee/bookings') ?>"
                class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'bookings' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                📋 Bookings
            </a>

            <a href="<?= site_url('employee/supervisor') ?>"
                hx-get="<?= site_url('employee/supervisor') ?>"
                class="nav-link block px-4 py-2.5 mt-4 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'supervisor_dashboard' ? 'bg-green-600 text-white shadow-lg shadow-green-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                💳 Billing & Release
            </a>

            <a href="<?= site_url('employee/supervisor/history') ?>"
                hx-get="<?= site_url('employee/supervisor/history') ?>"
                class="nav-link block px-4 py-2.5 mt-1 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'history' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                📜 Service History
            </a>

        <?php else: ?>
            <a href="<?= site_url('employee/dashboard') ?>"
                hx-get="<?= site_url('employee/dashboard') ?>"
                class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                📊 Dashboard
            </a>

            <a href="<?= site_url('employee/bookings') ?>"
                hx-get="<?= site_url('employee/bookings') ?>"
                class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'bookings' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                📋 Bookings
            </a>

            <a href="<?= site_url('employee/services') ?>"
                hx-get="<?= site_url('employee/services') ?>"
                class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'services' ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
                🛠️ Services
            </a>
        <?php endif; ?>

        <hr class="my-4 border-white/10">

        <a href="<?= site_url('employee/empdetail') ?>"
            hx-get="<?= site_url('employee/empdetail') ?>"
            class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'empdetail' ? 'bg-blue-600 text-white shadow-lg' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
            👨‍💼 Employee Details
        </a>

        <a href="<?= site_url('employee/attendance') ?>"
            hx-get="<?= site_url('employee/attendance') ?>"
            class="nav-link block px-4 py-2.5 rounded-xl transition-all duration-200 <?= ($activeMenu ?? '') === 'attendance' ? 'bg-blue-600 text-white shadow-lg' : 'text-white/80 hover:bg-white/20 hover:text-white' ?>">
            📚 Attendance
        </a>
    </nav>
</aside>