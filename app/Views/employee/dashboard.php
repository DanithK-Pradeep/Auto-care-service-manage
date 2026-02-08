<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Dashboard
    </h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">
            Total Bookings
        </h2>

    </div>
</div>

<?= $this->endSection(); ?> 

