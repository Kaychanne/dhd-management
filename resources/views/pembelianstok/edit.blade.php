
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Edit Pembelian Stok</title>

    @vite(['resources/css/inventori.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

@php
    // Mendukung pembelian dengan beberapa item.
    $purchaseItems = old('items');

    if (!$purchaseItems) {
        $purchaseItems = isset($purchase->items)
            ? $purchase->items->values()->all()
            : [$purchase];
    }

    $purchaseDate = old(
        'purchase_date',
        $purchase->purchase_date ?? $purchase->transaction_date ?? ''
    );

    $invoiceNumber = old(
        'invoice_number',
        $purchase->invoice_number ?? $purchase->reference_number ?? ''
    );

    $supplierId = old('supplier_id', $purchase->supplier_id ?? '');

    $shippingCost = old(
        'shipping_cost',
        $purchase->shipping_cost ?? 0
    );

    $notes = old('notes', $purchase->notes ?? '');
@endphp

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

            <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}"
               class="menu-item">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="menu-item">
                <i data-lucide="clipboard-list"></i>
                <span>RAP & Anggaran</span>
            </a>

            <div class="menu-group">

                <a href="{{ route('inventori.index') }}"
                   class="menu-item menu-toggle menu-parent-active">
                    <i data-lucide="package"></i>
                    <span>Inventori & Stok</span>
                    <i data-lucide="chevron-up" class="arrow"></i>
                </a>

                <div class="submenu open">

                    <a href="{{ route('masterharga.index') }}">
                        Master Harga
                    </a>

                    <a href="{{ route('supplier.index') }}">
                        Supplier
                    </a>

                    <a href="{{ route('pembelianstok.index') }}"
                       class="active">
                        Pembelian & Stok
                    </a>

                    <a href="#">Stok Gudang</a>
                    <a href="#">Mutasi Stok</a>
                    <a href="#">Stok Opname</a>

                </div>
            </div>

            <a href="#" class="menu-item">
                <i data-lucide="wallet"></i>
                <span>Operasional & Finansial</span>
            </a>

            <a href="#" class="menu-item">
                <i data-lucide="file-text"></i>
                <span>Laporan</span>
            </a>

            <a href="#" class="menu-item">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            <div class="orange-line"></div>

            <p>DHD Interior<br>Management System</p>

            <button type="button" class="theme-button"
                    onclick="changeTheme()">
                <i data-lucide="palette"></i>
                <span>Change Theme</span>
            </button>

            <form method="POST"
                  action="{{ Route::has('logout') ? route('logout') : '#' }}"
                  class="logout-form">
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

        {{-- TOPBAR --}}
        <header class="topbar">

            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text"
                       placeholder="Cari proyek, barang, supplier...">
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
                    <div class="admin-avatar">ST</div>
                    <span>Steven</span>
                    <i data-lucide="chevron-down"></i>
                </div>

            </div>
        </header>


        {{-- CONTENT --}}
        <section class="content">

            <div class="inventory-page">

                {{-- HEADER PAGE --}}
                <div class="inventory-header">

                    <div class="inventory-title">

                        <div class="breadcrumb">
                            <a href="{{ route('pembelianstok.index') }}">
                                Pembelian & Stok
                            </a>

                            <i data-lucide="chevron-right"></i>

                            <span>Edit Pembelian</span>
                        </div>

                        <h1>Edit Pembelian Stok</h1>

                        <p>
                            Perbarui informasi transaksi pembelian dan
                            detail barang yang masuk ke gudang.
                        </p>

                    </div>

                    <div class="header-action">

                        <a href="{{ route('pembelianstok.index') }}"
                           class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </div>


                {{-- FORM EDIT PEMBELIAN --}}
                <form action="{{ route('pembelianstok.update', $purchase->id) }}"
                      method="POST"
                      class="form-card">

                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI TRANSAKSI --}}
                        <div class="form-section">

                            <h3 class="section-title">
                                <i data-lucide="receipt"></i>
                                Informasi Transaksi Pembelian
                            </h3>

                            <div class="form-grid">

                                {{-- Tanggal Pembelian --}}
                                <div class="form-group">

                                    <label for="purchase_date">
                                        Tanggal Pembelian
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="date"
                                        id="purchase_date"
                                        name="purchase_date"
                                        value="{{ $purchaseDate }}"
                                        required
                                    >

                                    @error('purchase_date')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror

                                </div>


                                {{-- Nomor Invoice --}}
                                <div class="form-group">

                                    <label for="invoice_number">
                                        Nomor Invoice / Referensi
                                    </label>

                                    <input
                                        type="text"
                                        id="invoice_number"
                                        name="invoice_number"
                                        value="{{ $invoiceNumber }}"
                                        placeholder="Contoh: INV-001"
                                    >

                                    @error('invoice_number')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror

                                </div>


                                {{-- Supplier --}}
                                <div class="form-group col-span-2">

                                    <label for="supplier_id">
                                        Supplier / Asal Pembelian
                                        <span class="required">*</span>
                                    </label>

                                    <select
                                        name="supplier_id"
                                        id="supplier_id"
                                        required
                                    >
                                        <option value="">
                                            -- Pilih Supplier --
                                        </option>

                                        @foreach($suppliers as $supplier)

                                            <option
                                                value="{{ $supplier->id }}"
                                                {{ (string) $supplierId === (string) $supplier->id ? 'selected' : '' }}
                                            >
                                                {{ $supplier->name }}
                                            </option>

                                        @endforeach
                                    </select>

                                    @error('supplier_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>
                        </div>


                        {{-- SEKSI 2: DETAIL BARANG --}}
                        <div class="form-section">

                            <h3 class="section-title">
                                <i data-lucide="package"></i>
                                Detail Barang yang Dibeli
                            </h3>

                            <p style="margin-bottom: 20px; color: #777;">
                                Pilih harga dari master harga atau gunakan
                                input manual untuk barang yang belum terdaftar.
                            </p>

                            <div id="purchaseItems">

                                @foreach($purchaseItems as $index => $item)

                                    <div class="purchase-item-row">

                                        <div class="form-grid">

                                            {{-- Pilihan Sumber Harga --}}
                                            <div class="form-group col-span-2">

                                                <label>
                                                    Sumber Harga
                                                    <span class="required">*</span>
                                                </label>

                                                <select
                                                    name="items[{{ $index }}][price_mode]"
                                                    class="price-mode"
                                                    onchange="changePriceMode(this)"
                                                    required
                                                >
                                                    <option
                                                        value="master"
                                                        {{ old("items.$index.price_mode", $item->price_mode ?? 'master') === 'master' ? 'selected' : '' }}
                                                    >
                                                        Gunakan Master Harga
                                                    </option>

                                                    <option
                                                        value="manual"
                                                        {{ old("items.$index.price_mode", $item->price_mode ?? 'master') === 'manual' ? 'selected' : '' }}
                                                    >
                                                        Input Manual
                                                    </option>
                                                </select>

                                            </div>


                                            {{-- Master Harga --}}
                                            <div class="form-group master-price-group">

                                                <label>Master Harga Barang</label>

                                                <select
                                                    name="items[{{ $index }}][price_id]"
                                                    class="master-price"
                                                    onchange="selectMasterPrice(this)"
                                                >
                                                    <option value="">
                                                        -- Pilih Harga Barang --
                                                    </option>

                                                    @foreach($prices as $price)

                                                        <option
                                                            value="{{ $price->id }}"
                                                            data-material="{{ $price->material_id }}"
                                                            data-price="{{ $price->price }}"
                                                            data-name="{{ $price->material_name }}"
                                                            {{ (string) old("items.$index.price_id", $item->price_id ?? '') === (string) $price->id ? 'selected' : '' }}
                                                        >
                                                            {{ $price->material_name }}
                                                            - Rp {{ number_format($price->price, 0, ',', '.') }}
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            {{-- Material --}}
                                            <div class="form-group manual-material-group">

                                                <label>Nama Material</label>

                                                <select
                                                    name="items[{{ $index }}][material_id]"
                                                    class="material-select"
                                                >
                                                    <option value="">
                                                        -- Pilih Material --
                                                    </option>

                                                    @foreach($prices->unique('material_id') as $price)

                                                        <option
                                                            value="{{ $price->material_id }}"
                                                            {{ (string) old("items.$index.material_id", $item->material_id ?? '') === (string) $price->material_id ? 'selected' : '' }}
                                                        >
                                                            {{ $price->material_name }}
                                                            ({{ $price->material_code }})
                                                        </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            {{-- Harga Satuan --}}
                                            <div class="form-group">

                                                <label>Harga Satuan (Rp)</label>

                                                <input
                                                    type="number"
                                                    name="items[{{ $index }}][price]"
                                                    class="unit-price"
                                                    min="0"
                                                    step="0.01"
                                                    value="{{ old("items.$index.price", $item->unit_price ?? $item->price ?? '') }}"
                                                    placeholder="Masukkan harga"
                                                    required
                                                >

                                            </div>


                                            {{-- Jumlah --}}
                                            <div class="form-group">

                                                <label>Jumlah Barang</label>

                                                <input
                                                    type="number"
                                                    name="items[{{ $index }}][quantity]"
                                                    class="item-quantity"
                                                    min="0.01"
                                                    step="0.01"
                                                    value="{{ old("items.$index.quantity", $item->quantity ?? '') }}"
                                                    placeholder="Masukkan jumlah"
                                                    required
                                                >

                                            </div>

                                        </div>

                                        <div class="item-row-footer">

                                            <span class="item-subtotal">
                                                Subtotal: Rp 0
                                            </span>

                                            <button
                                                type="button"
                                                class="btn btn-outline remove-item"
                                                onclick="removeItem(this)"
                                            >
                                                <i data-lucide="trash-2"></i>
                                                Hapus Barang
                                            </button>

                                        </div>

                                        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                                    </div>

                                @endforeach

                            </div>


                            <button type="button"
                                    class="btn btn-secondary"
                                    onclick="addItem()">

                                <i data-lucide="plus"></i>
                                Tambah Barang

                            </button>

                        </div>


                        {{-- SEKSI 3: ONGKIR DAN CATATAN --}}
                        <div class="form-section">

                            <h3 class="section-title">
                                <i data-lucide="calculator"></i>
                                Biaya Pengiriman & Perhitungan
                            </h3>

                            <div class="form-grid">

                                {{-- Ongkir --}}
                                <div class="form-group">

                                    <label for="shipping_cost">
                                        Total Ongkos Kirim (Rp)
                                    </label>

                                    <input
                                        type="number"
                                        id="shipping_cost"
                                        name="shipping_cost"
                                        min="0"
                                        step="0.01"
                                        value="{{ $shippingCost }}"
                                        placeholder="Contoh: 50000"
                                    >

                                    @error('shipping_cost')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror

                                </div>


                                {{-- Catatan --}}
                                <div class="form-group col-span-2">

                                    <label for="notes">
                                        Catatan Pembelian
                                    </label>

                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="4"
                                        placeholder="Tambahkan keterangan pembelian..."
                                    >{{ $notes }}</textarea>

                                    @error('notes')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>


                            {{-- RINGKASAN HARGA --}}
                            <div class="purchase-summary">

                                <div class="summary-row">
                                    <span>Total Jumlah Barang</span>
                                    <strong id="totalQuantity">0</strong>
                                </div>

                                <div class="summary-row">
                                    <span>Total Harga Barang</span>
                                    <strong id="totalItems">Rp 0</strong>
                                </div>

                                <div class="summary-row">
                                    <span>Ongkos Kirim</span>
                                    <strong id="totalShipping">Rp 0</strong>
                                </div>

                                <hr>

                                <div class="summary-row grand-total">
                                    <span>Total Pembelian</span>
                                    <strong id="grandTotal">Rp 0</strong>
                                </div>

                                <div class="summary-row">
                                    <span>Estimasi HPP / Unit</span>
                                    <strong id="estimatedHpp">Rp 0</strong>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER BUTTON --}}
                    <div class="form-footer">

                        <a href="{{ route('pembelianstok.index') }}"
                           class="btn btn-outline">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary">

                            <i data-lucide="save"></i>
                            Perbarui Pembelian

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>


