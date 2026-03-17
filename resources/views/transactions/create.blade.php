@extends('layouts.app')
@section('title', 'Transaksi Baru')
@section('header')<h2>Transaksi Baru</h2>@endsection
@section('content')
<form method="POST" action="{{ route('transactions.store') }}" id="trxForm">@csrf
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
    <div style="display:flex;flex-direction:column;gap:1rem">
        <div class="card" style="padding:1.25rem">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--dark)">Data Customer</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div><label class="form-label">Cari Customer</label><input type="text" id="customerSearch" placeholder="Nama / HP / Member..." class="form-input" autocomplete="off"><div id="customerResults" class="absolute z-50 mt-1 w-full max-w-md bg-white rounded-lg shadow-lg border hidden" style="border-color:var(--stone-200)"></div><input type="hidden" name="customer_profile_id" id="customerProfileId"><input type="hidden" name="vehicle_id" id="vehicleId"></div>
                <div><label class="form-label">Customer Terpilih</label><div id="selectedCustomer" style="padding:.65rem .85rem;background:var(--stone-100);border-radius:8px;font-size:.82rem;color:var(--stone-500);min-height:42px;display:flex;align-items:center">Walk-in (tanpa member)</div></div>
            </div>
        </div>
        <div class="card" style="padding:1.25rem">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--dark)">Data Kendaraan</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                <div><label class="form-label">Plat Nomor *</label><input type="text" name="plate_number" required placeholder="B 1234 XX" class="form-input" style="text-transform:uppercase" value="{{ old('plate_number') }}"></div>
                <div><label class="form-label">Jenis *</label><select name="vehicle_type" required class="form-input form-select"><option value="motor">Motor</option><option value="mobil" selected>Mobil</option><option value="suv">SUV/MPV</option><option value="truck">Truck</option></select></div>
            </div>
        </div>
        <div class="card" style="padding:1.25rem">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--dark)">Pilih Layanan</h3>
            @foreach($categories as $cat)
            <div style="margin-bottom:1rem">
                <p style="font-size:.72rem;font-weight:700;color:var(--stone-500);text-transform:uppercase;letter-spacing:1px;margin-bottom:.5rem">{{ $cat->name }}</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem">
                    @foreach($cat->activeServices as $svc)
                    <label class="service-item" data-id="{{ $svc->id }}" data-name="{{ $svc->name }}" data-price="{{ $svc->price }}" style="display:flex;align-items:center;justify-content:space-between;padding:.75rem;border:1.5px solid var(--stone-200);border-radius:8px;cursor:pointer;transition:all .15s">
                        <div style="display:flex;align-items:center;gap:.5rem">
                            <input type="checkbox" class="service-check" value="{{ $svc->id }}" style="accent-color:var(--gold);width:16px;height:16px">
                            <div><p style="font-size:.82rem;font-weight:600;color:var(--dark);margin:0">{{ $svc->name }}</p><p style="font-size:.68rem;color:var(--stone-500);margin:0">{{ $svc->duration_minutes }}min</p></div>
                        </div>
                        <span style="font-size:.82rem;font-weight:700;color:var(--gold-dark)">Rp {{ number_format($svc->price,0,',','.') }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
            <div id="servicesContainer"></div>
        </div>
        <div class="card" style="padding:1.25rem"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-input" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea></div>
    </div>
    <div>
        <div class="card" style="padding:1.25rem;position:sticky;top:80px">
            <h3 style="font-size:.9rem;font-weight:700;margin-bottom:1rem;color:var(--dark)">Ringkasan</h3>
            <div id="summaryItems" style="margin-bottom:1rem"><p style="color:var(--stone-300);font-size:.82rem">Pilih layanan...</p></div>
            <div style="border-top:1px solid var(--stone-200);padding-top:.75rem">
                <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.5rem"><span style="color:var(--stone-500)">Subtotal</span><span id="subtotalDisplay" style="font-weight:600">Rp 0</span></div>
                <div style="margin-bottom:.5rem"><label class="form-label">Diskon (%)</label><input type="number" name="discount_percent" id="discountPercent" value="0" min="0" max="100" class="form-input"></div>
                <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.75rem"><span style="color:var(--stone-500)">Diskon</span><span id="discountDisplay" style="color:#dc2626">- Rp 0</span></div>
                <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">
                <div style="border-top:2px solid var(--dark);padding-top:.75rem;display:flex;justify-content:space-between;margin-bottom:1rem"><span style="font-weight:700;color:var(--dark)">TOTAL</span><span id="totalDisplay" style="font-size:1.25rem;font-weight:800;color:var(--gold-dark)">Rp 0</span></div>
            </div>
            <div style="display:flex;flex-direction:column;gap:.75rem">
                <div><label class="form-label">Metode Bayar *</label><select name="payment_method" required class="form-input form-select"><option value="cash">Cash</option><option value="debit">Debit</option><option value="credit_card">Kartu Kredit</option><option value="ewallet">E-Wallet</option><option value="transfer">Transfer</option></select></div>
                <div><label class="form-label">Jumlah Bayar</label><input type="number" name="payment_amount" id="paymentAmount" class="form-input" placeholder="Auto = total"></div>
                <div style="display:flex;justify-content:space-between;font-size:.85rem"><span style="color:var(--stone-500)">Kembalian</span><span id="changeDisplay" style="font-weight:600;color:#16a34a">Rp 0</span></div>
                <div id="rewardSection" class="hidden" style="padding:.75rem;background:var(--gold-50);border:1px solid var(--gold-100);border-radius:8px">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer"><input type="checkbox" name="is_reward_claim" value="1" style="accent-color:var(--gold)"><span style="font-size:.82rem;font-weight:600;color:var(--gold-dark)">Gunakan Cuci Gratis</span></label>
                </div>
                <button type="submit" class="btn-gold" style="margin-top:.5rem;padding:.85rem;font-size:.95rem;width:100%">Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
let selectedServices=[],grandTotal=0;
const searchInput=document.getElementById('customerSearch'),resultsDiv=document.getElementById('customerResults');
let searchTimeout;
searchInput.addEventListener('input',function(){clearTimeout(searchTimeout);const q=this.value.trim();if(q.length<2){resultsDiv.classList.add('hidden');return;}searchTimeout=setTimeout(()=>{fetch(`{{ route('api.search-customer') }}?q=${encodeURIComponent(q)}`).then(r=>r.json()).then(data=>{if(!data.length)resultsDiv.innerHTML='<p style="padding:.75rem;font-size:.82rem;color:var(--stone-500)">Tidak ditemukan</p>';else resultsDiv.innerHTML=data.map(c=>`<div style="padding:.75rem;cursor:pointer;border-bottom:1px solid var(--stone-100)" onmouseover="this.style.background='var(--gold-50)'" onmouseout="this.style.background='white'" onclick="selectCustomer(${JSON.stringify(c).replace(/"/g,'&quot;')})"><p style="font-size:.82rem;font-weight:600;margin:0">${c.user?.name||'-'}</p><p style="font-size:.7rem;color:var(--stone-500);margin:0">${c.member_code} • ${c.phone||'-'} • Cuci:${c.total_washes}</p></div>`).join('');resultsDiv.classList.remove('hidden');});},300);});
function selectCustomer(c){document.getElementById('customerProfileId').value=c.id;document.getElementById('selectedCustomer').innerHTML=`<div><strong>${c.user?.name}</strong> <span style="color:var(--stone-500)">(${c.member_code})</span><br><span style="font-size:.72rem;color:var(--stone-500)">Cuci:${c.total_washes} | Poin:${c.total_points}</span></div>`;resultsDiv.classList.add('hidden');searchInput.value=c.user?.name||'';if(c.total_washes>=10)document.getElementById('rewardSection').classList.remove('hidden');if(c.vehicles&&c.vehicles.length){const v=c.vehicles[0];document.getElementById('vehicleId').value=v.id;document.querySelector('[name="plate_number"]').value=v.plate_number;document.querySelector('[name="vehicle_type"]').value=v.vehicle_type;}}
document.querySelectorAll('.service-check').forEach(cb=>{cb.addEventListener('change',function(){const item=this.closest('.service-item'),id=item.dataset.id,name=item.dataset.name,price=parseFloat(item.dataset.price);if(this.checked){selectedServices.push({id,name,price,qty:1,discount:0});item.style.borderColor='var(--gold)';item.style.background='var(--gold-50)';}else{selectedServices=selectedServices.filter(s=>s.id!==id);item.style.borderColor='var(--stone-200)';item.style.background='transparent';}updateSummary();});});
function updateSummary(){const c=document.getElementById('summaryItems'),sc=document.getElementById('servicesContainer');if(!selectedServices.length){c.innerHTML='<p style="color:var(--stone-300);font-size:.82rem">Pilih layanan...</p>';sc.innerHTML='';}else{c.innerHTML=selectedServices.map(s=>`<div style="display:flex;justify-content:space-between;padding:.35rem 0;font-size:.82rem"><span>${s.name}</span><span style="font-weight:600">Rp ${parseInt(s.price).toLocaleString('id-ID')}</span></div>`).join('');sc.innerHTML=selectedServices.map((s,i)=>`<input type="hidden" name="services[${i}][service_id]" value="${s.id}"><input type="hidden" name="services[${i}][qty]" value="1"><input type="hidden" name="services[${i}][discount]" value="0">`).join('');}const sub=selectedServices.reduce((a,s)=>a+s.price*s.qty,0),dp=parseFloat(document.getElementById('discountPercent').value)||0,da=sub*(dp/100);grandTotal=sub-da;document.getElementById('subtotalDisplay').textContent='Rp '+sub.toLocaleString('id-ID');document.getElementById('discountDisplay').textContent='- Rp '+Math.round(da).toLocaleString('id-ID');document.getElementById('discountAmountInput').value=da;document.getElementById('totalDisplay').textContent='Rp '+Math.round(grandTotal).toLocaleString('id-ID');updateChange();}
document.getElementById('discountPercent').addEventListener('input',updateSummary);
document.getElementById('paymentAmount').addEventListener('input',updateChange);
function updateChange(){const p=parseFloat(document.getElementById('paymentAmount').value)||0,ch=p>0?Math.max(0,p-grandTotal):0;document.getElementById('changeDisplay').textContent='Rp '+Math.round(ch).toLocaleString('id-ID');}
document.addEventListener('click',e=>{if(!searchInput.contains(e.target)&&!resultsDiv.contains(e.target))resultsDiv.classList.add('hidden');});
</script>
@endpush
