<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - Pembelian & Stok</title>
    @vite(['resources/css/pembelianstok.css', 'resources/css/pembelianstok.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">DHD</div>
        <div class="brand-text"><strong>Interior</strong><span>Management System</span></div>
    </div>
    <nav class="sidebar-menu">
        <div class="menu-group">
            <a href="{{ route('inventori.index') }}" class="menu-item menu-toggle menu-parent-active">
                <i data-lucide="package"></i><span>Inventori & Stok</span>
            </a>
            <div class="submenu open">
                <a href="{{ route('masterharga.index') }}">Master Harga</a>
                <a href="{{ route('supplier.index') }}">Supplier</a>
                <a href="{{ route('pembelianstok.index') }}" class="active">Pembelian & Stok</a>
                <a href="{{ route('stokgudang.index') }}">Stok Gudang</a>
            </div>
        </div>
        <a href="{{ route('laporan.index') }}" class="menu-item"><i data-lucide="file-text"></i><span>Laporan</span></a>
    </nav>
    <div class="sidebar-footer">
        <div class="orange-line"></div>
        <p>DHD Interior<br>Management System</p>
        <div class="footer-actions">
            <button type="button" class="theme-button" onclick="changeTheme()"><i data-lucide="palette"></i><span>Change Theme</span></button>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-button"><i data-lucide="log-out"></i><span>Logout</span></button></form>
        </div>
    </div>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="search-box"><i data-lucide="search"></i><input id="globalSearch" type="search" placeholder="Cari invoice, supplier, material, atau proyek..."></div>
        <div class="topbar-right">
            <div class="date-info"><i data-lucide="calendar-days"></i><div><strong id="currentDate"></strong><span id="currentTime"></span></div></div>
            <button class="notification-button" type="button"><i data-lucide="bell"></i><span class="notification-dot"></span></button>
            <div class="admin-profile"><div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }}</div><span>{{ auth()->user()->name ?? 'Admin' }}</span><i data-lucide="chevron-down"></i></div>
        </div>
    </header>

    <section class="content purchase-page">
        <div class="purchase-heading">
            <div>
                <h1><i data-lucide="shopping-cart"></i> Pembelian & Stok</h1>
                <p>Catat pembelian material, biaya distribusi, dan barang masuk ke gudang.</p>
            </div>
            <a class="btn-primary" href="{{ route('pembelianstok.create') }}"><i data-lucide="plus"></i> Tambah Pembelian</a>
        </div>

        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-error">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert-error"><strong>Periksa kembali data:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        @if(!empty($showForm))
        <section class="purchase-card form-card">
            <div class="section-heading">
                <div><span class="section-icon"><i data-lucide="file-plus-2"></i></span><div><h2>{{ isset($purchase) && $purchase ? 'Edit Pembelian' : 'Informasi Pembelian' }}</h2><p>Isi informasi invoice dan daftar material yang dibeli.</p></div></div>
                <a class="btn-light" href="{{ route('pembelianstok.index') }}">Kembali ke Riwayat</a>
            </div>
            <form method="POST" action="{{ isset($purchase) && $purchase ? route('pembelianstok.update', $purchase->id) : route('pembelianstok.store') }}" id="purchaseForm">
                @csrf
                @if(isset($purchase) && $purchase) @method('PUT') @endif
                <div class="form-grid">
                    <label>Tanggal Pembelian<input type="date" name="purchase_date" required value="{{ old('purchase_date', isset($purchase) && $purchase ? $purchase->purchase_date->format('Y-m-d') : date('Y-m-d')) }}"></label>
                    <label>No. Invoice<input type="text" name="invoice_number" placeholder="Contoh: INV-001" value="{{ old('invoice_number', $purchase->invoice_number ?? '') }}"></label>
                    <label>Supplier / Toko<select name="supplier_id" required><option value="">Pilih supplier</option>@foreach($suppliers ?? [] as $supplier)<option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id ?? '') == $supplier->id)>{{ $supplier->name }}</option>@endforeach</select></label>
                    <label>Nama Proyek<input type="text" name="project_name" placeholder="Contoh: Springwood 8I" value="{{ old('project_name', $purchase->project_name ?? '') }}"></label>
                    <label>Total Ongkos Kirim (Rp)<input type="number" name="shipping_cost" id="shipping_cost" min="0" step="1" value="{{ old('shipping_cost', $purchase->shipping_cost ?? 0) }}"></label>
                    <label>Status<select name="status"><option value="Tercatat" @selected(old('status', $purchase->status ?? 'Tercatat') == 'Tercatat')>Tercatat</option><option value="Diproses" @selected(old('status', $purchase->status ?? '') == 'Diproses')>Diproses</option><option value="Selesai" @selected(old('status', $purchase->status ?? '') == 'Selesai')>Selesai</option></select></label>
                    <label class="full-field">Catatan<textarea name="notes" rows="2" placeholder="Catatan tambahan...">{{ old('notes', $purchase->notes ?? '') }}</textarea></label>
                </div>

                <div class="items-heading"><div><h3>Daftar Barang</h3><p>Satu invoice dapat berisi beberapa material.</p></div><button type="button" class="btn-light" id="addItem"><i data-lucide="plus"></i> Tambah Barang</button></div>
                <div class="table-wrap">
                    <table class="purchase-entry-table">
                        <thead><tr><th>No.</th><th>Material</th><th>Harga Satuan (Rp)</th><th>Jumlah</th><th>Satuan</th><th>Subtotal</th><th></th></tr></thead>
                        <tbody id="purchaseItems">
                        @php($oldItems = old('items', isset($purchase) && $purchase ? $purchase->items->map(fn($item) => ['material_id'=>$item->material_id,'price'=>$item->unit_price,'quantity'=>$item->quantity,'unit'=>$item->unit])->toArray() : [['material_id'=>'','price'=>'','quantity'=>1,'unit'=>'']]))
                        @foreach($oldItems as $i => $item)
                            <tr class="item-row">
                                <td class="row-no">{{ $i + 1 }}</td>
                                <td><select name="items[{{ $i }}][material_id]" class="material-select" required><option value="">Pilih material</option>@foreach($materials ?? [] as $material)<option value="{{ $material->id }}" data-unit="{{ $material->unit }}" @selected(($item['material_id'] ?? '') == $material->id)>{{ $material->code ?? 'MAT-'.$material->id }} - {{ $material->name }}</option>@endforeach</select></td>
                                <td><input type="number" name="items[{{ $i }}][price]" class="item-price" min="0" step="1" required value="{{ $item['price'] ?? '' }}" placeholder="0"></td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" class="item-qty" min="0.01" step="0.01" required value="{{ $item['quantity'] ?? 1 }}"></td>
                                <td><input type="text" name="items[{{ $i }}][unit]" class="item-unit" value="{{ $item['unit'] ?? '' }}" placeholder="Unit"></td>
                                <td class="line-total">Rp 0</td>
                                <td><button type="button" class="icon-delete" title="Hapus barang"><i data-lucide="trash-2"></i></button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="totals-strip">
                    <div><span>Total Unit</span><strong id="totalUnits">0</strong></div>
                    <div><span>Total Harga Barang</span><strong id="goodsTotal">Rp 0</strong></div>
                    <div><span>Ongkos Kirim</span><strong id="shippingTotal">Rp 0</strong></div>
                    <div class="grand-total"><span>Total Pembelian</span><strong id="grandTotal">Rp 0</strong></div>
                </div>
                <p class="formula-note"><i data-lucide="info"></i> Ongkir dibagikan secara proporsional berdasarkan jumlah unit. Harga pokok per unit = harga barang + alokasi ongkir per unit.</p>
                <div class="form-actions"><a href="{{ route('pembelianstok.index') }}" class="btn-light">Batal</a><button type="submit" class="btn-primary"><i data-lucide="save"></i> Simpan Pembelian</button></div>
            </form>
        </section>
        @else
        <div class="stats-grid">
            <article class="stat-card"><span class="stat-icon blue"><i data-lucide="package-plus"></i></span><div><small>Total Barang Masuk</small><strong>{{ number_format($totalIncoming ?? 0, 0, ',', '.') }}</strong><em>Unit tercatat</em></div></article>
            <article class="stat-card"><span class="stat-icon green"><i data-lucide="receipt"></i></span><div><small>Total Transaksi</small><strong>{{ number_format($totalPurchases ?? 0, 0, ',', '.') }}</strong><em>Invoice pembelian</em></div></article>
        </div>

        <section class="purchase-card history-card">
            <div class="section-heading">
                <div><span class="section-icon"><i data-lucide="history"></i></span><div><h2>Riwayat Pembelian</h2><p>Setiap baris mewakili satu invoice, bukan satu barang.</p></div></div>
                <div class="table-search"><i data-lucide="search"></i><input type="search" id="historySearch" placeholder="Cari invoice, supplier, material, proyek..."></div>
            </div>
            <div class="table-wrap">
                <table class="history-table" id="historyTable">
                    <thead><tr><th>Tanggal</th><th>No. Invoice</th><th>Supplier</th><th>Detail Barang</th><th>Total Unit</th><th>Total Pembelian</th><th>Proyek</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($purchases ?? [] as $row)
                        <tr>
                            <td>
                                {{ $row->purchase_date
                                    ? \Carbon\Carbon::parse($row->purchase_date)->format('d-m-Y')
                                    : '-' }}
                            </td>
                            <td class="invoice-cell">{{ $row->invoice_number }}</td>
                            <td>{{ $row->supplier->name ?? '-' }}</td>
                            <td><div class="material-lines">@foreach($row->items as $item)<span>{{ $item->material_name }} <small>({{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }} {{ $item->unit }})</small></span>@endforeach</div></td>
                            <td>{{ number_format((float)($row->total_quantity ?? 0), 2, ',', '.') }}</td>
                            <td class="money-cell">Rp {{ number_format($row->grand_total, 0, ',', '.') }}</td>
                            <td>{{ $row->project_name ?: '-' }}</td>
                            <td><span class="status-pill">{{ $row->status }}</span></td>
                            <td><div class="row-actions"><a href="{{ route('pembelianstok.edit', $row->id) }}" title="Edit"><i data-lucide="pencil"></i></a><form method="POST" action="{{ route('pembelianstok.destroy', $row->id) }}" onsubmit="return confirm('Hapus invoice ini beserta seluruh detail barang masuknya?')">@csrf @method('DELETE')<button title="Hapus"><i data-lucide="trash-2"></i></button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty-row"><i data-lucide="package-open"></i><strong>Belum ada riwayat pembelian</strong><span>Transaksi yang disimpan akan muncul di sini.</span></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @endif
    </section>
