<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - Supplier Management</title>
    @vite(['resources/css/masterharga.css'])
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
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="menu-item">
                <i data-lucide="clipboard-list"></i>
                <span>RAP & Anggaran</span>
            </a>

            <div class="menu-group">
                <a href="{{ route('inventori.index') }}" 
                   class="menu-item menu-toggle {{ request()->routeIs('masterharga.*', 'supplier.*') ? 'menu-parent-active' : '' }}">
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

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" id="globalSearch" placeholder="Cari supplier...">
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
            <div class="master-page">
                {{-- PAGE HEADER --}}
                <div class="master-header">
                    <div class="master-title">
                        <h1>
                            <i data-lucide="truck"></i>
                            Daftar Supplier
                        </h1>
                        <p>Kelola data mitra vendor dan penyedia material proyek.</p>
                    </div>

                    <div class="master-header-actions">
                        <button type="button" class="btn btn-primary" onclick="openSupplierModal()">
                            <i data-lucide="plus"></i>
                            Tambah Supplier
                        </button>
                    </div>
                </div>

                {{-- STATISTIC --}}
                <div class="master-stats">
                    <div class="master-stat-card">
                        <div class="master-stat-icon purple">
                            <i data-lucide="users"></i>
                        </div>
                        <div>
                            <span>Total Supplier</span>
                            <strong>{{ number_format(count($suppliers ?? []), 0, ',', '.') }}</strong>
                            <small>Mitra terdaftar</small>
                        </div>
                    </div>
                </div>

                {{-- TABLE CARD --}}
                <div class="table-card">
                    <div class="table-header-action">
                        <div class="table-title">
                            <i data-lucide="list"></i>
                            <h2>Data Vendor / Supplier</h2>
                        </div>

                        <div class="master-search">
                            <i data-lucide="search"></i>
                            <input type="text" id="supplierSearch" placeholder="Cari nama, kontak, atau alamat..." onkeyup="searchSupplier()">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table master-data-table" id="supplierTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>Nama Supplier</th>
                                    <th>Contact Person</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Jumlah Material</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers ?? [] as $supplier)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row-check" value="{{ $supplier->id }}">
                                        </td>
                                        <td>
                                            <strong>{{ $supplier->name }}</strong>
                                        </td>
                                        <td>{{ $supplier->contact_person ?? '-' }}</td>
                                        <td>{{ $supplier->phone ?? '-' }}</td>
                                        <td>{{ $supplier->email ?? '-' }}</td>
                                        <td>{{ $supplier->address ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-success">
                                                {{ $supplier->material_prices_count ?? 0 }} Item
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-dropdown">
                                                <button type="button" class="btn-action" onclick="toggleDropdown(event, 'sup-{{ $supplier->id }}')">
                                                    <i data-lucide="more-horizontal"></i>
                                                </button>

                                                <div id="sup-{{ $supplier->id }}" class="dropdown-menu-action">
                                                    {{-- EDIT --}}
                                                    <button type="button" class="dropdown-item-action" onclick="editSupplier(
                                                        {{ $supplier->id }},
                                                        '{{ addslashes($supplier->name) }}',
                                                        '{{ addslashes($supplier->contact_person ?? '') }}',
                                                        '{{ addslashes($supplier->phone ?? '') }}',
                                                        '{{ addslashes($supplier->email ?? '') }}',
                                                        '{{ addslashes($supplier->address ?? '') }}'
                                                    )">
                                                        <i data-lucide="edit-3"></i> Edit
                                                    </button>

                                                    {{-- HAPUS --}}
                                                    <button type="button" class="dropdown-item-action delete" onclick="deleteSupplier({{ $supplier->id }})">
                                                        <i data-lucide="trash-2"></i> Hapus
                                                    </button>

                                                    {{-- FORM DELETE HIDDEN --}}
                                                    <form id="delete-sup-form-{{ $supplier->id }}" method="POST" action="{{ route('supplier.destroy', $supplier->id) }}" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                            
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="empty-state">
                                            <i data-lucide="users-round"></i>
                                            <strong>Belum ada data supplier</strong>
                                            <span>Silakan tambahkan data supplier baru.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

{{-- MODAL TAMBAH SUPPLIER --}}
<div class="modal-overlay" id="supplierModal">
    <div class="master-modal">
        <div class="modal-header">
            <div>
                <span class="modal-icon">
                    <i data-lucide="truck"></i>
                </span>
                <div>
                    <h3>Tambah Supplier Baru</h3>
                    <p>Masukkan informasi rinci supplier.</p>
                </div>
            </div>
            <button type="button" onclick="closeSupplierModal()" class="modal-close">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('supplier.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Supplier / Perusahaan</label>
                    <input type="text" name="name" required placeholder="Contoh: PT. Kayu Jaya">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Person (Sales/Admin)</label>
                        <input type="text" name="contact_person" placeholder="Nama Kontak">
                    </div>
                    <div class="form-group">
                        <label>No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" placeholder="08XXXXXXXXXX">
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="supplier@email.com">
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="address" placeholder="Jl. Raya No. XX, Kota...">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeSupplierModal()">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i> Simpan Supplier
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();

    function openSupplierModal() {
        document.getElementById('supplierModal')?.classList.add('show');
    }

    function closeSupplierModal() {
        document.getElementById('supplierModal')?.classList.remove('show');
    }

    function searchSupplier() {
        const input = document.getElementById('supplierSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('supplierTable');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    function toggleDropdown(event, id) {
        event.stopPropagation();
        document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
            if(menu.id !== id) menu.classList.remove('show');
        });
        document.getElementById(id)?.classList.toggle('show');
    }

    window.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(localStorage.getItem('dhd-theme') === 'pink') {
            document.body.classList.add('pink-theme');
        }
    });
    function deleteSupplier(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data supplier ini?')) {
        document.getElementById('delete-sup-form-' + id).submit();
    }
    }
    function updateDateTime() {
        const now = new Date();
        const dateElement = document.getElementById('currentDate');
        const timeElement = document.getElementById('currentTime');

        if(dateElement) {
            dateElement.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
            });
        }
        if(timeElement) {
            timeElement.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit'
            }) + ' WIB';
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
</body>
</html>