<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - Edit Barang</title>
    @vite(['resources/css/inventori.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
<div class="dashboard-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">DHD</div>
            <div class="brand-text"><strong>Interior</strong><span>Management System</span></div>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-group">
                <a href="{ route('inventori.index') }" class="menu-item menu-toggle menu-parent-active">
                    <i data-lucide="package"></i><span>Inventori &amp; Stok</span><i data-lucide="chevron-up" class="arrow"></i>
                </a>
                <div class="submenu open">
                    <a href="{ route('masterharga.index') }">Master Harga</a>
                    <a href="{ route('supplier.index') }">Supplier</a>
                    <a href="{ route('pembelianstok.index') }">Pembelian &amp; Stok</a>
                    <a href="{ route('stokgudang.index') }" class="active">Stok Gudang</a>
                </div>
            </div>
            <a href="{ Route::has('laporan.index') ? route('laporan.index') : '#' }" class="menu-item">
                <i data-lucide="file-text"></i><span>Laporan</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="orange-line"></div>
            <p>DHD Interior<br>Management System</p>
            <button type="button" class="theme-button" onclick="changeTheme()">
                <i data-lucide="palette"></i><span>Change Theme</span>
            </button>
            <form method="POST" action="{ route('logout') }" class="logout-form">
                @csrf
                <button type="submit" class="logout-button"><i data-lucide="log-out"></i><span>Logout</span></button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari proyek, barang, supplier atau transaksi...">
            </div>
            <div class="topbar-right">
                <div class="date-info">
                    <i data-lucide="calendar-days"></i>
                    <div><strong id="currentDate"></strong><span id="currentTime"></span></div>
                </div>
                <button type="button" class="notification-button"><i data-lucide="bell"></i><span class="notification-dot"></span></button>
                <div class="admin-profile">
                    <div class="admin-avatar">{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }</div>
                    <span>{ auth()->user()->name ?? 'Steven' }</span>
                    <i data-lucide="chevron-down"></i>
                </div>
            </div>
        </header>

        <section class="content">
            <div class="inventory-page">
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{ route('stokgudang.index') }">Stok Gudang</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Edit Barang</span>
                        </div>
                        <h1>Edit Data Barang</h1>
                        <p>Perbarui informasi material dan batas minimum stok gudang.</p>
                    </div>
                    <div class="header-action">
                        <a href="{ route('stokgudang.index') }" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert-box-error">{ session('error') }</div>
                @endif
                @if($errors->any())
                    <div class="alert-box-error">
                        <strong>Periksa kembali data berikut:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{ $error }</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('stokgudang.update', $material->id) }}" method="POST" class="form-card" id="stokGudangEditForm">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-section">
                            <h3 class="section-title"><i data-lucide="package"></i> Informasi Barang</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="code">Kode Barang <span class="required">*</span></label>
                                    <input type="text" id="code" name="code" value="{{ old('code', $material->code) }}" required>
                                    @error('code')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Barang <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $material->name) }}" required>
                                    @error('name')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="warehouse_category_id">Kategori Gudang</label>
                                    <select name="warehouse_category_id" id="warehouse_category_id" required>
                                        <option value="">-- Pilih Kategori Gudang --</option>

                                        @foreach ($warehouseCategories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('warehouse_category_id', $material->warehouse_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="brand_id">Brand <span class="required">*</span></label>
                                    <select id="brand_id" name="brand_id" required>
                                        <option value="">-- Pilih Brand --</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" @selected(old('brand_id', $material->brand_id) == $brand->id)>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="specification">Spesifikasi</label>
                                    <input type="text" id="specification" name="specification" value="{{ old('specification', $material->specification) }}">
                                    @error('specification')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="unit">Satuan <span class="required">*</span></label>
                                    <input type="text" id="unit" name="unit" value="{{ old('unit', $material->unit) }}" required>
                                    @error('unit')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group col-span-2">
                                    <label for="description">Deskripsi Barang</label>
                                    <textarea id="description" name="description" rows="3">{{ old('description', $material->description) }}</textarea>
                                    @error('description')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h3 class="section-title"><i data-lucide="sliders-horizontal"></i> Pengaturan Stok</h3>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="minimum_stock">Minimum Stok <span class="required">*</span></label>
                                    <input type="number" id="minimum_stock" name="minimum_stock" min="0" step="0.01" value="{{ old('minimum_stock', $material->minimum_stock ?? 5) }}" required>
                                    @error('minimum_stock')<span class="error-msg">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label>Stok Saat Ini</label>
                                    <input type="text" value="{{ number_format((float)($currentStock ?? 0), 2, ',', '.') }} {{ $material->unit }}" readonly>
                                    <small>Stok dihitung dari transaksi barang masuk dan keluar.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer">
                        <a href="{{ route('stokgudang.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary"><i data-lucide="save"></i> Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </section>
    </main>
</div>

<script>
    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
    }
    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('dhd-theme') === 'pink') document.body.classList.add('pink-theme');
        updateDateTime();
        setInterval(updateDateTime, 1000);
        if (window.lucide) lucide.createIcons();
    });
    function updateDateTime() {
        const now = new Date();
        const date = document.getElementById('currentDate');
        const time = document.getElementById('currentTime');
        if (date) date.textContent = now.toLocaleDateString('id-ID', {weekday:'long', day:'2-digit', month:'long', year:'numeric'});
        if (time) time.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) + ' WIB';
    }
</script>
</body>
</html>