</main>

<script>
    lucide.createIcons();

    const rupiah = n => 'Rp ' + Math.round(Number(n || 0)).toLocaleString('id-ID');
    function calculatePurchase() {
        const rows = [...document.querySelectorAll('.item-row')];
        let units = 0, goods = 0;
        rows.forEach((row, index) => {
            row.querySelector('.row-no').textContent = index + 1;
            const qty = Number(row.querySelector('.item-qty').value || 0);
            const price = Number(row.querySelector('.item-price').value || 0);
            units += qty;
            goods += qty * price;
            row.querySelector('.line-total').textContent = rupiah(qty * price);
        });
        const shipping = Number(document.getElementById('shipping_cost')?.value || 0);
        const put = (id, value) => { const el = document.getElementById(id); if (el) el.textContent = value; };
        put('totalUnits', units.toLocaleString('id-ID'));
        put('goodsTotal', rupiah(goods));
        put('shippingTotal', rupiah(shipping));
        put('grandTotal', rupiah(goods + shipping));
    }
    document.getElementById('addItem')?.addEventListener('click', () => {
        const tbody = document.getElementById('purchaseItems');
        const index = tbody.querySelectorAll('.item-row').length;
        const first = tbody.querySelector('.item-row');
        const clone = first.cloneNode(true);
        clone.querySelectorAll('input').forEach(input => input.value = input.classList.contains('item-qty') ? '1' : '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        clone.querySelectorAll('[name]').forEach(el => el.name = el.name.replace(/items\[\d+\]/, `items[${index}]`));
        tbody.appendChild(clone);
        lucide.createIcons(); calculatePurchase();
    });
    document.getElementById('purchaseItems')?.addEventListener('input', calculatePurchase);
    document.getElementById('purchaseItems')?.addEventListener('change', e => {
        if (e.target.classList.contains('material-select')) {
            const opt = e.target.options[e.target.selectedIndex];
            e.target.closest('tr').querySelector('.item-unit').value = opt.dataset.unit || '';
        }
        calculatePurchase();
    });
    document.getElementById('purchaseItems')?.addEventListener('click', e => {
        const button = e.target.closest('.icon-delete');
        if (!button) return;
        const tbody = document.getElementById('purchaseItems');
        if (tbody.querySelectorAll('.item-row').length === 1) {
            const row = button.closest('tr');
            row.querySelectorAll('input').forEach(input => input.value = input.classList.contains('item-qty') ? '1' : '');
            row.querySelector('select').selectedIndex = 0;
        } else button.closest('tr').remove();
        calculatePurchase();
    });
    document.getElementById('shipping_cost')?.addEventListener('input', calculatePurchase);
    calculatePurchase();

    document.getElementById('historySearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#historyTable tbody tr').forEach(row => row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none');
    });
    document.getElementById('globalSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const table = document.querySelectorAll('#historyTable tbody tr');
        table.forEach(row => row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none');
    });
    function updateClock() {
        const now = new Date();
        const date = document.getElementById('currentDate');
        const time = document.getElementById('currentTime');
        if (date) date.textContent = now.toLocaleDateString('id-ID', {weekday:'long', day:'2-digit', month:'long', year:'numeric'});
        if (time) time.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) + ' WIB';
    }
    updateClock(); setInterval(updateClock, 1000);
    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
    }
    if (localStorage.getItem('dhd-theme') === 'pink') document.body.classList.add('pink-theme');
</script>
</body>
</html>
