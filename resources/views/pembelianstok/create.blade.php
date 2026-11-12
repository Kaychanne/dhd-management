
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Tambah Pembelian</title>

    @vite(['resources/css/inventori.css', 'resources/css/pembelianstok.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
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
                    <a href="{{ route('masterharga.index') }}">Master Harga</a>
                    <a href="{{ route('supplier.index') }}">Supplier</a>
                    <a href="{{ route('pembelianstok.index') }}" class="active">
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

            <button type="button" class="theme-button" onclick="changeTheme()">
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

        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari material, supplier, atau transaksi...">
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

        <section class="content">
            <div class="inventory-page purchase-page">

                {{-- HEADER --}}
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ route('pembelianstok.index') }}">
                                Pembelian & Stok
                            </a>
                            <i data-lucide="chevron-right"></i>
                            <span>Tambah Pembelian</span>
                        </div>

                        <h1>Tambah Pembelian Material</h1>
                        <p>
                            Catat pembelian material dari supplier dan masukkan
                            barang yang diterima ke riwayat transaksi.
                        </p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('pembelianstok.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- PESAN VALIDASI --}}
                @if($errors->any())
                    <div class="purchase-alert error-alert">
                        <i data-lucide="alert-circle"></i>
                        <div>
                            <strong>Periksa kembali data pembelian.</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- FORM PEMBELIAN --}}
                <form action="{{ route('pembelianstok.store') }}"
                      method="POST"
                      class="form-card"
                      id="purchaseForm">
                    @csrf

                    <div class="form-body">

                        {{-- INFORMASI PEMBELIAN --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="clipboard-list"></i>
                                Informasi Pembelian
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="purchase_date">
                                        Tanggal Pembelian <span class="required">*</span>
                                    </label>
                                    <input type="date"
                                           id="purchase_date"
                                           name="purchase_date"
                                           value="{{ old('purchase_date', date('Y-m-d')) }}"
                                           required>
                                    @error('purchase_date')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="invoice_number">Nomor Invoice</label>
                                    <input type="text"
                                           id="invoice_number"
                                           name="invoice_number"
                                           value="{{ old('invoice_number') }}"
                                           placeholder="Kosongkan untuk nomor otomatis">
                                    @error('invoice_number')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="supplier_id">
                                        Supplier <span class="required">*</span>
                                    </label>
                                    <select id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="shipping_cost">Biaya Ongkir (Rp)</label>
                                    <input type="number"
                                           id="shipping_cost"
                                           name="shipping_cost"
                                           min="0"
                                           step="0.01"
                                           value="{{ old('shipping_cost', 0) }}"
                                           placeholder="0">
                                    @error('shipping_cost')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="notes">Catatan Pembelian</label>
                                    <textarea id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Catatan tambahan pembelian...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- RINCIAN BARANG --}}
                        <div class="form-section">
                            <div class="purchase-items-heading">
                                <h3 class="section-title">
                                    <i data-lucide="package"></i>
                                    Rincian Barang
                                </h3>

                                <button type="button"
                                        class="btn btn-secondary"
                                        id="addItemButton">
                                    
                                        <a href="{{ route('pembelianstok.create') }}" class="btn btn-primary">
                                            <i data-lucide="plus"></i>
                                            Tambah Pembelian
                                        </a>

                                </button>
                            </div>

                            <div id="purchaseItems">
                                <div class="purchase-item-row">
                                    <div class="item-row-heading">
                                        <strong>Barang 1</strong>
                                        <button type="button"
                                                class="remove-item-button"
                                                disabled
                                                title="Hapus barang">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </div>

                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label>Sumber Harga <span class="required">*</span></label>
                                            <select name="items[0][source]" class="item-source" required>
                                                <option value="master">Master Harga</option>
                                                <option value="manual">Input Harga Manual</option>
                                            </select>
                                        </div>

                                        <div class="form-group master-price-field">
                                            <label>Material & Harga Acuan <span class="required">*</span></label>
                                            <select name="items[0][price_id]" class="item-price-id" required>
                                                <option value="">-- Pilih harga material --</option>
                                                @foreach($prices as $price)
                                                    <option value="{{ $price->id }}"
                                                            data-price="{{ $price->price }}">
                                                        {{ $price->material_code ?? ('MAT-' . $price->material_id) }}
                                                        - {{ $price->material_name ?? 'Material' }}
                                                        (Rp {{ number_format((float) $price->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    

                                        
                                        <div class="form-group">
                                            <label>Jumlah <span class="required">*</span></label>
                                            <input type="number"
                                                   name="items[0][quantity]"
                                                   class="item-quantity"
                                                   min="0.01"
                                                   step="0.01"
                                                   value="1"
                                                   required>
                                        </div>

                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <input type="text"
                                                   class="item-price-display"
                                                   value="Rp 0"
                                                   readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Subtotal</label>
                                            <input type="text"
                                                   class="item-subtotal-display"
                                                   value="Rp 0"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TOTAL --}}
                        <div class="purchase-total-box">
                            <div>
                                <span>Subtotal Barang</span>
                                <strong id="subtotalDisplay">Rp 0</strong>
                            </div>
                            <div>
                                <span>Biaya Ongkir</span>
                                <strong id="shippingDisplay">Rp 0</strong>
                            </div>
                            <div class="purchase-grand-total">
                                <span>Total Pembelian</span>
                                <strong id="grandTotalDisplay">Rp 0</strong>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="form-footer">
                        <a href="{{ route('pembelianstok.index') }}" class="btn btn-outline">
                            Batal
                        </a>

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

{{-- TEMPLATE BARANG TAMBAHAN --}}
<template id="purchaseItemTemplate">
    <div class="purchase-item-row">
        <div class="item-row-heading">
            <strong>Barang</strong>
            <button type="button" class="remove-item-button" title="Hapus barang">
                <i data-lucide="trash-2"></i>
            </button>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Sumber Harga <span class="required">*</span></label>
                <select data-name="source" class="item-source" required>
                    <option value="master">Master Harga</option>
                    <option value="manual">Input Harga Manual</option>
                </select>
            </div>

            <div class="form-group master-price-field">
                <label>Material & Harga Acuan <span class="required">*</span></label>
                <select data-name="price_id" class="item-price-id" required>
                    <option value="">-- Pilih harga material --</option>
                    @foreach($prices as $price)
                        <option value="{{ $price->id }}" data-price="{{ $price->price }}">
                            {{ $price->material_code ?? ('MAT-' . $price->material_id) }}
                            - {{ $price->material_name ?? 'Material' }}
                            (Rp {{ number_format((float) $price->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            
            <div class="form-group manual-material-field" hidden>
                <label>Material <span class="required">*</span></label>
                <select name="items[0][material_id]"
                        class="item-material-id"
                        disabled>
                    <option value="">-- Pilih material --</option>
                    @foreach($prices->unique('material_id') as $price)
                        <option value="{{ $price->material_id }}">
                            {{ $price->material_code }} - {{ $price->material_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group manual-price-field" hidden>
                <label>Harga Manual (Rp) <span class="required">*</span></label>
                <input type="number"
                    name="items[0][price]"
                    class="item-manual-price"
                    min="0"
                    step="0.01"
                    placeholder="Masukkan harga"
                    disabled>
            </div>


            <div class="form-group">
                <label>Jumlah <span class="required">*</span></label>
                <input type="number" data-name="quantity" class="item-quantity"
                       min="0.01" step="0.01" value="1" required>
            </div>

            <div class="form-group">
                <label>Harga Satuan</label>
                <input type="text" class="item-price-display" value="Rp 0" readonly>
            </div>

            <div class="form-group">
                <label>Subtotal</label>
                <input type="text" class="item-subtotal-display" value="Rp 0" readonly>
            </div>
        </div>
    </div>
</template>

<script>
    lucide.createIcons();

    function changeTheme() {
        document.body.classList.toggle('pink-theme');

        localStorage.setItem(
            'dhd-theme',
            document.body.classList.contains('pink-theme') ? 'pink' : 'default'
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('dhd-theme') === 'pink') {
            document.body.classList.add('pink-theme');
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
        initializePurchaseForm();
    });

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

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID', {
            maximumFractionDigits: 2
        });
    }

    function initializePurchaseForm() {
        const container = document.getElementById('purchaseItems');
        const template = document.getElementById('purchaseItemTemplate');
        const addButton = document.getElementById('addItemButton');
        const shippingInput = document.getElementById('shipping_cost');
        let nextIndex = 1;

        function updateTotals() {
            let subtotal = 0;

            container.querySelectorAll('.purchase-item-row').forEach(row => {
                const source = row.querySelector('.item-source').value;
                const quantity = Number(row.querySelector('.item-quantity').value || 0);
                let price = 0;

                if (source === 'master') {
                    const selected = row.querySelector('.item-price-id').selectedOptions[0];
                    price = Number(selected?.dataset.price || 0);
                } else {
                    price = Number(row.querySelector('.item-manual-price').value || 0);
                }

                subtotal += price * quantity;

                row.querySelector('.item-price-display').value = formatRupiah(price);
                row.querySelector('.item-subtotal-display').value =
                    formatRupiah(price * quantity);
            });

            const shipping = Number(shippingInput.value || 0);

            document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
            document.getElementById('shippingDisplay').textContent = formatRupiah(shipping);
            document.getElementById('grandTotalDisplay').textContent =
                formatRupiah(subtotal + shipping);
        }

        function setupRow(row) {
            const source = row.querySelector('.item-source');
            const priceSelect = row.querySelector('.item-price-id');
            const materialSelect = row.querySelector('.item-material-id');
            const manualPrice = row.querySelector('.item-manual-price');

            function updateSource() {
                const master = source.value === 'master';

                row.querySelector('.master-price-field').hidden = !master;
                row.querySelector('.manual-material-field').hidden = master;
                row.querySelector('.manual-price-field').hidden = master;

                priceSelect.disabled = !master;
                priceSelect.required = master;

                materialSelect.disabled = master;
                materialSelect.required = !master;

                manualPrice.disabled = master;
                manualPrice.required = !master;

                if (master) {
                    materialSelect.value = '';
                    manualPrice.value = '';
                } else {
                    priceSelect.value = '';
                }

                updateTotals();
            }

            source.addEventListener('change', updateSource);
            priceSelect.addEventListener('change', updateTotals);
            materialSelect.addEventListener('change', updateTotals);
            manualPrice.addEventListener('input', updateTotals);
            row.querySelector('.item-quantity').addEventListener('input', updateTotals);

            row.querySelector('.remove-item-button').addEventListener('click', function () {
                row.remove();
                renumberRows();
                updateTotals();
            });

            updateSource();
        }

        function renumberRows() {
            const rows = container.querySelectorAll('.purchase-item-row');

            rows.forEach((row, index) => {
                row.querySelector('.item-row-heading strong').textContent =
                    'Barang ' + (index + 1);

                row.querySelectorAll('[data-name]').forEach(field => {
                    field.name = `items[${index}][${field.dataset.name}]`;
                });

                row.querySelector('.remove-item-button').disabled = rows.length === 1;
            });
        }

        addButton.addEventListener('click', function () {
            container.appendChild(template.content.cloneNode(true));

            const row = container.lastElementChild;
            row.querySelectorAll('[data-name]').forEach(field => {
                field.name = `items[${nextIndex}][${field.dataset.name}]`;
            });

            nextIndex++;
            setupRow(row);
            renumberRows();
            lucide.createIcons();
        });

        shippingInput.addEventListener('input', updateTotals);

        container.querySelectorAll('.purchase-item-row').forEach(setupRow);
        renumberRows();
    }
</script>

</body>
</html>
