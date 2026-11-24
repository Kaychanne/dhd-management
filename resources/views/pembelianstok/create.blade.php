<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Tambah Pembelian</title>

    @vite(['resources/css/inventori.css'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .items-container {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .item-box {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            background: #fcfdfe;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .item-box-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf1f5;
        }

        .item-box-title {
            font-size: 13px;
            font-weight: 650;
            color: var(--dhd-blue);
        }

        .btn-remove-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--red);
            background: var(--red-light);
            cursor: pointer;
        }

        .btn-remove-item:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .item-calc-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            background: var(--dhd-blue-light);
            color: var(--text-dark);
            font-size: 12px;
        }

        .summary-box {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid var(--border);
        }

        .summary-box > div {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .summary-box span {
            font-size: 11px;
            color: var(--text-light);
        }

        .summary-box strong {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .summary-highlight {
            padding-left: 12px;
            border-left: 2px solid var(--dhd-blue);
        }

        .summary-highlight strong {
            color: var(--dhd-blue);
            font-size: 16px;
        }

        .alert-box-error {
            padding: 14px 16px;
            border-radius: 10px;
            background: var(--red-light);
            border: 1px solid #f5c2c2;
            color: #b43c3c;
            font-size: 12px;
        }

        .alert-box-error ul {
            margin-top: 6px;
            padding-left: 18px;
        }

        @media (max-width: 900px) {
            .summary-box {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .summary-box {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="dashboard-wrapper">
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">DHD</div>
            <div class="brand-text">
                <strong>Interior</strong>
                <span>Management System</span>
            </div>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-group">
                <a href="{{ route('inventori.index') }}" class="menu-item menu-toggle menu-parent-active">
                    <i data-lucide="package"></i>
                    <span>Inventori &amp; Stok</span>
                    <i data-lucide="chevron-up" class="arrow"></i>
                </a>

                <div class="submenu open">
                    <a href="{{ route('masterharga.index') }}">
                        Master Harga
                    </a>
                    <a href="{{ route('supplier.index') }}">
                        Supplier
                    </a>
                    <a href="{{ route('pembelianstok.index') }}" class="active">
                        Pembelian &amp; Stok
                    </a>
                    <a href="{{ route('stokgudang.index') }}">
                        Stok Gudang
                    </a>
                </div>
            </div>

            <a href="{{ route('laporan.index') }}" class="menu-item">
                <i data-lucide="file-text"></i>
                <span>Laporan</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="orange-line"></div>
            <p>DHD Interior<br>Management System</p>
            <button type="button" class="theme-button" onclick="changeTheme()">
                <i data-lucide="palette"></i>
                <span>Change Theme</span>
            </button>
            <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button">
                    <i data-lucide="log-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari proyek, barang, supplier atau transaksi...">
            </div>

            <div class="topbar-right">
                <div class="date-info">
                    <i data-lucide="calendar-days"></i>
                    <div>
                        <strong id="currentDate"></strong>
                        <span id="currentTime"></span>
                    </div>
                </div>

                <button type="button" class="notification-button">
                    <i data-lucide="bell"></i>
                    <span class="notification-dot"></span>
                </button>

                <div class="admin-profile">
                    <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }}</div>
                    <span>{{ auth()->user()->name ?? 'Steven' }}</span>
                    <i data-lucide="chevron-down"></i>
                </div>
            </div>
        </header>

        <section class="content">
            <div class="inventory-page">

                {{-- HEADER PAGE --}}
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ route('pembelianstok.index') }}">Pembelian &amp; Stok</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Tambah Pembelian</span>
                        </div>
                        <h1>Tambah Pembelian Baru</h1>
                        <p>Isi formulir di bawah ini untuk mencatat pembelian material, proyek, dan ongkos kirim.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('pembelianstok.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert-box-error">
                        <strong>Periksa kembali data pembelian:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM CREATE PEMBELIAN --}}
                <form action="{{ route('pembelianstok.store') }}" method="POST" class="form-card" id="purchaseCreateForm">
                    @csrf

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI PEMBELIAN & PROYEK --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="file-text"></i>
                                Informasi Pembelian &amp; Proyek
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="purchase_date">
                                        Tanggal Pembelian <span class="required">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        id="purchase_date"
                                        name="purchase_date"
                                        value="{{ old('purchase_date', date('Y-m-d')) }}"
                                        required
                                    >
                                    @error('purchase_date')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="invoice_number">No. Nota / Invoice</label>
                                    <input
                                        type="text"
                                        id="invoice_number"
                                        name="invoice_number"
                                        value="{{ old('invoice_number') }}"
                                        placeholder="Kosongkan untuk nomor otomatis"
                                    >
                                    @error('invoice_number')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="supplier_id">
                                        Pilih Supplier <span class="required">*</span>
                                    </label>
                                    <select id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="project_name">Nama Proyek</label>
                                    <input
                                        type="text"
                                        id="project_name"
                                        name="project_name"
                                        value="{{ old('project_name') }}"
                                        placeholder="Contoh: Springwood 8I atau Stok Gudang"
                                    >
                                    @error('project_name')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipping_cost">Total Ongkos Kirim (Rp)</label>
                                    <input
                                        type="number"
                                        step="1"
                                        min="0"
                                        id="shipping_cost"
                                        name="shipping_cost"
                                        value="{{ old('shipping_cost', 0) }}"
                                        oninput="calculateTotals()"
                                    >
                                    @error('shipping_cost')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="Tercatat" {{ old('status', 'Tercatat') == 'Tercatat' ? 'selected' : '' }}>Tercatat</option>
                                        <option value="Diproses" {{ old('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="notes">Catatan Tambahan</label>
                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="3"
                                        placeholder="Tuliskan catatan pembelian atau keterangan proyek..."
                                    >{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 2: RINCIAN BARANG --}}
                        <div class="form-section">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                                <h3 class="section-title">
                                    <i data-lucide="package-plus"></i>
                                    Rincian Barang Masuk
                                </h3>
                                <button type="button" class="btn btn-secondary" onclick="addItemRow()">
                                    <i data-lucide="plus"></i>
                                    Tambah Barang
                                </button>
                            </div>

                            <div class="items-container" id="itemsList">
                                <div class="item-box">
                                    <div class="item-box-head">
                                        <span class="item-box-title">Barang 1</span>
                                        <button type="button" class="btn-remove-item" onclick="removeItemRow(this)" disabled>
                                            <i data-lucide="trash-2"></i>
                                            Hapus
                                        </button>
                                    </div>

                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label>Sumber Data <span class="required">*</span></label>
                                            <select class="source-select" name="items[0][source]" onchange="changeSource(this)" required>
                                                <option value="master">Master Harga</option>
                                                <option value="manual">Input Manual</option>
                                            </select>
                                        </div>

                                        <div class="form-group master-field">
                                            <label>Material &amp; Harga Acuan <span class="required">*</span></label>
                                            <select class="price-select" name="items[0][price_id]" onchange="selectMasterPrice(this)" required>
                                                <option value="">-- Pilih Material &amp; Harga --</option>
                                                @foreach($prices ?? [] as $price)
                                                    <option
                                                        value="{{ $price->id }}"
                                                        data-material-id="{{ $price->material_id }}"
                                                        data-price="{{ $price->price }}"
                                                        data-unit="{{ $price->material_unit }}"
                                                    >
                                                        {{ $price->material_code ?: 'MAT-'.$price->material_id }} - {{ $price->material_name }} (Rp {{ number_format($price->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        
                                        </div>

                                        <div class="form-group">
                                            <label>Harga Satuan (Rp) <span class="required">*</span></label>
                                            <input type="number" class="item-price" name="items[0][price]" min="0" step="0.01" value="0" required oninput="calculateTotals()">
                                        </div>

                                        <div class="form-group">
                                            <label>Jumlah <span class="required">*</span></label>
                                            <input type="number" class="item-quantity" name="items[0][quantity]" min="0.01" step="0.01" value="1" required oninput="calculateTotals()">
                                        </div>

                                        <div class="form-group col-span-2">
                                            <label>Satuan</label>
                                            <input type="text" class="item-unit" name="items[0][unit]" placeholder="Otomatis terisi dari material" readonly>
                                        </div>
                                    </div>

                                    <div class="item-calc-bar">
                                        <span>Subtotal Barang: <strong class="row-subtotal">Rp 0</strong></span>
                                        <span>Harga Pokok / Unit: <strong class="row-unit-cost">Rp 0</strong></span>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-box">
                                <div>
                                    <span>Total Unit</span>
                                    <strong id="totalQuantity">0</strong>
                                </div>
                                <div>
                                    <span>Total Harga Barang</span>
                                    <strong id="goodsTotal">Rp 0</strong>
                                </div>
                                <div>
                                    <span>Total Ongkos Kirim</span>
                                    <strong id="shippingTotal">Rp 0</strong>
                                </div>
                                <div class="summary-highlight">
                                    <span>Total Pembelian</span>
                                    <strong id="grandTotal">Rp 0</strong>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ route('pembelianstok.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Pembelian
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </main>
</div>

<script>
    const money = value => {
        const num = Number(value);
        if (!Number.isFinite(num)) return 'Rp 0';
        return 'Rp ' + num.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    };

    const setText = (id, text) => {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    };

    function reindexRows() {
        const rows = document.querySelectorAll('.item-box');
        rows.forEach((row, index) => {
            const titleEl = row.querySelector('.item-box-title');
            if (titleEl) titleEl.textContent = 'Barang ' + (index + 1);

            row.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/items\[\d+\]/, `items[${index}]`);
            });

            const removeBtn = row.querySelector('.btn-remove-item');
            if (removeBtn) removeBtn.disabled = rows.length === 1;
        });
    }

    function changeSource(select) {
        const row = select.closest('.item-box');
        if (!row) return;

        const isMaster = select.value === 'master';
        const masterField = row.querySelector('.master-field');
        const manualField = row.querySelector('.manual-field');
        const priceSelect = row.querySelector('.price-select');
        const materialSelect = row.querySelector('.material-select');
        const itemPrice = row.querySelector('.item-price');
        const itemUnit = row.querySelector('.item-unit');

        if (masterField) masterField.hidden = !isMaster;
        if (manualField) manualField.hidden = isMaster;

        if (priceSelect) {
            priceSelect.disabled = !isMaster;
            priceSelect.required = isMaster;
            priceSelect.value = '';
        }
        if (materialSelect) {
            materialSelect.disabled = isMaster;
            materialSelect.required = !isMaster;
            materialSelect.value = '';
        }
        if (itemPrice) itemPrice.value = 0;
        if (itemUnit) itemUnit.value = '';

        calculateTotals();
    }

    function selectMasterPrice(select) {
        const option = select.selectedOptions[0];
        const row = select.closest('.item-box');
        if (!row) return;

        const itemPrice = row.querySelector('.item-price');
        const itemUnit = row.querySelector('.item-unit');

        if (!option || !option.value) {
            if (itemPrice) itemPrice.value = 0;
            if (itemUnit) itemUnit.value = '';
            calculateTotals();
            return;
        }

        if (itemPrice) itemPrice.value = Number(option.dataset.price || 0);
        if (itemUnit) itemUnit.value = option.dataset.unit || '';
        calculateTotals();
    }

    function selectManualMaterial(select) {
        const row = select.closest('.item-box');
        if (!row) return;
        const itemUnit = row.querySelector('.item-unit');
        if (itemUnit) {
            itemUnit.value = select.selectedOptions[0]?.dataset.unit || '';
        }
        calculateTotals();
    }

    function addItemRow() {
        const list = document.getElementById('itemsList');
        const first = list?.querySelector('.item-box');
        if (!list || !first) return;

        const clone = first.cloneNode(true);

        clone.querySelectorAll('input').forEach(input => {
            if (input.classList.contains('item-quantity')) {
                input.value = 1;
            } else if (input.type === 'number') {
                input.value = 0;
            } else {
                input.value = '';
            }
        });

        clone.querySelectorAll('select').forEach(select => {
            select.value = select.classList.contains('source-select') ? 'master' : '';
            select.disabled = false;
        });

        const masterField = clone.querySelector('.master-field');
        const manualField = clone.querySelector('.manual-field');
        const priceSelect = clone.querySelector('.price-select');
        const materialSelect = clone.querySelector('.material-select');

        if (masterField) masterField.hidden = false;
        if (manualField) manualField.hidden = true;
        if (priceSelect) {
            priceSelect.disabled = false;
            priceSelect.required = true;
        }
        if (materialSelect) {
            materialSelect.disabled = true;
            materialSelect.required = false;
        }

        const subEl = clone.querySelector('.row-subtotal');
        const unitCostEl = clone.querySelector('.row-unit-cost');
        if (subEl) subEl.textContent = money(0);
        if (unitCostEl) unitCostEl.textContent = money(0);

        list.appendChild(clone);
        reindexRows();
        if (window.lucide) lucide.createIcons();
        calculateTotals();
    }

    function removeItemRow(button) {
        const rows = document.querySelectorAll('.item-box');
        if (rows.length <= 1) return;
        button.closest('.item-box')?.remove();
        reindexRows();
        calculateTotals();
    }

    function calculateTotals() {
        const rows = [...document.querySelectorAll('.item-box')];
        const shipping = Number(document.getElementById('shipping_cost')?.value) || 0;

        let totalQty = 0;
        let goods = 0;

        rows.forEach(row => {
            const qty = Number(row.querySelector('.item-quantity')?.value) || 0;
            const price = Number(row.querySelector('.item-price')?.value) || 0;
            totalQty += qty;
            goods += qty * price;
        });

        const shippingPerUnit = totalQty > 0 ? shipping / totalQty : 0;

        rows.forEach(row => {
            const qty = Number(row.querySelector('.item-quantity')?.value) || 0;
            const price = Number(row.querySelector('.item-price')?.value) || 0;
            const subtotal = qty * price;
            const unitCost = price + shippingPerUnit;

            const subEl = row.querySelector('.row-subtotal');
            const unitCostEl = row.querySelector('.row-unit-cost');
            if (subEl) subEl.textContent = money(subtotal);
            if (unitCostEl) unitCostEl.textContent = money(unitCost);
        });

        setText('totalQuantity', totalQty.toLocaleString('id-ID'));
        setText('goodsTotal', money(goods));
        setText('shippingTotal', money(shipping));
        setText('grandTotal', money(goods + shipping));
    }

    document.getElementById('purchaseCreateForm')?.addEventListener('submit', event => {
        let valid = true;
        document.querySelectorAll('.item-box').forEach(row => {
            const source = row.querySelector('.source-select')?.value;
            const chosen = source === 'master'
                ? row.querySelector('.price-select')?.value
                : row.querySelector('.material-select')?.value;
            if (!chosen) valid = false;
        });
        if (!valid) {
            event.preventDefault();
            alert('Pilih material untuk setiap baris barang terlebih dahulu.');
        }
    });

    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        if (document.body.classList.contains('pink-theme')) {
            localStorage.setItem('dhd-theme', 'pink');
        } else {
            localStorage.setItem('dhd-theme', 'default');
        }
    }

    function updateDateTime() {
        const now = new Date();
        const dateElement = document.getElementById('currentDate');
        const timeElement = document.getElementById('currentTime');

        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) lucide.createIcons();
        if (localStorage.getItem('dhd-theme') === 'pink') {
            document.body.classList.add('pink-theme');
        }
        updateDateTime();
        calculateTotals();
    });

    setInterval(updateDateTime, 1000);
</script>

</body>
</html>