{{-- JAVASCRIPT --}}
<script>

    lucide.createIcons();

    // THEME
    function changeTheme() {
        document.body.classList.toggle('pink-theme');

        localStorage.setItem(
            'dhd-theme',
            document.body.classList.contains('pink-theme')
                ? 'pink'
                : 'default'
        );
    }

    document.addEventListener('DOMContentLoaded', function () {

        if (localStorage.getItem('dhd-theme') === 'pink') {
            document.body.classList.add('pink-theme');
        }

        document.querySelectorAll('.purchase-item-row').forEach(row => {
            initializeItem(row);
        });

        calculateTotal();
        updateDateTime();

    });


    // DATE AND TIME
    function updateDateTime() {

        const now = new Date();

        document.getElementById('currentDate').textContent =
            now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

        document.getElementById('currentTime').textContent =
            now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';

    }

    setInterval(updateDateTime, 1000);


    // FORMAT RUPIAH
    function rupiah(value) {

        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');

    }


    // INITIALIZE ITEM
    function initializeItem(row) {

        const mode = row.querySelector('.price-mode');

        const master = row.querySelector('.master-price');
        const material = row.querySelector('.material-select');

        if (mode.value === 'master' && master.value) {
            const selected = master.options[master.selectedIndex];

            if (selected) {
                material.value = selected.dataset.material || '';
            }
        }

        changePriceMode(mode, false);

        row.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        row.querySelector('.master-price').addEventListener(
            'change',
            calculateTotal
        );

    }


    // CHANGE PRICE MODE
    function changePriceMode(select, reset = true) {

        const row = select.closest('.purchase-item-row');

        const masterGroup = row.querySelector('.master-price-group');
        const manualGroup = row.querySelector('.manual-material-group');

        const master = row.querySelector('.master-price');
        const material = row.querySelector('.material-select');
        const price = row.querySelector('.unit-price');

        const isMaster = select.value === 'master';

        masterGroup.style.display = isMaster ? 'block' : 'none';
        manualGroup.style.display = isMaster ? 'none' : 'block';

        master.disabled = !isMaster;
        material.disabled = isMaster;

        material.required = !isMaster;
        master.required = isMaster;

        if (reset) {

            if (isMaster) {
                material.value = '';
                price.value = '';
            } else {
                master.value = '';
                price.value = '';
            }

        }

        if (isMaster && master.value) {
            selectMasterPrice(master);
        }

        calculateTotal();

    }


    // SELECT MASTER PRICE
    function selectMasterPrice(select) {

        const row = select.closest('.purchase-item-row');

        const option = select.options[select.selectedIndex];

        if (!option || !option.value) return;

        row.querySelector('.material-select').value =
            option.dataset.material || '';

        row.querySelector('.unit-price').value =
            option.dataset.price || '';

        calculateTotal();

    }


    // ADD NEW ITEM
    function addItem() {

        const container = document.getElementById('purchaseItems');

        const index = Date.now();

        const firstRow = container.querySelector('.purchase-item-row');

        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        newRow.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        newRow.querySelectorAll('[name]').forEach(element => {

            element.name = element.name.replace(
                /items\[[^\]]+\]/,
                `items[${index}]`
            );

        });

        container.appendChild(newRow);

        initializeItem(newRow);

        lucide.createIcons();

        calculateTotal();

    }


    // REMOVE ITEM
    function removeItem(button) {

        const rows = document.querySelectorAll('.purchase-item-row');

        if (rows.length <= 1) {
            alert('Minimal harus ada satu barang pembelian!');
            return;
        }

        button.closest('.purchase-item-row').remove();

        calculateTotal();

    }


    // CALCULATE TOTAL
    function calculateTotal() {

        let totalQuantity = 0;
        let totalItems = 0;

        document.querySelectorAll('.purchase-item-row').forEach(row => {

            const price = Number(
                row.querySelector('.unit-price').value
            ) || 0;

            const quantity = Number(
                row.querySelector('.item-quantity').value
            ) || 0;

            const subtotal = price * quantity;

            totalQuantity += quantity;
            totalItems += subtotal;

            row.querySelector('.item-subtotal').textContent =
                'Subtotal: ' + rupiah(subtotal);

        });

        const shipping = Number(
            document.getElementById('shipping_cost').value
        ) || 0;

        const grandTotal = totalItems + shipping;

        const hpp = totalQuantity > 0
            ? grandTotal / totalQuantity
            : 0;

        document.getElementById('totalQuantity').textContent =
            totalQuantity.toLocaleString('id-ID');

        document.getElementById('totalItems').textContent =
            rupiah(totalItems);

        document.getElementById('totalShipping').textContent =
            rupiah(shipping);

        document.getElementById('grandTotal').textContent =
            rupiah(grandTotal);

        document.getElementById('estimatedHpp').textContent =
            rupiah(hpp);

    }

    document.getElementById('shipping_cost').addEventListener(
        'input',
        calculateTotal
    );

</script>

</body>
</html>
