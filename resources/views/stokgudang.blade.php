<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - Stok Gudang</title>

    @vite(['resources/css/stokgudang.css'])
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
                <a href="{{ route('pembelianstok.index') }}">Pembelian & Stok</a>
                <a href="{{ route('stokgudang.index') }}" class="active">Stok Gudang</a>
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

        <div class="footer-actions">
            <button type="button" class="theme-button" onclick="changeTheme()">
                <i data-lucide="palette"></i>
                <span>Change Theme</span>
            </button>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
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
            <input type="text" id="globalSearch" placeholder="Cari material...">
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

        <div class="stokgudang-page">

            {{-- HEADER --}}
            <div class="master-header">

                <div class="master-title">
                    <h1>
                        <i data-lucide="warehouse"></i>
                        Stok Gudang
                    </h1>

                    <p>
                        Kelola dan pantau ketersediaan barang
                        serta persediaan di gudang.
                    </p>
                </div>

                <a href="{{ route('stokgudang.create') }}"
                   class="btn btn-primary"
                   style="text-decoration:none;">
                    <i data-lucide="plus"></i>
                    Tambah Barang
                </a>

            </div>


            {{-- NOTIFICATION --}}
            @if(session('success'))
                <div class="alert-success">
                    <i data-lucide="check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
                    <i data-lucide="alert-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <i data-lucide="alert-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif


            {{-- CATEGORY --}}
            <div class="category-section">

                <div class="category-heading">
                    <h2>Kategori Stok Gudang</h2>
                    <p>Pilih kelompok gudang untuk melihat persediaan.</p>
                </div>

                <div class="category-list">

                    <a href="{{ route('stokgudang.index', request()->except('warehouse_category_id')) }}"
                       class="category-card {{ !$selectedCategory ? 'active' : '' }}">
                        <i data-lucide="layout-grid"></i>
                        <span>Semua Barang</span>
                    </a>

                    @foreach($warehouseCategories as $category)

                        @php
                            $name = strtolower($category->name);

                            $icon = match(true) {
                                str_contains($name, 'cat') => 'paint-bucket',
                                str_contains($name, 'electrical') => 'zap',
                                str_contains($name, 'workshop') => 'hammer',
                                str_contains($name, 'plumbing') => 'wrench',
                                default => 'package'
                            };
                        @endphp

                        <a href="{{ route('stokgudang.index', array_merge(
                                request()->except('warehouse_category_id'),
                                ['warehouse_category_id' => $category->id]
                            )) }}"
                           class="category-card {{ (string)$selectedCategory === (string)$category->id ? 'active' : '' }}">

                            <i data-lucide="{{ $icon }}"></i>
                            <span>{{ $category->name }}</span>

                        </a>

                    @endforeach

                </div>
            </div>


            {{-- STATISTICS --}}
            <div class="stokgudang-stats">

                <div class="stokgudang-stat-card">
                    <div class="stokgudang-stat-icon purple">
                        <i data-lucide="package"></i>
                    </div>

                    <div>
                        <span>Total Jenis Barang</span>
                        <strong>{{ number_format($totalMaterials, 0, ',', '.') }}</strong>
                        <small>Material terdaftar</small>
                    </div>
                </div>

                <div class="stokgudang-stat-card">
                    <div class="stokgudang-stat-icon green">
                        <i data-lucide="boxes"></i>
                    </div>

                    <div>
                        <span>Total Stok Barang</span>
                        <strong>{{ number_format($totalStock, 0, ',', '.') }}</strong>
                        <small>Akumulasi seluruh stok</small>
                    </div>
                </div>

                <div class="stokgudang-stat-card">
                    <div class="stokgudang-stat-icon orange">
                        <i data-lucide="alert-triangle"></i>
                    </div>

                    <div>
                        <span>Stok Menipis / Habis</span>
                        <strong>{{ number_format($lowOrEmpty, 0, ',', '.') }}</strong>
                        <small>Perlu pengadaan ulang</small>
                    </div>
                </div>

                <div class="stokgudang-stat-card">
                    <div class="stokgudang-stat-icon blue">
                        <i data-lucide="wallet"></i>
                    </div>

                    <div>
                        <span>Nilai Persediaan</span>
                        <strong style="font-size:17px;">
                            Rp {{ number_format($totalValue, 0, ',', '.') }}
                        </strong>
                        <small>Total nilai stok gudang</small>
                    </div>
                </div>

            </div>


            {{-- TABLE --}}
            <div class="table-card">

                <div class="table-header-action">

                    <div class="table-title">
                        <i data-lucide="warehouse"></i>
                        <h2>Daftar Stok Material</h2>
                    </div>

                    <div class="stokgudang-search">
                        <i data-lucide="search"></i>
                        <input type="text"
                               id="stockSearch"
                               placeholder="Cari kode atau material..."
                               oninput="searchStock()">
                    </div>

                </div>


                {{-- FILTER PERIODE --}}
                <div class="stock-filter">

                    <form action="{{ route('stokgudang.index') }}"
                          method="GET"
                          class="stock-filter-form"
                          id="stockFilterForm">

                        @if($selectedCategory)
                            <input type="hidden"
                                   name="warehouse_category_id"
                                   value="{{ $selectedCategory }}">
                        @endif

                        <div class="filter-item">
                            <label>Bulan</label>
                            <select name="month" id="filterMonth">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}"
                                        @selected($month == $m)>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-item">
                            <label>Tahun</label>
                            <select name="year" id="filterYear">
                                @foreach(range(now()->year - 5, now()->year + 2) as $y)
                                    <option value="{{ $y }}"
                                        @selected($year == $y)>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-item">
                            <label>Dari Tanggal</label>
                            <input type="date"
                                   name="start_date"
                                   id="filterStartDate"
                                   value="{{ $startDate->format('Y-m-d') }}">
                        </div>

                        <div class="filter-item">
                            <label>Sampai Tanggal</label>
                            <input type="date"
                                   name="end_date"
                                   id="filterEndDate"
                                   value="{{ $endDate->format('Y-m-d') }}">
                        </div>

                        <button type="submit" class="btn-filter">
                            <i data-lucide="filter"></i>
                            Terapkan
                        </button>

                        <a href="{{ route('stokgudang.index', $selectedCategory ? ['warehouse_category_id' => $selectedCategory] : []) }}"
                           class="btn-reset">
                            Reset
                        </a>

                    </form>

                    <p class="period-information">
                        <i data-lucide="calendar"></i>
                        Periode:
                        <strong>
                            {{ $startDate->format('d/m/Y') }}
                            -
                            {{ $endDate->format('d/m/Y') }}
                        </strong>
                    </p>

                </div>


                {{-- TABLE CONTENT --}}
                <div class="table-responsive">

                    <table class="data-table stokgudang-data-table"
                           id="stockTable">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Material</th>
                                <th>Stok Awal</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Tersedia</th>
                                <th>Satuan</th>
                                <th>Harga Pokok</th>
                                <th>Nilai Stok</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($stocks as $index => $stock)

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $stock->tanggal_data ? \Carbon\Carbon::parse($stock->tanggal_data)->translatedFormat('d M Y') : '-' }}
                                    </td>

                                    <td>{{ $stock->code }}</td>

                                    <td>{{ $stock->name }}</td>

                                    <td>
                                        {{ number_format($stock->opening_stock, 2, ',', '.') }}
                                    </td>

                                    <td class="stock-in">
                                        +{{ number_format($stock->total_in, 2, ',', '.') }}
                                    </td>

                                    <td class="stock-out">
                                        -{{ number_format($stock->total_out, 2, ',', '.') }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format($stock->stock, 2, ',', '.') }}
                                        </strong>
                                    </td>

                                    <td>{{ $stock->unit }}</td>

                                    <td>
                                        Rp {{ number_format($stock->average_cost, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        Rp {{ number_format($stock->stock_value, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <span class="stock-status
                                            {{ $stock->status === 'Aman' ? 'status-safe' :
                                            ($stock->status === 'Menipis' ? 'status-low' : 'status-empty') }}">
                                            {{ $stock->status }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-dropdown">

                                            <button type="button"
                                                    class="btn-action"
                                                    onclick="toggleDropdown(event, 'stock-{{ $stock->id }}')">
                                                <i data-lucide="more-horizontal"></i>
                                            </button>

                                            <div id="stock-{{ $stock->id }}" class="dropdown-menu-action">

                                                <a href="{{ route('stokgudang.edit', $stock->id) }}"
                                                class="dropdown-item-action">
                                                    <i data-lucide="edit-3"></i>
                                                    Edit
                                                </a>

                                                <form action="{{ route('stokgudang.destroy', $stock->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item-action delete">
                                                        <i data-lucide="trash-2"></i>
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="13" class="empty-state">
                                        Belum ada data stok untuk periode ini.
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


{{-- JAVASCRIPT --}}
<script>

lucide.createIcons();

function searchStock() {
    const input = document.getElementById('stockSearch');
    const filter = input.value.toLowerCase();

    document.querySelectorAll('#stockTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(filter)
            ? ''
            : 'none';
    });
}

const globalSearch = document.getElementById('globalSearch');

if (globalSearch) {
    globalSearch.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();

        document.querySelectorAll('#stockTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter)
                ? ''
                : 'none';
        });
    });
}

function changeTheme() {
    document.body.classList.toggle('pink-theme');

    localStorage.setItem(
        'dhd-theme',
        document.body.classList.contains('pink-theme') ? 'pink' : 'default'
    );
}

document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('dhd-theme') === 'pink') {
        document.body.classList.add('pink-theme');
    }

    const monthSelect = document.getElementById('filterMonth');
    const yearSelect  = document.getElementById('filterYear');
    const startInput  = document.getElementById('filterStartDate');
    const endInput    = document.getElementById('filterEndDate');

    function syncDatesWithMonthYear() {
        if (!monthSelect || !yearSelect || !startInput || !endInput) return;

        const year  = parseInt(yearSelect.value, 10);
        const month = parseInt(monthSelect.value, 10);

        const firstDay = new Date(year, month - 1, 1);
        const lastDay  = new Date(year, month, 0);

        const pad = (n) => String(n).padStart(2, '0');
        const fmt = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

        startInput.value = fmt(firstDay);
        endInput.value   = fmt(lastDay);
    }

    if (monthSelect) monthSelect.addEventListener('change', syncDatesWithMonthYear);
    if (yearSelect)  yearSelect.addEventListener('change', syncDatesWithMonthYear);
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

updateDateTime();
setInterval(updateDateTime, 1000);

function toggleDropdown(event, id) {
    event.stopPropagation();

    document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
        if (menu.id !== id) {
            menu.classList.remove('show');
        }
    });

    document.getElementById(id).classList.toggle('show');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-dropdown')) {
        document.querySelectorAll('.dropdown-menu-action').forEach(menu => {
            menu.classList.remove('show');
        });
    }
});

function deleteStock(id) {
    if (confirm('Apakah kamu yakin ingin menghapus data barang ini?')) {
        document.getElementById('delete-stock-form-' + id).submit();
    }
}

</script>

</body>
</html>