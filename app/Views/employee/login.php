
<?= view('/components/ajax_toast') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Employee Login') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow p-8 transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-2xl font-bold text-center mb-6">Employee Login</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('employee/login') ?>" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
                Login
            </button>
        </form>
    </div>

</body>

</html>