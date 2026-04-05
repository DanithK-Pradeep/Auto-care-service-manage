<header class="bg-black/30 backdrop-blur-md border-b border-white/10 text-white shadow-lg py-3 px-6">
    <div class="flex justify-between items-center max-w-full mx-auto">
        
        <div class="flex items-center space-x-3">
            <a href="<?= site_url('employee/dashboard') ?>" class="flex items-center hover:opacity-80 transition">
                <img src="<?= base_url('assets/images/logo.png') ?>" 
                     alt="AutoCare Logo" 
                     class="h-10 w-auto object-contain"> <span class="ml-3 text-xl font-extrabold tracking-tighter uppercase italic">
                    Auto<span class="text-blue-400">Care</span>
                </span>
            </a>
        </div>

        <div class="flex items-center space-x-6">
            <div class="text-right hidden md:block">
                <p class="text-xs text-gray-300 font-medium leading-none mb-1 uppercase tracking-widest">Logged in as</p>
                <p class="text-sm font-bold text-white"><?= session()->get('employee_name') ?? 'Employee' ?></p>
            </div>
            
            <a href="<?= site_url('employee/logout') ?>" 
               class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-5 py-2 rounded-lg shadow-lg hover:shadow-red-500/30 transition-all duration-300 transform hover:scale-105">
                LOGOUT
            </a>
        </div>

    </div>
</header>