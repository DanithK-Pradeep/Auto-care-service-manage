<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class=" flex items-center justify-center h-screen" style="background: linear-gradient(135deg, #f43f5e 0%, #3f84e3 100%);">

    <form method="post" action="<?= site_url('admin/login-process') ?>" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md transform hover:scale-105 transition-transform duration-300">
        <?= csrf_field() ?>

        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <p class="text-red-600 mb-4"><?= session()->getFlashdata('error') ?></p>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <p class="text-green-600 mb-4"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>

        <input name="username" placeholder="Username" class="w-full border p-3 mb-4 rounded" />

        <input type="password" name="password" placeholder="Password" class="w-full border p-3 mb-6 rounded" />

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Login
            </button>
            <a href="<?= site_url('/') ?>" class="bg-blue-100 text-gray-900 px-4 py-2 rounded hover:bg-blue-200">
                Back to Home
            </a>
    </form>

</body>

</html>