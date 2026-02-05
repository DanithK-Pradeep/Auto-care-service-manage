<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Admin Panel' ?></title>
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
    <?= $this->include('admin/partials/header') ?>

    <div class="admin-wrapper">

        <!-- SIDEBAR -->
        <?= $this->include('admin/partials/sidebar') ?>

        <!-- PAGE CONTENT -->
        <main class="admin-content">
            <?= $this->renderSection('content') ?>
        </main>

    </div>

    <!-- FOOTER -->
    <?= $this->include('admin/partials/footer') ?>

    <?= view('admin/components/toast') ?>
    <script src="<?= base_url('assets/js/ajax-handler.js') ?>"></script>



</body>

</html>