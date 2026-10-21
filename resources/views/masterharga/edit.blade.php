<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Edit Master Harga</title>

    @vite(['resources/css/inventori.css'])

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
            <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="menu-item">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" class="menu-item">
                <i data-lucide="clipboard-list"></i>
                <span>RAP & Anggaran</span>
            </a>
            <div class="menu-group">
                <a href="{{ route('inventori.index') }}" class="menu-item menu-toggle {{ request()->routeIs('masterharga.*', 'supplier.*') ? 'menu-parent-active' : '' }}">
                    <i data-lucide="package"></i>
                    <span>Inventori & Stok</span>
                    <i data-lucide="chevron-up" class="arrow"></i>
                </a>

                <div class="submenu open">
                    <a href="{{ route('masterharga.index') }}" class="{{ request()->routeIs('masterharga.*') ? 'active' : '' }}">
                        Master Harga
                    </a>
                    <a href="{{ route('supplier.index') }}" class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        Supplier
                    </a>
                    <a href="#">Pembelian & Stok</a>
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
                <input type="text" placeholder="Cari proyek, barang, supplier...">
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
            <div class="inventory-page">

                {{-- HEADER PAGE --}}
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ route('masterharga.index') }}">Master Harga</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Edit Harga Material</span>
                        </div>
                        <h1>Edit Harga Material: {{ $price->material?->name ?? '-' }}</h1>
                        <p>Perbarui informasi penawaran harga dari supplier untuk material ini.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('masterharga.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- FORM EDIT MASTER HARGA --}}
                <form action="{{ route('masterharga.price.update', $price->id) }}" method="POST" class="form-card">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI RELASI --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="package"></i>
                                Informasi Material & Supplier
                            </h3>

                            <div class="form-grid">

                                {{-- Material ID --}}
                                <div class="form-group">
                                    <label for="material_id">Pilih Material <span class="required">*</span></label>
                                    <select id="material_id" name="material_id" required>
                                        <option value="">-- Pilih Material --</option>
                                        @foreach($materials ?? [] as $material)
                                            <option value="{{ $material->id }}" {{ old('material_id', $price->material_id) == $material->id ? 'selected' : '' }}>
                                                {{ $material->code ?? 'MAT-' . str_pad($material->id, 3, '0', STR_PAD_LEFT) }} - {{ $material->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Supplier ID --}}
                                <div class="form-group">
                                    <label for="supplier_id">Pilih Supplier <span class="required">*</span></label>
                                    <select id="supplier_id" name="supplier_id" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach($suppliers ?? [] as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $price->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} {{ $supplier->contact_person ? '('.$supplier->contact_person.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 2: HARGA & TANGGAL --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="badge-dollar-sign"></i>
                                Rincian Harga & Masa Berlaku
                            </h3>

                            <div class="form-grid">

                                {{-- Price --}}
                                <div class="form-group">
                                    <label for="price">Harga Material (Rp) <span class="required">*</span></label>
                                    <input 
                                        type="number" 
                                        step="0.01"
                                        min="0"
                                        id="price" 
                                        name="price" 
                                        value="{{ old('price', $price->price) }}" 
                                        placeholder="Contoh: 180000" 
                                        required
                                    >
                                    @error('price')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Price Date --}}
                                <div class="form-group">
                                    <label for="price_date">Tanggal Penawaran / Harga <span class="required">*</span></label>
                                    <input 
                                        type="date" 
                                        id="price_date" 
                                        name="price_date" 
                                        value="{{ old('price_date', \Carbon\Carbon::parse($price->price_date)->format('Y-m-d')) }}" 
                                        required
                                    >
                                    @error('price_date')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Is Reference --}}
                                <div class="form-group col-span-2">
                                    <label for="is_reference">Status Harga Acuan (Reference) <span class="required">*</span></label>
                                    <select id="is_reference" name="is_reference" required>
                                        <option value="1" {{ old('is_reference', $price->is_reference) == 1 ? 'selected' : '' }}>Ya (Jadikan Harga Utama/Acuan)</option>
                                        <option value="0" {{ old('is_reference', $price->is_reference) == 0 ? 'selected' : '' }}>Tidak (Hanya Riwayat/Opsi Opsional)</option>
                                    </select>
                                    @error('is_reference')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 3: CATATAN TAMBAHAN --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="file-text"></i>
                                Catatan & Keterangan
                            </h3>

                            <div class="form-grid">

                                {{-- Notes --}}
                                <div class="form-group col-span-2">
                                    <label for="notes">Catatan Tambahan (Notes)</label>
                                    <textarea 
                                        id="notes" 
                                        name="notes" 
                                        rows="3" 
                                        placeholder="Tuliskan syarat pembelian, diskon volume, atau keterangan tambahan dari supplier..."
                                    >{{ old('notes', $price->notes) }}</textarea>
                                    @error('notes')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ route('masterharga.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Perbarui Harga Material
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons();

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