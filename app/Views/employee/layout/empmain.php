<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->include('components/ajax_toast') ?>

    <meta charset="UTF-8">
    <title><?= $title ?? 'Employee Panel' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        const BASE_URL = "<?= site_url() ?>";
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* 1. Modern Slim Scrollbar */
    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }

    /* 2. Sidebar Hover Fix */
    .nav-link:hover:not(.bg-blue-600):not(.bg-green-600) {
        background-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(4px);
        color: white !important;
    }

    /* 3. Main Content - Layout Shift Fix */
    #main-content {
        min-height: 90vh; 
        position: relative;
        display: block;
        transition: opacity 0.2s ease-in-out;
    }

    /* 4. HTMX Loading & Swapping State */
    
    .htmx-request #main-content,
    .htmx-swapping #main-content,
    .htmx-request#main-content {
        opacity: 0 !important;
        visibility: hidden;
    }

    
    @keyframes smoothPageIn {
        0% { opacity: 0; transform: translateY(12px); filter: blur(4px); }
        100% { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    /* 5. Page Transition Animation */
    #main-content > div {
        animation: smoothPageIn 0.4s ease-out forwards;
    }

    /* 6. Layout Stability Fixes */
    html { scrollbar-gutter: stable; }
    
    .bg-white\/60 {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>
</head>

<body class="antialiased">

    <div class="min-h-screen bg-cover bg-center bg-fixed" style="background-image: url('<?= base_url('assets/images/bg.png') ?>');">
        <div class="min-h-screen bg-white/30 backdrop-blur-md flex flex-col">

            <?= $this->include('employee/partials/header') ?>

            <div class="flex flex-1 overflow-hidden">
                <?= $this->include('employee/partials/sidebar') ?>

                <main class="flex-1 overflow-y-auto p-6" id="main-content">
                    <div class="bg-white/60 p-6 rounded-3xl shadow-xl backdrop-blur-sm border border-white/20">
                        <?= $this->renderSection('content') ?>
                    </div>
                </main>
            </div>

            <?= $this->include('employee/partials/footer') ?>
        </div>
    </div> <?= $this->renderSection('modals') ?>

</body>
</html>