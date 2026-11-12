<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Pembelian Stok</title>

    @vite(['resources/css/masterharga.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
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
        {{-- DASHBOARD --}}
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" 
           class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard"></i>
            <span>Dashboard</span>
        </a>

        {{-- RAP --}}
        <a href="#" class="menu-item">
            <i data-lucide="clipboard-list"></i>
            <span>RAP & Anggaran</span>
        </a>

        {{-- INVENTORI & STOK (MENU GROUP) --}}
        
<div class="menu-group">
    <a href="{{ route('inventori.index') }}"
       class="menu-item menu-toggle {{ request()->routeIs('masterharga.*', 'supplier.*', 'pembelianstok.*') ? 'menu-parent-active' : '' }}">
        <i data-lucide="package"></i>
        <span>Inventori & Stok</span>
        <i data-lucide="chevron-up" class="arrow"></i>
    </a>

    <div class="submenu open">
        <a href="{{ route('masterharga.index') }}"
           class="{{ request()->routeIs('masterharga.*') ? 'active' : '' }}">
            Master Harga
        </a>

        <a href="{{ route('supplier.index') }}"
           class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
            Supplier
        </a>

        <a href="{{ route('pembelianstok.index') }}" 
            class="{{ request()->routeIs('pembelianstok.*') ? 'active' : '' }}">
            Pembelian & Stok
        </a>
                
        <a href="{{ route('stokgudang.index') }}"
            class="{{ request()->routeIs('stokgudang.*') ? 'active' : '' }}">
            Stok Gudang
        </a>
    </div>
</div>


        {{-- OPERASIONAL --}}
        <a href="#" class="menu-item">
            <i data-lucide="wallet"></i>
            <span>Operasional & Finansial</span>
        </a>

        <a href="{{ route('laporan.index') }}" class="menu-item">
                <i data-lucide="file-text"></i>
                <span>Laporan</span>
            </a>

        {{-- PENGATURAN --}}
        <a href="#" class="menu-item">
            <i data-lucide="settings"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    {{-- SIDEBAR FOOTER --}}
    <div class="sidebar-footer">
        <div class="orange-line"></div>
        <p>
            DHD Interior<br>
            Management System
        </p>

        <div class="footer-actions">
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
    </div>
</aside>

    <main class="main-content">

        <header class="topbar">

            <div class="search-box">

                <i data-lucide="search"></i>

                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Cari material..."
                >

            </div>


            <div class="topbar-right">


                {{-- DATE --}}
                <div class="date-info">

                    <i data-lucide="calendar-days"></i>

                    <div>

                        <strong id="currentDate"></strong>

                        <span id="currentTime"></span>

                    </div>

                </div>


                {{-- NOTIFICATION --}}
                <button type="button"
                        class="notification-button">

                    <i data-lucide="bell"></i>

                    <span class="notification-dot"></span>

                </button>


                {{-- ADMIN --}}
                <div class="admin-profile">

                    <div class="admin-avatar">
                        ST
                    </div>

                    <span>Steven</span>

                    <i data-lucide="chevron-down"></i>

                </div>

            </div>

        </header>
        <section class="content">
            <div class="master-page purchase-page">
                <div class="master-header">
                    <div class="master-title">
                        <h1><i data-lucide="shopping-cart"></i> Pembelian &amp; Stok</h1>
                        <p>Catat barang masuk, kelola pembelian dari supplier, dan hitung harga pokok barang secara otomatis.</p>
                    </div>
                    <div class="master-header-actions">
                        <button type="button" class="btn btn-primary" onclick="showPurchaseTab('form')">
                            <a href="{{ route('pembelianstok.create') }}" class="btn-primary">
                                <i class="fas fa-plus"></i>
                                Tambah Pembelian
                            </a>
                        </button>
                    </div>
                </div>

                <div class="master-stats">
                    <div class="master-stat-card">
                        <div class="master-stat-icon purple"><i data-lucide="package-plus"></i></div>
                        <div><span>Total Barang Masuk</span><strong>{{ number_format($totalIncoming ?? 0, 0, ',', '.') }}</strong><small>Unit tercatat</small></div>
                    </div>
                    <div class="master-stat-card">
                        <div class="master-stat-icon green"><i data-lucide="receipt"></i></div>
                        <div><span>Total Transaksi</span><strong>{{ number_format($totalPurchases ?? 0, 0, ',', '.') }}</strong><small>Transaksi pembelian</small></div>
                    </div>
                    <div class="master-stat-card">
                        <div class="master-stat-icon purple"><i data-lucide="truck"></i></div>
                        <div><span>Total Ongkir</span><strong>Rp {{ number_format($totalShipping ?? 0, 0, ',', '.') }}</strong><small>Biaya distribusi</small></div>
                    </div>
                </div>


                <section class="purchase-panel" id="purchaseFormPanel" role="tabpanel" aria-labelledby="tabForm" hidden>
                <form method="POST" action="{{ Route::has('pembelian.store') ? route('pembelian.store') : '#' }}" id="purchaseForm">
                    @csrf
                    <div class="table-card purchase-form-card">
                        <div class="table-header-action">
                            <div class="table-title"><i data-lucide="file-plus-2"></i><h2>Informasi Pembelian</h2></div>
                        </div>
                        <div class="purchase-fields">
                            <div class="form-group">
                                <label for="purchase_date">Tanggal Pembelian</label>
                                <input id="purchase_date" type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="invoice_number">No. Nota / Invoice</label>
                                <input id="invoice_number" type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Contoh: INV-001">
                            </div>
                            <div class="form-group">
                                <label for="supplier_id">Supplier / Toko Asal</label>
                                <select id="supplier_id" name="supplier_id">
                                    <option value="">Pilih supplier atau toko</option>
                                    @foreach($suppliers ?? [] as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="shipping_cost">Total Ongkos Kirim (Rp)</label>
                                <input id="shipping_cost" type="number" name="shipping_cost" min="0" step="1" value="{{ old('shipping_cost', 0) }}" placeholder="0" oninput="calculatePurchaseTotals()">
                            </div>
                            <div class="form-group purchase-notes">
                                <label for="notes">Catatan</label>
                                <input id="notes" type="text" name="notes" value="{{ old('notes') }}" placeholder="Catatan pembelian (opsional)">
                            </div>
                        </div>
                    </div>

                    <div class="table-card purchase-items-card">
                        <div class="table-header-action">
                            <div class="table-title"><i data-lucide="list-checks"></i><h2>Barang Masuk</h2></div>
                            <div class="purchase-source-note">Pilih dari master harga atau gunakan input manual.</div>
                        </div>
                        <div class="table-responsive">
                            <table class="data-table master-data-table purchase-table" id="purchaseTable">
                                <thead><tr>
                                    <th>No.</th><th>Sumber Data</th><th>Material / Barang</th><th>Harga Barang (Rp)</th><th>Jumlah</th><th>Satuan</th><th>Subtotal (Rp)</th><th>Harga Pokok / Unit (Rp)</th><th>Aksi</th>
                                </tr></thead>
                                <tbody id="purchaseItems">
                                    <tr class="purchase-row">
                                        <td class="row-number">1</td>
                                        <td><select class="source-select" name="items[0][source]" onchange="toggleItemSource(this)" aria-label="Sumber data"><option value="master">Master Harga</option><option value="manual">Input Manual</option></select>
                                            <select class="master-material" name="items[0][price_id]" onchange="fillMasterPrice(this)"><option value="">Pilih material</option>@foreach($prices ?? [] as $price)<option value="{{ $price->id }}" data-name="{{ $price->material?->name ?? '' }}" data-price="{{ $price->price ?? 0 }}" data-unit="{{ $price->material?->unit ?? '' }}">{{ $price->material?->code ?? 'MAT-' . str_pad($price->material_id ?? 0, 3, '0', STR_PAD_LEFT) }} - {{ $price->material?->name ?? 'Material' }} (Rp {{ number_format($price->price ?? 0, 0, ',', '.') }})</option>@endforeach</select>
                                        </td>
                                        <td><input class="manual-name" type="text" name="items[0][material_name]" placeholder="Nama barang" disabled><input class="manual-unit" type="hidden" name="items[0][unit]" value=""></td>
                                        <td><input class="item-price" type="number" name="items[0][price]" min="0" step="1" value="0" oninput="calculatePurchaseTotals()" required></td>
                                        <td><input class="item-quantity" type="number" name="items[0][quantity]" min="1" step="1" value="1" oninput="calculatePurchaseTotals()" required></td>
                                        <td><input class="item-unit" type="text" name="items[0][unit_display]" placeholder="Unit" value="" required></td>
                                        <td class="item-subtotal">Rp 0</td>
                                        <td class="item-cost">Rp 0</td>
                                        <td><button type="button" class="btn-action remove-row" onclick="removePurchaseRow(this)" title="Hapus baris"><i data-lucide="trash-2"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="purchase-summary">
                            <div><span>Total Unit</span><strong id="totalQuantity">0</strong></div>
                            <div><span>Total Harga Barang</span><strong id="totalGoods">Rp 0</strong></div>
                            <div><span>Total Ongkir</span><strong id="totalShippingDisplay">Rp 0</strong></div>
                            <div class="purchase-grand-total"><span>Estimasi Total Modal</span><strong id="grandTotal">Rp 0</strong></div>
                        </div>
                        <p class="purchase-formula"><i data-lucide="info"></i> <strong>Rumus harga pokok per unit:</strong> harga barang per unit + (total ongkir ÷ total unit barang). Ongkir dialokasikan merata berdasarkan jumlah unit.</p>
                        <div class="purchase-actions">
                            <button type="button" class="btn btn-outline" onclick="addPurchaseRow()"><i data-lucide="plus"></i> Tambah Barang</button>
                            <button type="submit" class="btn btn-primary"><i data-lucide="save"></i> Simpan Pembelian</button>
                        </div>
                    </div>
                </form>
                </section>

                
<section class="purchase-panel" id="purchaseHistoryPanel" role="tabpanel" aria-labelledby="tabHistory">

    <div class="table-card purchase-history-card">

        <div class="table-header-action">

            <div class="table-title">
                <i data-lucide="history"></i>
                <h2>Riwayat Barang Masuk</h2>
            </div>

            <div class="master-search">
                <i data-lucide="search"></i>
                <input
                    type="text"
                    id="purchaseSearch"
                    placeholder="Cari invoice, supplier, atau barang..."
                    oninput="searchPurchases()"
                >
            </div>

        </div>

        <div class="table-responsive">

            <table class="data-table master-data-table" id="purchaseHistoryTable">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Invoice</th>
                        <th>Supplier / Toko</th>
                        <th>Total Unit</th>
                        <th>Total Pembelian</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($purchases ?? [] as $purchase)

                        <tr>

                            <td>
                                {{ $purchase->purchase_date ?? '-' }}
                            </td>

                            <td>
                                {{ $purchase->invoice_number ?? '-' }}
                            </td>

                            <td>
                                {{ $purchase->supplier?->name ?? $purchase->supplier_name ?? '-' }}
                            </td>

                            <td>
                                {{ number_format($purchase->total_quantity ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="price-value">
                                Rp {{ number_format($purchase->total_cost ?? $purchase->total_amount ?? 0, 0, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge badge-success">
                                    Tercatat
                                </span>
                            </td>

                           {{-- ACTION DROPDOWN --}}
                            <td>
                                <div class="action-dropdown">

                                    {{-- TOMBOL TITIK TIGA --}}
                                    <button
                                        type="button"
                                        class="btn-action"
                                        onclick="toggleDropdown(event, 'purchase-{{ $purchase->id }}')"
                                    >
                                        <i data-lucide="more-horizontal"></i>
                                    </button>

                                    <div
                                        id="purchase-{{ $purchase->id }}"
                                        class="dropdown-menu-action"
                                    >

                                        {{-- EDIT --}}
                                        <button
                                            type="button"
                                            class="dropdown-item-action"
                                            onclick="editPurchase({{ $purchase->id }})"
                                        >
                                            <i data-lucide="edit-3"></i>
                                            Edit
                                        </button>

                                        {{-- HAPUS --}}
                                        <button
                                            type="button"
                                            class="dropdown-item-action delete"
                                            onclick="deletePurchase({{ $purchase->id }})"
                                        >
                                            <i data-lucide="trash-2"></i>
                                            Hapus
                                        </button>

                                        {{-- FORM DELETE TERSEMBUNYI --}}
                                        <form
                                            id="delete-purchase-form-{{ $purchase->id }}"
                                            method="POST"
                                            action="{{ route('pembelianstok.destroy', $purchase->id) }}"
                                            style="display: none;"
                                        >
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </div>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="empty-state">

                                <i data-lucide="package-open"></i>

                                <strong>
                                    Belum ada riwayat pembelian
                                </strong>

                                <span>
                                    Barang masuk yang disimpan akan muncul di bagian ini.
                                </span>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</section>


    </main>

</div>

<div class="modal-overlay"
     id="priceModal">


    <div class="master-modal">


        {{-- MODAL HEADER --}}
        <div class="modal-header">

            <div>

                <span class="modal-icon">

                    <i data-lucide="badge-dollar-sign"></i>

                </span>


                <div>

                    <h3>
                        Tambah Harga Material
                    </h3>

                    <p>
                        Tambahkan harga baru untuk material.
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closePriceModal()"
                class="modal-close">

                <i data-lucide="x"></i>

            </button>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ Route::has('masterharga.price.store') ? route('masterharga.price.store') : '#' }}">

            @csrf


            <div class="modal-body">


                {{-- MATERIAL --}}
                <form method="POST" action="{{ route('masterharga.price.store') }}">
    @csrf
    <div class="modal-body">

    <!-- PILIH MATERIAL -->
    <div class="form-group">
        <label>Material</label>
        <select name="material_id" required>
            <option value="">Pilih Material</option>
            @foreach($materials ?? [] as $material)
                <option value="{{ $material->id }}">
                    {{ $material->code ?? 'MAT-' . str_pad($material->id, 3, '0', STR_PAD_LEFT) }} - {{ $material->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- PILIH SUPPLIER -->
    <div class="form-group">
        <label>Supplier</label>
        <select name="supplier_id" required>
            <option value="">Pilih Supplier</option>
            @foreach($suppliers ?? [] as $sup)
                <option value="{{ $sup->id }}">
                    {{ $sup->name }} {{ $sup->contact_person ? '('.$sup->contact_person.')' : '' }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- HARGA & TANGGAL -->
    <div class="form-row">
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="price" min="0" required placeholder="0">
        </div>

        <div class="form-group">
            <label>Tanggal Harga</label>
            <input type="date" name="price_date" value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <!-- HARGA ACUAN -->
    <div class="form-group">
        <label>Harga Acuan (Reference)</label>
        <select name="is_reference">
            <option value="1">Ya (Aktif)</option>
            <option value="0">Tidak</option>
        </select>
    </div>

    <!-- CATATAN -->
    <div class="form-group">
        <label>Catatan / Notes</label>
        <input type="text" name="notes" placeholder="Catatan tambahan...">
    </div>

</div>

    <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closePriceModal()">Batal</button>
        <button type="submit" class="btn btn-primary">
            <i data-lucide="save"></i> Simpan Harga
        </button>
    </div>
</form>

    </div>

</div>

<div class="modal-overlay"
     id="detailModal">


    <div class="master-modal">


        <div class="modal-header">

            <div>

                <span class="modal-icon">

                    <i data-lucide="eye"></i>

                </span>


                <div>

                    <h3>
                        Detail Harga
                    </h3>

                    <p>
                        Informasi harga material.
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeDetailModal()"
                class="modal-close">

                <i data-lucide="x"></i>

            </button>

        </div>


        <div class="modal-body">

            <div class="detail-row">

                <span>
                    Kode Material
                </span>

                <strong id="detailCode">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Material
                </span>

                <strong id="detailMaterial">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Kategori
                </span>

                <strong id="detailCategory">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Harga
                </span>

                <strong id="detailPrice">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Satuan
                </span>

                <strong id="detailUnit">
                    -
                </strong>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-outline"
                onclick="closeDetailModal()">

                Tutup

            </button>

        </div>

    </div>

</div>

<script>

    lucide.createIcons();

    function openPriceModal()
    {
        const modal =
            document.getElementById('priceModal');

        if(modal)
        {
            modal.classList.add('show');
        }
    }


    function closePriceModal()
    {
        const modal =
            document.getElementById('priceModal');

        if(modal)
        {
            modal.classList.remove('show');
        }
    }


    function showPriceDetail(
        material,
        code,
        category,
        price,
        unit
    )
    {
        document.getElementById('detailMaterial')
            .textContent = material;

        document.getElementById('detailCode')
            .textContent = code;

        document.getElementById('detailCategory')
            .textContent = category;

        document.getElementById('detailPrice')
            .textContent =
                'Rp ' +
                Number(price).toLocaleString('id-ID');

        document.getElementById('detailUnit')
            .textContent = unit;


        document
            .getElementById('detailModal')
            .classList.add('show');
    }


    function closeDetailModal()
    {
        document
            .getElementById('detailModal')
            .classList.remove('show');
    }

    function searchHarga()
    {
        const input =
            document.getElementById('hargaSearch');

        const filter =
            input.value.toLowerCase();

        const table =
            document.getElementById('hargaTable');

        const rows =
            table.querySelectorAll('tbody tr');


        rows.forEach(row =>
        {
            const text =
                row.textContent.toLowerCase();

            row.style.display =
                text.includes(filter)
                    ? ''
                    : 'none';
        });
    }

    const globalSearch =
        document.getElementById('globalSearch');


    if(globalSearch)
    {
        globalSearch.addEventListener(
            'keyup',
            function()
            {
                const filter =
                    this.value.toLowerCase();

                document
                    .querySelectorAll('#hargaTable tbody tr')
                    .forEach(row =>
                    {
                        row.style.display =
                            row.textContent
                                .toLowerCase()
                                .includes(filter)
                                    ? ''
                                    : 'none';
                    });
            }
        );
    }


function toggleDropdown(event, id) {
    event.stopPropagation();

    document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
        if (menu.id !== id) {
            menu.classList.remove('show');
        }
    });

    document.getElementById(id)?.classList.toggle('show');
}

window.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
        menu.classList.remove('show');
    });
});


function editPurchase(id) {
    window.location.href = `/pembelian-stok/${id}/edit`;
}


function deletePurchase(id) {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi pembelian ini?')) {
        document.getElementById('delete-purchase-form-' + id).submit();
    }
}


    function toggleDropdown(event, id)
    {
        event.stopPropagation();


        document
            .querySelectorAll('.dropdown-menu-action')
            .forEach(menu =>
            {
                if(menu.id !== id)
                {
                    menu.classList.remove('show');
                }
            });


        const menu =
            document.getElementById(id);


        if(menu)
        {
            menu.classList.toggle('show');
        }
    }


    window.addEventListener(
        'click',
        function()
        {
            document
                .querySelectorAll('.dropdown-menu-action')
                .forEach(menu =>
                {
                    menu.classList.remove('show');
                });
        }
    );



    function deletePrice(id)
    {
        if(!confirm(
            'Apakah kamu yakin ingin menghapus data harga ini?'
        ))
        {
            return;
        }

        alert(
            'Data ID ' +
            id +
            ' siap dihapus setelah route delete dibuat.'
        );
    }

    function editPrice(id)
    {

        const editUrl =
            @json(Route::has('masterharga.price.edit')
                ? route('masterharga.price.edit', ':id')
                : '');

        if(editUrl)
        {
            window.location.href =
                editUrl.replace(':id', id);

            return;
        }


        alert(
            'Route edit harga belum dibuat.'
        );
    }

    const checkAll =
        document.getElementById('checkAll');


    if(checkAll)
    {
        checkAll.addEventListener(
            'change',
            function()
            {
                document
                    .querySelectorAll('.row-check')
                    .forEach(
                        checkbox =>
                        checkbox.checked =
                            this.checked
                    );
            }
        );
    }

    function changeTheme()
    {
        document.body.classList.toggle(
            'pink-theme'
        );


        localStorage.setItem(
            'dhd-theme',

            document.body.classList.contains(
                'pink-theme'
            )
                ? 'pink'
                : 'default'
        );
    }


    document.addEventListener(
        'DOMContentLoaded',
        function()
        {
            if(
                localStorage.getItem('dhd-theme')
                ===
                'pink'
            )
            {
                document.body.classList.add(
                    'pink-theme'
                );
            }
        }
    );

    function updateDateTime()
    {
        const now =
            new Date();


        const dateElement =
            document.getElementById(
                'currentDate'
            );

        const timeElement =
            document.getElementById(
                'currentTime'
            );


        if(dateElement)
        {
            dateElement.textContent =
                now.toLocaleDateString(
                    'id-ID',
                    {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }
                );
        }


        if(timeElement)
        {
            timeElement.textContent =
                now.toLocaleTimeString(
                    'id-ID',
                    {
                        hour: '2-digit',
                        minute: '2-digit'
                    }
                ) + ' WIB';
        }
    }


    updateDateTime();

    setInterval(
        updateDateTime,
        1000
    );


    document
        .querySelectorAll('.modal-overlay')
        .forEach(modal =>
        {
            modal.addEventListener(
                'click',
                function(event)
                {
                    if(
                        event.target === modal
                    )
                    {
                        modal.classList.remove(
                            'show'
                        );
                    }
                }
            );
        });

</script>


<script>
(function(){
    const tbody = document.getElementById('purchaseItems');
    let rowIndex = tbody ? tbody.querySelectorAll('.purchase-row').length : 0;
    const rupiah = value => 'Rp ' + Math.round(Number(value)||0).toLocaleString('id-ID');
    window.addPurchaseRow = function(){
        if(!tbody) return;
        const first = tbody.querySelector('.purchase-row');
        const row = first.cloneNode(true);
        row.querySelectorAll('input').forEach(input=>{input.value = input.classList.contains('item-quantity') ? 1 : (input.classList.contains('item-price') ? 0 : '');});
        row.querySelectorAll('select').forEach(select=>select.selectedIndex=0);
        row.querySelector('.manual-name').disabled = true;
        row.querySelector('.master-material').disabled = false;
        row.querySelector('.item-unit').value='';
        row.querySelector('.item-subtotal').textContent='Rp 0';
        row.querySelector('.item-cost').textContent='Rp 0';
        row.querySelectorAll('[name]').forEach(el=>el.name=el.name.replace(/items\[\d+\]/,'items['+rowIndex+']'));
        rowIndex++;
        tbody.appendChild(row);
        renumberRows(); calculatePurchaseTotals(); if(window.lucide) lucide.createIcons();
    };
    window.removePurchaseRow = function(button){
        if(!tbody) return;
        const rows=tbody.querySelectorAll('.purchase-row');
        if(rows.length===1){
            const row=rows[0]; row.querySelectorAll('input').forEach(input=>input.value=input.classList.contains('item-quantity')?1:(input.classList.contains('item-price')?0:''));
            row.querySelectorAll('select').forEach(select=>select.selectedIndex=0);
            row.querySelector('.manual-name').disabled=true; row.querySelector('.item-unit').value='';
        }else button.closest('tr').remove();
        renumberRows(); calculatePurchaseTotals();
    };
    window.toggleItemSource = function(select){
        const row=select.closest('tr'); const manual=select.value==='manual';
        row.querySelector('.master-material').disabled=manual;
        row.querySelector('.manual-name').disabled=!manual;
        if(manual){row.querySelector('.master-material').value='';row.querySelector('.item-price').value=0;row.querySelector('.item-unit').value='';}
        else {row.querySelector('.manual-name').value='';}
        calculatePurchaseTotals();
    };
    window.fillMasterPrice = function(select){
        const row=select.closest('tr'); const option=select.options[select.selectedIndex];
        if(option && option.value){row.querySelector('.item-price').value=option.dataset.price||0;row.querySelector('.item-unit').value=option.dataset.unit||'';row.querySelector('.manual-unit').value=option.dataset.unit||'';}
        calculatePurchaseTotals();
    };
    function renumberRows(){tbody.querySelectorAll('.purchase-row').forEach((row,i)=>row.querySelector('.row-number').textContent=i+1);}
    window.calculatePurchaseTotals=function(){
        if(!tbody)return;
        const rows=[...tbody.querySelectorAll('.purchase-row')];
        const totalUnits=rows.reduce((sum,row)=>sum+Math.max(0,Number(row.querySelector('.item-quantity').value)||0),0);
        const goods=rows.reduce((sum,row)=>sum+(Math.max(0,Number(row.querySelector('.item-price').value)||0)*Math.max(0,Number(row.querySelector('.item-quantity').value)||0)),0);
        const shipping=Math.max(0,Number(document.getElementById('shipping_cost').value)||0);
        rows.forEach(row=>{
            const price=Math.max(0,Number(row.querySelector('.item-price').value)||0);
            const qty=Math.max(0,Number(row.querySelector('.item-quantity').value)||0);
            row.querySelector('.item-subtotal').textContent=rupiah(price*qty);
            row.querySelector('.item-cost').textContent=rupiah(price+(totalUnits?shipping/totalUnits:0));
        });
        document.getElementById('totalQuantity').textContent=totalUnits.toLocaleString('id-ID');
        document.getElementById('totalGoods').textContent=rupiah(goods);
        document.getElementById('totalShippingDisplay').textContent=rupiah(shipping);
        document.getElementById('grandTotal').textContent=rupiah(goods+shipping);
    };
    window.searchPurchases=function(){
        const filter=(document.getElementById('purchaseSearch').value||'').toLowerCase();
        document.querySelectorAll('#purchaseHistoryTable tbody tr').forEach(row=>row.style.display=row.textContent.toLowerCase().includes(filter)?'':'none');
    };
    const globalSearch=document.getElementById('globalSearch');
    if(globalSearch)globalSearch.addEventListener('input',function(){const f=this.value.toLowerCase();document.querySelectorAll('#purchaseHistoryTable tbody tr').forEach(row=>row.style.display=row.textContent.toLowerCase().includes(f)?'':'none');});
    calculatePurchaseTotals();
})();
</script>


<script>
    window.showPurchaseTab = function(tab) {
        const showForm = tab === 'form';
        const formPanel = document.getElementById('purchaseFormPanel');
        const historyPanel = document.getElementById('purchaseHistoryPanel');
        const tabForm = document.getElementById('tabForm');
        const tabHistory = document.getElementById('tabHistory');

        if (!formPanel || !historyPanel || !tabForm || !tabHistory) return;

        formPanel.hidden = !showForm;
        historyPanel.hidden = showForm;

        tabForm.classList.toggle('active', showForm);
        tabHistory.classList.toggle('active', !showForm);
        tabForm.setAttribute('aria-selected', String(showForm));
        tabHistory.setAttribute('aria-selected', String(!showForm));

        if (window.lucide) lucide.createIcons();
        if (showForm) {
            const firstField = document.getElementById('purchase_date');
            if (firstField) firstField.focus({ preventScroll: true });
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        window.showPurchaseTab(hasErrors ? 'form' : 'history');
    });
</script>

</body>
</html>