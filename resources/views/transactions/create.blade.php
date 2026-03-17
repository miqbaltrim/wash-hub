@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800">Transaksi Baru</h2>
@endsection

@section('content')
<form method="POST" action="{{ route('transactions.store') }}" id="trxForm">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Customer & Vehicle -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Data Customer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Customer</label>
                        <input type="text" id="customerSearch" placeholder="Nama / HP / Member Code..." class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                        <div id="customerResults" class="absolute z-50 mt-1 w-full max-w-md bg-white rounded-lg shadow-lg border border-gray-200 hidden"></div>
                        <input type="hidden" name="customer_profile_id" id="customerProfileId">
                        <input type="hidden" name="vehicle_id" id="vehicleId">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Terpilih</label>
                        <div id="selectedCustomer" class="p-3 bg-gray-50 rounded-lg text-sm text-gray-500 min-h-[42px] flex items-center">Walk-in (tanpa member)</div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Data Kendaraan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plat Nomor *</label>
                        <input type="text" name="plate_number" required placeholder="B 1234 XX" class="w-full rounded-lg border-gray-300 text-sm uppercase" value="{{ old('plate_number') }}">
                        @error('plate_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan *</label>
                        <select name="vehicle_type" required class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="motor">Motor</option>
                            <option value="mobil" selected>Mobil</option>
                            <option value="suv">SUV / MPV</option>
                            <option value="truck">Truck</option>
                            <option value="bus">Bus</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Pilih Layanan</h3>
                @foreach($categories as $cat)
                <div class="mb-4">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">{{ $cat->name }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($cat->activeServices as $service)
                        <label class="service-item flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition" data-id="{{ $service->id }}" data-name="{{ $service->name }}" data-price="{{ $service->price }}" data-points="{{ $service->points_earned }}">
                            <div class="flex items-center">
                                <input type="checkbox" class="service-check rounded border-gray-300 text-indigo-600 mr-3" value="{{ $service->id }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $service->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $service->duration_minutes }} menit • {{ $service->vehicle_type === 'all' ? 'Semua' : ucfirst($service->vehicle_type) }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-indigo-600">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
                <div id="servicesContainer"></div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Right: Summary -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-4">
                <h3 class="font-semibold text-gray-800 mb-4">Ringkasan</h3>

                <div id="summaryItems" class="space-y-2 mb-4 text-sm"></div>
                <div class="border-t border-gray-200 pt-3 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span id="subtotalDisplay" class="font-semibold">Rp 0</span></div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Diskon (%)</label>
                        <input type="number" name="discount_percent" id="discountPercent" value="0" min="0" max="100" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Diskon</span><span id="discountDisplay" class="text-red-500">- Rp 0</span></div>
                    <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">

                    <div class="border-t border-gray-200 pt-2 flex justify-between"><span class="font-semibold text-gray-800">TOTAL</span><span id="totalDisplay" class="text-xl font-bold text-indigo-600">Rp 0</span></div>
                </div>

                <div class="mt-4 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Bayar *</label>
                        <select name="payment_method" required class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="cash">Cash</option>
                            <option value="debit">Debit</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="ewallet">E-Wallet</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                        <input type="number" name="payment_amount" id="paymentAmount" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Auto = total">
                    </div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Kembalian</span><span id="changeDisplay" class="font-semibold text-green-600">Rp 0</span></div>

                    <!-- Reward Claim -->
                    <div id="rewardSection" class="hidden p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_reward_claim" value="1" id="rewardCheck" class="rounded border-gray-300 text-yellow-600 mr-2">
                            <span class="text-sm font-medium text-yellow-800">Gunakan Cuci Gratis (Reward)</span>
                        </label>
                        <p class="text-xs text-yellow-600 mt-1">Customer punya <span id="rewardCount">0</span> cuci gratis</p>
                    </div>
                </div>

                <button type="submit" class="mt-4 w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition text-sm">
                    Simpan Transaksi
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let selectedServices = [];
let grandTotal = 0;

// Customer search
const searchInput = document.getElementById('customerSearch');
const resultsDiv = document.getElementById('customerResults');
let searchTimeout;

searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { resultsDiv.classList.add('hidden'); return; }
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('api.search-customer') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) { resultsDiv.innerHTML = '<p class="p-3 text-sm text-gray-500">Tidak ditemukan</p>'; }
                else {
                    resultsDiv.innerHTML = data.map(c => `
                        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" onclick="selectCustomer(${JSON.stringify(c).replace(/"/g, '&quot;')})">
                            <p class="text-sm font-medium">${c.user?.name || '-'}</p>
                            <p class="text-xs text-gray-500">${c.member_code} • ${c.phone || '-'} • Cuci: ${c.total_washes} • Poin: ${c.total_points}</p>
                        </div>
                    `).join('');
                }
                resultsDiv.classList.remove('hidden');
            });
    }, 300);
});

