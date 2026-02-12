<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Employee Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- Simple base styles -->
<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: #fafafa;
    }

    .admin-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .admin-content {
        flex: 1;
        padding: 20px;
    }
</style>
</head>

<body>

    <!-- HEADER -->
    <?= $this->include('employee/partials/header') ?>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <?= $this->include('employee/partials/sidebar') ?>

        <!-- PAGE CONTENT -->
        <main class="employee-content">
            <?= $this->renderSection('content') ?>
        </main>

    </div>

    <!-- FOOTER -->
    <?= $this->include('employee/partials/footer') ?>

    <?= view('components/ajax_toast') ?>

    <script src="<?= base_url('assets/js/ajax-toast.js') ?>"></script>





</body>

</html>