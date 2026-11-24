<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Edit Pembelian</title>

    @vite(['resources/css/inventori.css'])

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .item-box {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            background: #fcfdfe;
            display: flex;
            flex-direction: column;
            gap: 14px;
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
                            <span>Edit Pembelian</span>
                        </div>
                        <h1>Edit Pembelian</h1>
                        <p>Perbarui informasi transaksi pembelian, proyek, dan rincian barang masuk.</p>
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

                @php($firstItem = $purchase->items->first())

                {{-- FORM EDIT PEMBELIAN --}}
                <form action="{{ route('pembelianstok.update', $purchase->id) }}" method="POST" class="form-card" id="purchaseEditForm">
                    @csrf
                    @method('PUT')

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
                                        value="{{ old('purchase_date', optional($purchase->purchase_date)->format('Y-m-d') ?? $purchase->purchase_date) }}"
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
                                        value="{{ old('invoice_number', $purchase->invoice_number) }}"
                                        placeholder="Nomor invoice"
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
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
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
                                        value="{{ old('project_name', $purchase->project_name) }}"
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
                                        value="{{ old('shipping_cost', $purchase->shipping_cost ?? 0) }}"
                                        oninput="calculateEditTotal()"
                                    >
                                    @error('shipping_cost')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="Tercatat" {{ old('status', $purchase->status) == 'Tercatat' ? 'selected' : '' }}>Tercatat</option>
                                        <option value="Diproses" {{ old('status', $purchase->status) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ old('status', $purchase->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
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
                                    >{{ old('notes', $purchase->notes) }}</textarea>
                                    @error('notes')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 2: RINCIAN BARANG --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="package"></i>
                                Rincian Barang
                            </h3>

                            <div class="item-box">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Sumber Harga <span class="required">*</span></label>
                                        <select name="items[0][price_mode]" id="price_mode" onchange="toggleEditSource()" required>
                                            <option value="master" {{ old('items.0.price_mode', $purchase->price_mode) === 'master' ? 'selected' : '' }}>Master Harga</option>
                                            <option value="manual" {{ old('items.0.price_mode', $purchase->price_mode) === 'manual' ? 'selected' : '' }}>Input Manual</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="editMasterField">
                                        <label>Material &amp; Harga Acuan</label>
                                        <select name="items[0][price_id]" id="price_id" onchange="selectEditPrice()">
                                            <option value="">-- Pilih Material &amp; Harga --</option>
                                            @foreach($prices ?? [] as $price)
                                                <option
                                                    value="{{ $price->id }}"
                                                    data-material-id="{{ $price->material_id }}"
                                                    data-price="{{ $price->price }}"
                                                    data-unit="{{ $price->material_unit }}"
                                                    {{ old('items.0.price_id', $purchase->price_id) == $price->id ? 'selected' : '' }}
                                                >
                                                    {{ $price->material_code ?: 'MAT-'.$price->material_id }} - {{ $price->material_name }} (Rp {{ number_format($price->price, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="editMaterialField">
                                        <label>Material <span class="required">*</span></label>
                                        <select name="items[0][material_id]" id="material_id">
                                            <option value="">-- Pilih Material --</option>
                                            @foreach($materials ?? [] as $material)
                                                <option value="{{ $material->id }}" {{ old('items.0.material_id', $firstItem->material_id ?? '') == $material->id ? 'selected' : '' }}>
                                                    {{ $material->code ?: 'MAT-'.$material->id }} - {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Harga Satuan (Rp) <span class="required">*</span></label>
                                        <input
                                            type="number"
                                            name="items[0][price]"
                                            id="item_price"
                                            min="0"
                                            step="0.01"
                                            value="{{ old('items.0.price', $purchase->price ?? ($firstItem->unit_price ?? 0)) }}"
                                            required
                                            oninput="calculateEditTotal()"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah <span class="required">*</span></label>
                                        <input
                                            type="number"
                                            name="items[0][quantity]"
                                            id="item_quantity"
                                            min="0.01"
                                            step="0.01"
                                            value="{{ old('items.0.quantity', $firstItem->quantity ?? 1) }}"
                                            required
                                            oninput="calculateEditTotal()"
                                        >
                                    </div>
                                </div>

                                <div class="item-calc-bar">
                                    <span>Subtotal Barang: <strong id="editSubtotal">Rp 0</strong></span>
                                    <span>Estimasi Total: <strong id="editGrandTotal">Rp 0</strong></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ route('pembelianstok.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons();

    const editMoney = value => 'Rp ' + (Number(value) || 0).toLocaleString('id-ID', { maximumFractionDigits: 2 });

    function toggleEditSource() {
        const master = document.getElementById('price_mode').value === 'master';
        document.getElementById('editMasterField').hidden = !master;
        document.getElementById('editMaterialField').hidden = master;
        document.getElementById('price_id').disabled = !master;
        document.getElementById('material_id').disabled = master;
        document.getElementById('price_id').required = master;
        document.getElementById('material_id').required = !master;
    }

    function selectEditPrice() {
        const option = document.getElementById('price_id').selectedOptions[0];
        if (option && option.value) {
            document.getElementById('item_price').value = option.dataset.price || 0;
        }
        calculateEditTotal();
    }

    function calculateEditTotal() {
        const price = Number(document.getElementById('item_price')?.value) || 0;
        const qty = Number(document.getElementById('item_quantity')?.value) || 0;
        const shipping = Number(document.getElementById('shipping_cost')?.value) || 0;
        const subtotal = price * qty;
        document.getElementById('editSubtotal').textContent = editMoney(subtotal);
        document.getElementById('editGrandTotal').textContent = editMoney(subtotal + shipping);
    }

    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        if (document.body.classList.contains('pink-theme')) {
            localStorage.setItem('dhd-theme', 'pink');
        } else {
            localStorage.setItem('dhd-theme', 'default');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const theme = localStorage.getItem('dhd-theme');
        if (theme === 'pink') {
            document.body.classList.add('pink-theme');
        }
        toggleEditSource();
        calculateEditTotal();
    });

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

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>

</body>
</html>