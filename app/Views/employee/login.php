


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Employee Login') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4 sm:px-6 lg:px-8 bg-gradient-to-r from-pink-500 to-blue-500">

    <div class="w-full max-w-md bg-white rounded-2xl shadow p-8 transform hover:scale-105 transition-transform duration-300">
        <h1 class="text-2xl font-bold text-center mb-6">Employee Login</h1>


        <form method="post" action="<?= site_url('employee/login') ?>" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                
                <input type="email" name="email"  placeholder="Type your Email" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                
                <input type="password" name="password"  placeholder=" Password" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
                Login
            </button>

            <button type="button" onclick="window.location='<?= site_url('/') ?>'"
                class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 rounded-lg">
                Back to Home
            </button>




        </form>
    </div>
<?= $this->include('components/ajax_toast') ?>


</body>

</html>