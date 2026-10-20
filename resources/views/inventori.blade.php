<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Inventori & Stok</title>

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
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>

            <a href="#" class="menu-item">
                <i data-lucide="clipboard-list"></i>
                <span>RAP & Anggaran</span>
            </a>

            <div class="menu-group">
                <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}" 
                   class="menu-item {{ request()->routeIs('inventori.*') || request()->routeIs('materials.*') ? 'active' : '' }}">
                    <i data-lucide="package"></i>
                    <span>Inventori & Stok</span>
                    <i data-lucide="chevron-up" class="arrow"></i>
                </a>

                <div class="submenu open">
                    <a href="{{ route('masterharga.index') }}"
                    class="{{ request()->routeIs('masterharga.*') ? 'active' : '' }}">
                        Master Harga
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

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari proyek, barang, supplier atau transaksi...">
            </div>

            <div class="topbar-right">
                <div class="date-info">
                    <i data-lucide="calendar-days"></i>
                    <div>
                        <strong id="currentDate">Thursday, 08 October 2026</strong>
                        <span id="currentTime">13:06 WIB</span>
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


        {{-- INVENTORY SECTION --}}
        <section class="content">
            <div class="inventory-page">

                {{-- HEADER INVENTORY --}}
                <div class="inventory-header">
                    <div class="inventory-title">
                        <h1>
                            <i data-lucide="package"></i> Inventori & Stok
                        </h1>
                        <p>Kelola seluruh data material, stok, supplier, dan pergerakan barang.</p>
                    </div>

                    {{-- FILTER BAR ATAS --}}
                    <div class="inventory-filters">
                        <div class="filter-group">
                            <div class="select-box">
                                <i data-lucide="map-pin"></i>
                                <select name="gudang_id">
                                    <option value="">Semua Gudang</option>
                                    @foreach($gudangs ?? [] as $gudang)
                                        <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="select-box">
                                <i data-lucide="folder"></i>
                                <select name="proyek_id">
                                    <option value="">Semua Proyek</option>
                                    @foreach($proyeks ?? [] as $proyek)
                                        <option value="{{ $proyek->id }}">{{ $proyek->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="select-box">
                                {{-- MENGGUNAAN ->unique('name') AGAR TIDAK DOUBLE --}}
                                <select name="category_id">
                                    <option value="">Semua Kategori</option>
                                    @foreach(collect($categories ?? [])->unique('name') as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="select-box">
                                <i data-lucide="calendar"></i>
                                <input type="month" value="{{ request('periode', now()->format('Y-m')) }}">
                            </div>
                        </div>
                    </div>
                </div>


                {{-- STATISTIC CARDS --}}
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon purple"><i data-lucide="image"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Total Material</span>
                            <h3 class="stat-value">{{ number_format(count($materials ?? []), 0, ',', '.') }}</h3>
                            <span class="stat-trend positive"><i data-lucide="arrow-up-right"></i> 12% dari bulan lalu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon blue"><i data-lucide="archive"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Total Stok</span>
                            <h3 class="stat-value">{{ number_format(collect($materials ?? [])->sum('stok'), 0, ',', '.') }}</h3>
                            <span class="stat-trend positive"><i data-lucide="arrow-up-right"></i> 8% dari bulan lalu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green"><i data-lucide="box"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Nilai Persediaan</span>
                            <h3 class="stat-value">Rp {{ number_format($nilaiPersediaan ?? 485250000, 0, ',', '.') }}</h3>
                            <span class="stat-trend positive"><i data-lucide="arrow-up-right"></i> 6% dari bulan lalu</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon warning"><i data-lucide="alert-triangle"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Stok Menipis</span>
                            <h3 class="stat-value">{{ number_format($stokMenipisCount ?? 0, 0, ',', '.') }}</h3>
                            <span class="stat-subtext warning">Perlu perhatian</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon danger"><i data-lucide="package-x"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Stok Habis</span>
                            <h3 class="stat-value">{{ number_format($stokHabisCount ?? 0, 0, ',', '.') }}</h3>
                            <span class="stat-subtext danger">Segera restock</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon teal"><i data-lucide="arrow-down-left"></i></div>
                        <div class="stat-details">
                            <span class="stat-label">Barang Masuk (Bln Ini)</span>
                            <h3 class="stat-value">{{ number_format($barangMasukCount ?? 1240, 0, ',', '.') }}</h3>
                            <span class="stat-trend positive"><i data-lucide="arrow-up-right"></i> 15% dari bulan lalu</span>
                        </div>
                    </div>
                </div>


                {{-- TABLE CARD CONTAINER --}}
                <div class="table-card">

                    <div class="table-header-action">
                        <div class="table-title">
                            <i data-lucide="layout-grid"></i>
                            <h2>Daftar Material</h2>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ url('/materials/create') }}" class="btn btn-primary">
                                <i data-lucide="plus"></i>
                                Tambah Material
                            </a>
                            <button type="button" class="btn btn-outline">
                                <i data-lucide="download"></i>
                                Import
                            </button>
                            <a href="{{ Route::has('materials.export') ? route('materials.export') : '#' }}" class="btn btn-outline">
                                <i data-lucide="upload"></i>
                                Export
                            </a>
                        </div>
                    </div>

                    {{-- CATEGORY TABS (MENGGUNAAN UNIQUE UNTUK MENCEGAH KATEGORI DOUBLE) --}}
                    <div class="table-toolbar">
                        <div class="category-tabs">
                            <button type="button" class="tab-btn active" onclick="filterByCategory('', this)">
                                Semua
                            </button>
                            @foreach(collect($categories ?? [])->unique('name') as $category)
                                <button type="button" class="tab-btn" onclick="filterByCategory('{{ $category->id }}', this)">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                        </div>

                        <div class="search-filter-wrapper">
                            <div class="search-input">
                                <i data-lucide="search"></i>
                                <input 
                                    type="text" 
                                    id="searchInput" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Cari material..." 
                                    oninput="filterMaterials()"
                                >
                            </div>
                            <button type="button" class="btn-icon">
                                <i data-lucide="sliders-horizontal"></i>
                            </button>
                        </div>
                    </div>

                    {{-- DATA TABLE --}}
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Kode</th>
                                    <th>Nama Material</th>
                                    <th>Kategori</th>
                                    <th>Brand</th>
                                    <th>Warna / Spesifikasi</th>
                                    <th>Satuan <i data-lucide="arrow-down-up"></i></th>
                                    <th>Stok</th>
                                    <th>Min. Stok</th>
                                    <th>Harga Acuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials ?? [] as $item)
                                    <tr data-category="{{ $item->category_id }}">
                                        <td><input type="checkbox" name="selected_items[]" value="{{ $item->id }}"></td>
                                        <td>{{ $item->code }}</td>
                                        <td>
                                            <div class="material-name">
                                                <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/40' }}" 
                                                     alt="{{ $item->name }}" 
                                                     class="item-thumb" 
                                                     onerror="this.src='https://via.placeholder.com/40'">
                                                <span>{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>{{ $item->brand->name ?? '-' }}</td>
                                        <td>{{ $item->specification ?? '-' }}</td>
                                        <td>{{ $item->unit ?? '-' }}</td>
                                        <td>{{ $item->stok ?? 0 }}</td>
                                        <td>{{ $item->minimum_stock ?? 0 }}</td>
                                        <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @php 
                                                $stok = $item->stok ?? 0;
                                                $minStok = $item->minimum_stock ?? 0;
                                            @endphp
                                            @if($stok > $minStok)
                                                <span class="badge badge-success">Aman</span>
                                            @elseif($stok > 0 && $stok <= $minStok)
                                                <span class="badge badge-warning">Menipis</span>
                                            @else
                                                <span class="badge badge-danger">Kritis</span>
                                            @endif
                                        </td>

                                        {{-- AKSI DROPDOWN MENU (3 TITIK) --}}
                                        <td>
                                            <div class="action-dropdown">
                                                <button type="button" class="btn-action" onclick="toggleDropdown(event, 'dropdown-{{ $item->id }}')">
                                                    <i data-lucide="more-horizontal"></i>
                                                </button>
                                                
                                                <div id="dropdown-{{ $item->id }}" class="dropdown-menu-action">
                                                    {{-- OPSI EDIT / UPDATE --}}
                                                    <a href="{{ Route::has('materials.edit') ? route('materials.edit', $item->id) : url('/materials/' . $item->id . '/edit') }}" class="dropdown-item-action">
                                                        <i data-lucide="edit-3"></i> Edit
                                                    </a>

                                                    {{-- OPSI DELETE / HAPUS --}}
                                                    <form action="{{ Route::has('materials.destroy') ? route('materials.destroy', $item->id) : url('/materials/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus material ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item-action delete">
                                                            <i data-lucide="trash-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4" style="text-align: center;">Belum ada data material.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="table-pagination">
                        @if(isset($materials) && method_exists($materials, 'links'))
                            {{ $materials->links() }}
                        @else
                            <span class="pagination-info">Menampilkan 1 - {{ count($materials ?? []) }} dari {{ count($materials ?? []) }} material</span>
                            <div class="pagination-controls">
                                <button type="button" class="page-nav" disabled><i data-lucide="chevron-left"></i></button>
                                <button type="button" class="page-num active">1</button>
                                <button type="button" class="page-nav" disabled><i data-lucide="chevron-right"></i></button>
                            </div>
                        @endif
                    </div>

                </div>

            </div>
        </section>

    </main>

</div>


<script>
    lucide.createIcons();

    // ==========================================
    // DROPDOWN ACTION MENU TOGGLE
    // ==========================================
    function toggleDropdown(event, dropdownId) {
        event.stopPropagation();
        
        // Tutup semua dropdown lain yang terbuka
        const allDropdowns = document.querySelectorAll('.dropdown-menu-action');
        allDropdowns.forEach(dropdown => {
            if (dropdown.id !== dropdownId) {
                dropdown.classList.remove('show');
            }
        });

        // Buka/Tutup dropdown yang diklik
        const targetDropdown = document.getElementById(dropdownId);
        if (targetDropdown) {
            targetDropdown.classList.toggle('show');
        }
    }

    // Tutup dropdown jika mengklik di luar area dropdown
    window.addEventListener('click', function() {
        const allDropdowns = document.querySelectorAll('.dropdown-menu-action');
        allDropdowns.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    });

    // ==========================================
    // REALTIME SEARCH & CATEGORY FILTER
    // ==========================================
    let activeCategoryId = '';

    function filterMaterials() {
        const searchInput = document.getElementById('searchInput');
        const filterText = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const tableBody = document.querySelector('.data-table tbody');
        
        if (!tableBody) return;
        const rows = tableBody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            if (row.cells.length === 1) continue;

            const rowCategory = row.getAttribute('data-category') || '';
            const rowText = (row.textContent || row.innerText).toLowerCase();

            const matchesSearch = rowText.includes(filterText);
            const matchesCategory = (activeCategoryId === '' || rowCategory === activeCategoryId);

            if (matchesSearch && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function filterByCategory(categoryId, element) {
        activeCategoryId = categoryId.toString();

        const tabs = document.querySelectorAll('.category-tabs .tab-btn');
        tabs.forEach(tab => tab.classList.remove('active'));
        if (element) element.classList.add('active');

        filterMaterials();
    }

    // ==========================================
    // THEME & DATE TIME
    // ==========================================
    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
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
            dateElement.textContent = now.toLocaleDateString('en-US', {
                weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
            });
        }

        if (timeElement) {
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