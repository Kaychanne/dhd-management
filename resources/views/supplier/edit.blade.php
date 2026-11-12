
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - Edit Supplier</title>

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
                   class="menu-item menu-toggle {{ request()->routeIs('supplier.*') ? 'menu-parent-active' : '' }}">
                    <i data-lucide="package"></i>
                    <span>Inventori & Stok</span>
                    <i data-lucide="chevron-up" class="arrow"></i>
                </a>

                <div class="submenu open">
                    <a href="{{ route('masterharga.index') }}">Master Harga</a>
                    <a href="{{ route('supplier.index') }}"
                       class="{{ request()->routeIs('supplier.*') ? 'active' : '' }}">
                        Supplier
                    </a>
                    <a href="{{ route('pembelianstok.index') }}">Pembelian & Stok</a>
                    <a href="{{ route('stokgudang.index') }}">Stok Gudang</a>
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

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text"
                       placeholder="Cari proyek, barang, supplier atau transaksi...">
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

                {{-- PAGE HEADER --}}
                <div class="inventory-header">
                    <div class="inventory-title">

                        <div class="breadcrumb">
                            <a href="{{ route('supplier.index') }}">Supplier</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Edit Supplier</span>
                        </div>

                        <h1>Edit Data Supplier</h1>

                        <p>
                            Perbarui informasi supplier yang telah terdaftar
                            pada sistem DHD Interior.
                        </p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('supplier.index') }}"
                           class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- FORM EDIT --}}
                <form action="{{ route('supplier.update', $supplier->id) }}"
                      method="POST"
                      class="form-card">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        {{-- SECTION 1 --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="building-2"></i>
                                Informasi Perusahaan
                            </h3>

                            <div class="form-grid">

                                <div class="form-group col-span-2">
                                    <label for="name">
                                        Nama Supplier / Perusahaan
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $supplier->name) }}"
                                        placeholder="Nama supplier"
                                        maxlength="255"
                                        required
                                    >

                                    @error('name')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SECTION 2 --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="contact"></i>
                                Informasi Kontak
                            </h3>

                            <div class="form-grid">

                                <div class="form-group">
                                    <label for="contact_person">
                                        Contact Person
                                    </label>

                                    <input
                                        type="text"
                                        id="contact_person"
                                        name="contact_person"
                                        value="{{ old('contact_person', $supplier->contact_person) }}"
                                        placeholder="Nama sales / admin"
                                        maxlength="255"
                                    >

                                    @error('contact_person')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">
                                        No. Telepon / WhatsApp
                                    </label>

                                    <input
                                        type="text"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $supplier->phone) }}"
                                        placeholder="08XXXXXXXXXX"
                                        maxlength="20"
                                        inputmode="numeric"
                                    >

                                    @error('phone')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="email">Email Supplier</label>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $supplier->email) }}"
                                        placeholder="supplier@email.com"
                                        maxlength="255"
                                    >

                                    @error('email')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SECTION 3 --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="map-pin"></i>
                                Alamat Supplier
                            </h3>

                            <div class="form-grid">

                                <div class="form-group col-span-2">
                                    <label for="address">Alamat Lengkap</label>

                                    <textarea
                                        id="address"
                                        name="address"
                                        rows="3"
                                        maxlength="255"
                                        placeholder="Masukkan alamat lengkap supplier..."
                                    >{{ old('address', $supplier->address) }}</textarea>

                                    @error('address')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="form-footer">
                        <a href="{{ route('supplier.index') }}"
                           class="btn btn-outline">
                            Batal
                        </a>

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
