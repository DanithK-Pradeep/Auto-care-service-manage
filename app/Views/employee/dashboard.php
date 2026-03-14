<?= $this->extend('employee/layout/empmain'); ?>
<?= $this->section('content'); ?>
<?= $this->include('components/ajax_toast') ?>

<div class="container mx-auto p-6">
    <h1 class="text-2xl mb-4 text-gray-800 font-bold tracking-wide"><?= esc($title ?? 'My Dashboard') ?></h1>
    <div class="mb-4 h-1 bg-red-600"></div>

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

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Attendance Status</h3>
            <p class="text-sm text-gray-500">
                <?php if (!$todayRecord): ?>
                    
                <?php elseif ($todayRecord && empty($todayRecord['check_out'])): ?>
                     (In: <?= date('h:i A', strtotime($todayRecord['check_in'])) ?>)
                <?php else: ?>
                   (Hours: <?= $todayRecord['worked_hours'] ?>)
                <?php endif; ?>
            </p>
        </div>

        <div>
            <?php if (!$todayRecord): ?>
                <a href="<?= site_url('employee/attendance/checkIn') ?>" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Check In Now</a>
            <?php elseif (empty($todayRecord['check_out'])): ?>
                <a href="<?= site_url('employee/attendance/checkOut') ?>" class="px-6 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">Check Out</a>
            <?php endif; ?>
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
            <h3 class="text-lg font-bold text-gray-900 mb-4">Today's Distribution</h3>
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

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Weekly Performance Bar Chart
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyLabels = <?= $chartLabels ?? '[]' ?>;
        const weeklyValues = <?= $chartValues ?? '[]' ?>;

        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: weeklyLabels.length > 0 ? weeklyLabels : ['No Data Yet'],
                datasets: [{
                    label: 'Vehicles Completed',
                    data: weeklyValues.length > 0 ? weeklyValues : [0],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)', // Tailwind Blue 500
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

        // 2. Today's Distribution Doughnut Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Up Next', 'Active Now', 'Handed Over'],
                datasets: [{
                    data: [<?= $assignedCount ?? 0 ?>, <?= $inProgressCount ?? 0 ?>, <?= $handedOverToday ?? 0 ?>],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.9)', // Blue
                        'rgba(249, 115, 22, 0.9)', // Orange
                        'rgba(34, 197, 94, 0.9)' // Green
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
    });
</script>

<?= $this->endSection(); ?>