<header class="bg-gray-900 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold tracking-wide">
       Welcome to AutoCare
    </h1>

    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-300">
            <?= esc(session()->get('employee_name' )) ?>
        </span>

        <a href="<?= site_url('employee/logout') ?>"
           class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded">
            Logout
        </a>
    </div>
</header>