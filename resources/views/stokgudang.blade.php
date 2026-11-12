
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
               class="menu-item menu-toggle menu-parent-active">
                <i data-lucide="package"></i>
                <span>Inventori & Stok</span>
                <i data-lucide="chevron-up" class="arrow"></i>
            </a>

            <div class="submenu open">

                <a href="{{ route('masterharga.index') }}">
                    Master Harga
                </a>

                <a href="{{ route('supplier.index') }}">
                    Supplier
                </a>

                <a href="{{ route('pembelianstok.index') }}">
                    Pembelian & Stok
                </a>

                <a href="{{ route('stokgudang.index') }}" class="active">
                    Stok Gudang
                </a>

            </div>
        </div>

        <a href="#" class="menu-item">
            <i data-lucide="wallet"></i>
            <span>Operasional & Finansial</span>
        </a>

        <a href="{{ route('laporan.index') }}" class="menu-item">
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

            <button type="button" class="theme-button"
                    onclick="changeTheme()">
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
    </div>

</aside>


{{-- MAIN CONTENT --}}
<main class="main-content">

    {{-- TOPBAR --}}
    <header class="topbar">

        <div class="search-box">
            <i data-lucide="search"></i>
            <input type="text"
                   id="globalSearch"
                   placeholder="Cari material...">
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

            {{-- PAGE HEADER --}}
            
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

    <<a href="{{ route('stokgudang.create') }}"
        class="btn btn-primary"
        style="text-decoration:none;">
            <i data-lucide="plus"></i>
            Tambah Barang
        </a>
    </div>

</div>


            {{-- STATISTICS --}}
            <div class="stokgudang-stats">

                {{-- TOTAL MATERIAL --}}
                <div class="stokgudang-stat-card">

                    <div class="stokgudang-stat-icon purple">
                        <i data-lucide="package"></i>
                    </div>

                    <div>
                        <span>Total Jenis Barang</span>

                        <strong>
                            {{ number_format($totalMaterials, 0, ',', '.') }}
                        </strong>

                        <small>Material terdaftar</small>
                    </div>

                </div>


                {{-- TOTAL STOK --}}
                <div class="stokgudang-stat-card">

                    <div class="stokgudang-stat-icon green">
                        <i data-lucide="boxes"></i>
                    </div>

                    <div>
                        <span>Total Stok Barang</span>

                        <strong>
                            {{ number_format($totalStock, 0, ',', '.') }}
                        </strong>

                        <small>Akumulasi seluruh stok</small>
                    </div>

                </div>


                {{-- STOK MENIPIS --}}
                <div class="stokgudang-stat-card">

                    <div class="stokgudang-stat-icon orange">
                        <i data-lucide="alert-triangle"></i>
                    </div>

                    <div>
                        <span>Stok Menipis / Habis</span>

                        <strong>
                            {{ number_format($lowStock, 0, ',', '.') }}
                        </strong>

                        <small>Perlu pengadaan ulang</small>
                    </div>

                </div>


                {{-- NILAI PERSEDIAAN --}}
                <div class="stokgudang-stat-card">

                    <div class="stokgudang-stat-icon blue">
                        <i data-lucide="wallet"></i>
                    </div>

                    <div>
                        <span>Nilai Persediaan</span>

                        <strong style="font-size: 17px;">
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
                               onkeyup="searchStock()">
                    </div>

                </div>


                {{-- TABLE CONTENT --}}
                <div class="table-responsive">

                    <table class="data-table stokgudang-data-table"
                           id="stockTable">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Kode</th>
                                <th>Material</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Tersedia</th>
                                <th>Satuan</th>
                                <th>Harga Pokok</th>
                                <th>Nilai Stok</th>
                                <th>Status</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>

                            <tbody>
                            @forelse($stocks as $index => $stock)

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $stock->code ?? 'MAT-' . str_pad($stock->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ number_format($stock->total_in, 0, ',', '.') }}</td>
                                    <td>{{ number_format($stock->total_out, 0, ',', '.') }}</td>
                                    <td>{{ number_format($stock->stock, 0, ',', '.') }}</td>
                                    <td>{{ $stock->unit ?? '-' }}</td>
                                    <td>Rp {{ number_format($stock->average_cost, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($stock->stock_value, 0, ',', '.') }}</td>

                                    <td>
                                        <span class="badge badge-success">
                                            {{ $stock->status }}
                                        </span>
                                    </td>

                                    {{-- ACTION DROPDOWN --}}
                                    <td>
                                        <div class="action-dropdown">

                                            <button
                                                type="button"
                                                class="btn-action"
                                                onclick="toggleDropdown(event, 'stock-{{ $stock->id }}')">
                                                <i data-lucide="more-horizontal"></i>
                                            </button>
                                            <div id="stock-{{ $stock->id }}"
                                                class="dropdown-menu-action">

                                                <a href="{{ route('stokgudang.edit', $stock->id) }}"
                                                class="dropdown-item-action">
                                                    <i data-lucide="edit-3"></i>
                                                    Edit
                                                </a>

                                                <button type="button"
                                                        class="dropdown-item-action delete"
                                                        onclick="deleteStock({{ $stock->id }})">
                                                    <i data-lucide="trash-2"></i>
                                                    Hapus
                                                </button>

                                                <form id="delete-stock-form-{{ $stock->id }}"
                                                    method="POST"
                                                    action="{{ route('stokgudang.destroy', $stock->id) }}"
                                                    style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                            </div>
                                        </div>
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="11" class="empty-state">
                                        Belum ada data stok.
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


<script>

    lucide.createIcons();
    function searchStock() {

        const input = document.getElementById('stockSearch');
        const filter = input.value.toLowerCase();

        document.querySelectorAll('#stockTable tbody tr')
            .forEach(row => {

                row.style.display =
                    row.textContent.toLowerCase().includes(filter)
                        ? ''
                        : 'none';

            });
    }

    const globalSearch = document.getElementById('globalSearch');

    globalSearch.addEventListener('keyup', function() {

        const filter = this.value.toLowerCase();

        document.querySelectorAll('#stockTable tbody tr')
            .forEach(row => {

                row.style.display =
                    row.textContent.toLowerCase().includes(filter)
                        ? ''
                        : 'none';

            });

    });

    function changeTheme() {

        document.body.classList.toggle('pink-theme');

        localStorage.setItem(
            'dhd-theme',
            document.body.classList.contains('pink-theme')
                ? 'pink'
                : 'default'
        );

    }


    document.addEventListener('DOMContentLoaded', function() {

        if (localStorage.getItem('dhd-theme') === 'pink') {
            document.body.classList.add('pink-theme');
        }

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

    const dropdown = document.getElementById(id);

    document.querySelectorAll('.dropdown-menu-action')
        .forEach(menu => {
            if (menu.id !== id) {
                menu.classList.remove('show');
            }
        });

    dropdown.classList.toggle('show');
}


document.addEventListener('click', function(event) {

    if (!event.target.closest('.action-dropdown')) {

        document.querySelectorAll('.dropdown-menu-action')
            .forEach(menu => {
                menu.classList.remove('show');
            });

    }

});


function deleteStock(id) {

    if (confirm('Apakah kamu yakin ingin menghapus data stok ini?')) {

        document.getElementById('delete-stock-form-' + id).submit();

    }

}
</script>

</body>
</html>
