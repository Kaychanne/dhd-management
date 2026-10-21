<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Tambah Supplier</title>

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
                            <a href="{{ route('supplier.index') }}">Supplier</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Tambah Supplier Baru</span>
                        </div>
                        <h1>Tambah Supplier Baru</h1>
                        <p>Isi formulir di bawah ini untuk menambahkan data vendor / supplier baru.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- FORM CREATE SUPPLIER --}}
                <form action="{{ route('supplier.store') }}" method="POST" class="form-card">
                    @csrf

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI UTAMA SUPPLIER --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="truck"></i>
                                Informasi Identitas Supplier
                            </h3>

                            <div class="form-grid">

                                {{-- Name --}}
                                <div class="form-group col-span-2">
                                    <label for="name">Nama Supplier / Perusahaan <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}" 
                                        placeholder="Contoh: PT. Kayu Jaya Abadi" 
                                        required
                                    >
                                    @error('name')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Contact Person --}}
                                <div class="form-group">
                                    <label for="contact_person">Contact Person (Sales / Penanggung Jawab)</label>
                                    <input 
                                        type="text" 
                                        id="contact_person" 
                                        name="contact_person" 
                                        value="{{ old('contact_person') }}" 
                                        placeholder="Contoh: Pak Budi / Ibu Siska"
                                    >
                                    @error('contact_person')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="form-group">
                                    <label for="phone">No. Telepon / WhatsApp</label>
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone') }}" 
                                        placeholder="Contoh: 08123456789"
                                    >
                                    @error('phone')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 2: KONTAK & ALAMAT --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="map-pin"></i>
                                Email & Alamat Operasional
                            </h3>

                            <div class="form-grid">

                                {{-- Email --}}
                                <div class="form-group col-span-2">
                                    <label for="email">Email Resmi</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email') }}" 
                                        placeholder="Contoh: sales@kayujaya.com"
                                    >
                                    @error('email')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="form-group col-span-2">
                                    <label for="address">Alamat Lengkap Kantor / Gudang Supplier</label>
                                    <textarea 
                                        id="address" 
                                        name="address" 
                                        rows="4" 
                                        placeholder="Tuliskan jalan, nomor, kecamatan, kota dan lokasi fisik supplier..."
                                    >{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ route('supplier.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Supplier
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