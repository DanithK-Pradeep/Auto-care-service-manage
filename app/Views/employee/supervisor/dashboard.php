<?= $this->extend('employee/layout/empmain') ?>

<?= $this->section('content') ?>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Vehicle Release & Billing Panel</h2>
    </div>

    <div class="mb-4 h-1 bg-red-600"></div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-200 text-gray-600 text-sm uppercase">
                    <th class="p-4">Booking ID</th>
                    <th class="p-4">Vehicle</th>
                    <th class="p-4">Service</th>
                    <th class="p-4">Spare Parts Cost</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($readyBookings)): ?>
                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                           No vehicles waiting for release.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($readyBookings as $b): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4 font-bold text-gray-700">#<?= $b['id'] ?></td>
                            <td class="p-4 font-semibold text-gray-900"><?= esc($b['vehicle_model'] ?? 'N/A') ?></td>
                            <td class="p-4 text-gray-600"><?= esc($b['service'] ?? 'N/A') ?></td>
                            <td class="p-4 font-bold text-red-600">Rs. <?= number_format($b['total_spare_parts_cost'], 2) ?></td>
                            <td class="p-4 text-center">
                                <span class="px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-full">
                                    <?= esc($b['status'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td class="p-4 flex justify-center gap-2">
                                <button onclick="openBillingModal(<?= $b['id'] ?>, <?= $b['total_spare_parts_cost'] ?>)" 
                                    class="px-4 py-2 bg-green-600 text-sm text-white font-bold rounded-lg hover:bg-green-700 shadow-md transition transform hover:scale-105">
                                    Bill & Release
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="billingModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden items-center justify-center z-[100] p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-95 duration-300" id="billingCard">
        
        <div class="px-6 py-4 bg-green-50 border-b border-green-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-green-800">Final Invoice & Handover</h2>
            <button onclick="closeBillingModal()" class="text-green-600 hover:text-green-800 text-2xl font-bold">&times;</button>
        </div>

        <div class="p-6 space-y-4">
            <input type="hidden" id="billBookingId">
            <input type="hidden" id="billSparePartsCost">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Service Charge (Rs.)</label>
                <input type="number" id="serviceCharge" value="0" min="0" oninput="calculateTotal()" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-lg font-bold text-gray-800">
            </div>

            <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-200">
                <span class="font-bold text-gray-600">Spare Parts Cost:</span>
                <span class="font-bold text-red-600 text-lg" id="displaySpareCost">Rs. 0.00</span>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Discount (Rs.) <span class="text-gray-400 font-normal">- Optional</span></label>
                <input type="number" id="discount" value="0" min="0" oninput="calculateTotal()" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <hr class="border-gray-200">

            <div class="flex justify-between items-center bg-green-100 p-4 rounded-xl border border-green-300">
                <span class="text-xl font-extrabold text-green-900">NET TOTAL:</span>
                <span class="text-2xl font-extrabold text-green-700" id="displayNetTotal">Rs. 0.00</span>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Payment Method</label>
                <select id="paymentMethod" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 font-semibold text-gray-700">
                    <option value="Cash">💵 Cash</option>
                    <option value="Card">💳 Credit/Debit Card</option>
                    <option value="Online">🏦 Online Transfer</option>
                </select>
            </div>
        </div>

        <div class="px-6 py-4 border-t bg-gray-50 flex justify-end gap-3">
            <button onclick="closeBillingModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-100">Cancel</button>
            <button onclick="submitRelease()" id="btnRelease" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-md">
                Confirm & Release
            </button>
        </div>
    </div>
</div>

<script>
    function openBillingModal(id, spareCost) {
        document.getElementById('billBookingId').value = id;
        document.getElementById('billSparePartsCost').value = spareCost;
        document.getElementById('displaySpareCost').innerText = 'Rs. ' + parseFloat(spareCost).toFixed(2);
        
        // Reset values
        document.getElementById('serviceCharge').value = '';
        document.getElementById('discount').value = '0';
        
        calculateTotal();

        const modal = document.getElementById('billingModal');
        const card = document.getElementById('billingCard');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => { card.classList.remove('scale-95'); card.classList.add('scale-100'); }, 10);
    }

    function closeBillingModal() {
        const modal = document.getElementById('billingModal');
        const card = document.getElementById('billingCard');
        card.classList.remove('scale-100'); card.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    function calculateTotal() {
        let service = parseFloat(document.getElementById('serviceCharge').value) || 0;
        let parts = parseFloat(document.getElementById('billSparePartsCost').value) || 0;
        let discount = parseFloat(document.getElementById('discount').value) || 0;

        let net = (service + parts) - discount;
        if(net < 0) net = 0; 

        document.getElementById('displayNetTotal').innerText = 'Rs. ' + net.toFixed(2);
    }

    function submitRelease() {
        const btn = document.getElementById('btnRelease');
        btn.disabled = true; btn.innerText = "Processing...";

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        formData.append('booking_id', document.getElementById('billBookingId').value);
        formData.append('service_charge', document.getElementById('serviceCharge').value || 0);
        formData.append('spare_parts_cost', document.getElementById('billSparePartsCost').value || 0);
        formData.append('discount', document.getElementById('discount').value || 0);
        formData.append('payment_method', document.getElementById('paymentMethod').value);

        fetch('<?= site_url('employee/supervisor/release') ?>', {
            method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            closeBillingModal();
            window.showToast(data.message, data.success ? 'success' : 'error');
            if(data.success) setTimeout(() => location.reload(), 1500);
            else { btn.disabled = false; btn.innerText = "Confirm & Release"; }
        })
        .catch(error => {
            window.showToast("Network Error", "error");
            btn.disabled = false; btn.innerText = "Confirm & Release";
        });
    }
</script>

<?= $this->endSection() ?>