
<?php
// Ensure $role is defined to avoid undefined variable errors
$role = strtolower(trim(session()->get('employee_role') ?? ''));
?>
<div class="flex justify-between items-center w-full">
    <div>
        <h3 class="text-lg font-bold text-gray-800">Attendance (<?= esc(ucfirst($role ?? '')) ?>)</h3>
        <p class="text-sm text-gray-500">
            <?php if (!$todayRecord): ?>
                Ready to start?
            <?php elseif (empty($todayRecord['check_out'])): ?>
                <span class="text-green-600 font-medium italic">In: <?= date('h:i A', strtotime($todayRecord['check_in'])) ?></span>
            <?php else: ?>
                Worked: <span class="font-bold text-gray-700"><?= $todayRecord['worked_hours'] ?> hrs</span>
            <?php endif; ?>
        </p>
    </div>

    <div>
        <?php if (!$todayRecord): ?>
            <button type="button" onclick="processCheckIn()" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition-all active:scale-95">Check In</button>
        <?php elseif (empty($todayRecord['check_out'])): ?>
            <button type="button" onclick="confirmCheckOut()" class="px-6 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md transition-all active:scale-95">Check Out</button>
        <?php endif; ?>
    </div>
</div>