function selectCustomer(c) {
    document.getElementById('customerProfileId').value = c.id;
    document.getElementById('selectedCustomer').innerHTML = `<div><span class="font-semibold">${c.user?.name}</span> <span class="text-gray-500">(${c.member_code})</span><br><span class="text-xs text-gray-500">Cuci: ${c.total_washes} | Poin: ${c.total_points}</span></div>`;
    resultsDiv.classList.add('hidden');
    searchInput.value = c.user?.name || '';

    // Check rewards
    const available = Math.floor(c.total_washes / 10) - 0; // simplified
    if (c.total_washes >= 10) {
        document.getElementById('rewardSection').classList.remove('hidden');
        document.getElementById('rewardCount').textContent = available > 0 ? available : '0';
    }

    // Auto-fill vehicle
    if (c.vehicles && c.vehicles.length > 0) {
        const v = c.vehicles[0];
        document.getElementById('vehicleId').value = v.id;
        document.querySelector('[name="plate_number"]').value = v.plate_number;
        document.querySelector('[name="vehicle_type"]').value = v.vehicle_type;
    }
}

// Service selection
document.querySelectorAll('.service-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const item = this.closest('.service-item');
        const id = item.dataset.id;
        const name = item.dataset.name;
        const price = parseFloat(item.dataset.price);

        if (this.checked) {
            selectedServices.push({ id, name, price, qty: 1, discount: 0 });
            item.classList.add('border-indigo-400', 'bg-indigo-50');
        } else {
            selectedServices = selectedServices.filter(s => s.id !== id);
            item.classList.remove('border-indigo-400', 'bg-indigo-50');
        }
        updateSummary();
    });
});

function updateSummary() {
    const container = document.getElementById('summaryItems');
    const servicesContainer = document.getElementById('servicesContainer');

    if (selectedServices.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-sm">Pilih layanan...</p>';
        servicesContainer.innerHTML = '';
    } else {
        container.innerHTML = selectedServices.map((s, i) => `
            <div class="flex justify-between items-center"><span class="text-gray-700">${s.name}</span><span class="font-medium">Rp ${parseInt(s.price).toLocaleString('id-ID')}</span></div>
        `).join('');
        servicesContainer.innerHTML = selectedServices.map((s, i) => `
            <input type="hidden" name="services[${i}][service_id]" value="${s.id}">
            <input type="hidden" name="services[${i}][qty]" value="1">
            <input type="hidden" name="services[${i}][discount]" value="0">
        `).join('');
    }

    const subtotal = selectedServices.reduce((sum, s) => sum + s.price * s.qty, 0);
    const discPct = parseFloat(document.getElementById('discountPercent').value) || 0;
    const discAmt = subtotal * (discPct / 100);
    grandTotal = subtotal - discAmt;

    document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('discountDisplay').textContent = '- Rp ' + Math.round(discAmt).toLocaleString('id-ID');
    document.getElementById('discountAmountInput').value = discAmt;
    document.getElementById('totalDisplay').textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
    updateChange();
}

document.getElementById('discountPercent').addEventListener('input', updateSummary);

document.getElementById('paymentAmount').addEventListener('input', updateChange);
function updateChange() {
    const paid = parseFloat(document.getElementById('paymentAmount').value) || 0;
    const change = paid > 0 ? Math.max(0, paid - grandTotal) : 0;
    document.getElementById('changeDisplay').textContent = 'Rp ' + Math.round(change).toLocaleString('id-ID');
}

document.addEventListener('click', (e) => { if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) resultsDiv.classList.add('hidden'); });
</script>
@endpush
