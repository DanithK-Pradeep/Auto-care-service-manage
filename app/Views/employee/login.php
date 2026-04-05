<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Employee Login') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative min-h-screen bg-gray-100 flex items-center justify-center p-4 sm:px-6 lg:px-8 bg-gradient-to-r from-pink-500 to-blue-500">

    <div class="absolute top-6 right-6">
        <a href="<?= site_url('admin/login') ?>" 
           class="flex items-center gap-2 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/50 px-4 py-2 rounded-xl font-bold transition-all shadow-lg active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Admin Login
        </a>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Employee Login</h1>

        <form method="post" action="<?= site_url('employee/login') ?>" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <input type="email" name="email" placeholder="Type your Email" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <input type="password" name="password" placeholder="Password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg shadow-md transition-colors">
                Login
            </button>

            <button type="button" onclick="window.location='<?= site_url('/') ?>'"
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                Back to Home
            </button>
        </form>
    </div>

    <?= $this->include('components/ajax_toast') ?>

</body>
</html>