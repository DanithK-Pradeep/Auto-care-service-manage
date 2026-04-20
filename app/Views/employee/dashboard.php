<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>

<?php
// Determine role for conditional content
$role = strtolower(trim(session()->get('employee_role') ?? ''));
?>

<div class="container mx-auto p-6">
    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title ?? 'My Dashboard') ?></h1>
    <div class="mb-4 h-1 bg-red-600"></div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 overflow-hidden">
        <div id="attendance-refresh-target"
            hx-get="<?= site_url('employee/attendance/getAttendanceBar') ?>"
            hx-trigger="attendanceStatusChanged from:body"
            hx-swap="innerHTML">

            <?= view('employee/partials/_attendance_bar') ?>
        </div>
    </div>
</div>
<?= $this->section('modals'); ?>
<div id="checkOutModal" class="fixed inset-0 z-[9999] hidden bg-black/60 backdrop-blur-sm grid place-items-center p-4">

    <div id="modalCard" class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-95 opacity-0 duration-300">

        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2">Ending Shift?</h3>
            <p class="text-sm text-gray-500">Are you sure you want to clock out now? Your working hours will be finalized.</p>
        </div>

        <div class="grid grid-cols-2 border-t border-gray-100">
            <button onclick="closeCheckOutModal()" class="py-4 text-sm font-bold text-gray-400 hover:bg-gray-50 border-r border-gray-100 transition-colors uppercase tracking-widest">
                Cancel
            </button>
            <button onclick="processCheckOut()" class="py-4 text-sm font-black text-red-600 hover:bg-red-50 transition-colors text-center uppercase tracking-widest w-full">
                Yes, Check Out
            </button>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
<?php if ($role === 'supervisor'): ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <a href="<?= site_url('employee/bookings') ?>" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-yellow-500 p-6 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Awaiting</p>
                <h3 class="text-4xl font-extrabold text-gray-800 mt-1"><?= esc($awaitingCount ?? 0) ?></h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
                <span class="text-3xl">⏳</span>
            </div>
        </a>

        <a href="<?= site_url('employee/bookings') ?>" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 p-6 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">In Progress</p>
                <h3 class="text-4xl font-extrabold text-gray-800 mt-1"><?= esc($inProgressCount ?? 0) ?></h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <span class="text-3xl">🚗</span>
            </div>
        </a>

        <a href="<?= site_url('employee/supervisor') ?>" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 p-6 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">To Bill</p>
                <h3 class="text-4xl font-extrabold text-gray-800 mt-1"><?= esc($readyForBillingCount ?? 0) ?></h3>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <span class="text-3xl">💸</span>
            </div>
        </a>

        <a href="<?= site_url('employee/supervisor/history') ?>" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500 p-6 flex items-center justify-between hover:shadow-md transition cursor-pointer">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Done</p>
                <h3 class="text-4xl font-extrabold text-gray-800 mt-1"><?= esc($totalFinishedCount ?? 0) ?></h3>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <span class="text-3xl">🏆</span>
            </div>
        </a>

    </div>

<?php else: ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-blue-500">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Up Next</p>
                <h3 class="text-2xl font-extrabold text-gray-900"><?= esc($assignedCount ?? 0) ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-orange-500">
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Active Now</p>
                <h3 class="text-2xl font-extrabold text-gray-900"><?= esc($inProgressCount ?? 0) ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-green-500">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Handed Over</p>
                <h3 class="text-2xl font-extrabold text-gray-900"><?= esc($handedOverToday ?? 0) ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 border-l-4 border-l-purple-500">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase">Strike Rate</p>
                <h3 class="text-2xl font-extrabold text-gray-900"><?= esc($strikeRate ?? 0) ?>%</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Past 7 Days Output</h3>
            <div class="relative h-64 w-full">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Output Distribution</h3>
            <div class="relative h-64 w-full flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Waiting in Your Bay</h3>
            <a href="<?= site_url('employee/bookings') ?>" class="text-sm font-bold text-blue-600 hover:text-blue-800">View All Bookings &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Vehicle No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Station</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned At</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if (empty($upNextQueue)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">
                                No vehicles currently waiting for you. Great job!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($upNextQueue as $job): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                    <?= esc($job['vehicle_model'] ?? 'N/A') ?>
                                    <span class="block text-xs text-gray-500 font-normal">Booking #<?= esc($job['booking_id']) ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    <?= esc($job['station_name'] ?? 'Unknown Station') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('h:i A', strtotime($job['assigned_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?= site_url('employee/bookings') ?>" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-bold hover:bg-blue-100 transition-colors">
                                        Approve Job
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

<?php if ($role !== 'supervisor'): ?>
    <script>
        // HTMX පාවිච්චි කරන නිසා 'const' වෙනුවට 'var' පාවිච්චි කිරීම ආරක්ෂිතයි
        if (typeof weeklyChartInstance === 'undefined') {
            var weeklyChartInstance = null;
        }
        if (typeof statusChartInstance === 'undefined') {
            var statusChartInstance = null;
        }

        // චාර්ට් එක අඳින ප්‍රධාන ෆන්ක්ෂන් එක
        function initDashboardCharts() {
            // --- 1. Weekly Performance Bar Chart ---
            var weeklyCanvas = document.getElementById('weeklyChart');
            if (weeklyCanvas) {
                var weeklyCtx = weeklyCanvas.getContext('2d');

                // පරණ චාර්ට් එකක් තියෙනවා නම් ඒක අයින් කරනවා (Memory Leak/Error නැති වෙන්න)
                if (weeklyChartInstance) {
                    weeklyChartInstance.destroy();
                }

                var weeklyLabels = <?= $chartLabels ?? '[]' ?>;
                var weeklyValues = <?= $chartValues ?? '[]' ?>;

                weeklyChartInstance = new Chart(weeklyCtx, {
                    type: 'bar',
                    data: {
                        labels: weeklyLabels.length > 0 ? weeklyLabels : ['No Data Yet'],
                        datasets: [{
                            label: 'Vehicles Completed',
                            data: weeklyValues.length > 0 ? weeklyValues : [0],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // --- 2. Today's Distribution Doughnut Chart ---
            var statusCanvas = document.getElementById('statusChart');
            if (statusCanvas) {
                var statusCtx = statusCanvas.getContext('2d');

                if (statusChartInstance) {
                    statusChartInstance.destroy();
                }

                statusChartInstance = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Up Next', 'Active Now', 'Handed Over'],
                        datasets: [{
                            data: [<?= $assignedCount ?? 0 ?>, <?= $inProgressCount ?? 0 ?>, <?= $handedOverToday ?? 0 ?>],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.9)',
                                'rgba(249, 115, 22, 0.9)',
                                'rgba(34, 197, 94, 0.9)'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: {
                                        family: "'Inter', sans-serif",
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // පේජ් එක මුලින්ම ලෝඩ් වෙද්දී රන් කරන්න
        document.addEventListener('DOMContentLoaded', function() {
            initDashboardCharts();
        });

        // මචං, වැදගත්ම දේ: HTMX එකෙන් පේජ් එකේ කෑල්ලක් මාරු වුණොත් ආයෙත් චාර්ට් එක අඳින්න ඕනේ නම් මේ ලයිනර් එක තියන්න
        document.addEventListener('htmx:afterSettle', function() {
            initDashboardCharts();
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>