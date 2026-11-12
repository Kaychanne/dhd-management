<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Inventori & Stok</title>

    @vite(['resources/css/laporan.css'])

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
               class="menu-item{{ request()->routeIs('masterharga.*', 'supplier.*') ? 'active' : '' }}">
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
                    Pembelian & stok
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

        {{-- LAPORAN --}}
        <a href="#" class="menu-item  menu-toggle active">
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



        <section class="content-wrapper">


            <div class="page-heading">
                <div>
                    <span class="page-eyebrow">REPORT CENTER</span>
                    <h1>Laporan & Ekspor Data</h1>
                    <p>
                        Kelola, lihat, dan unduh laporan perusahaan
                        dalam satu tempat.
                    </p>
                </div>
            </div>

            <div class="report-grid">

                <div class="report-card rap-card">
                    <div class="report-card-top">
                        <div class="report-icon">
                            <i data-lucide="clipboard-list"></i>
                        </div>
                        <span class="report-number">01</span>
                    </div>

                    <div class="report-card-content">
                        <h3>Laporan RAP</h3>
                        <p>Anggaran dan realisasi proyek</p>
                    </div>

                    <div class="report-card-footer">
                        <span>Anggaran Proyek</span>
                        <i data-lucide="arrow-up-right"></i>
                    </div>
                </div>

                <div class="report-card finance-card">
                    <div class="report-card-top">
                        <div class="report-icon">
                            <i data-lucide="chart-no-axes-combined"></i>
                        </div>
                        <span class="report-number">02</span>
                    </div>

                    <div class="report-card-content">
                        <h3>Laporan Keuangan</h3>
                        <p>Pemasukan dan pengeluaran</p>
                    </div>

                    <div class="report-card-footer">
                        <span>Keuangan Perusahaan</span>
                        <i data-lucide="arrow-up-right"></i>
                    </div>
                </div>

                <div class="report-card debt-card">
                    <div class="report-card-top">
                        <div class="report-icon">
                            <i data-lucide="hand-coins"></i>
                        </div>
                        <span class="report-number">03</span>
                    </div>

                    <div class="report-card-content">
                        <h3>Hutang & Piutang</h3>
                        <p>Tagihan dan pembayaran</p>
                    </div>

                    <div class="report-card-footer">
                        <span>Tagihan Perusahaan</span>
                        <i data-lucide="arrow-up-right"></i>
                    </div>
                </div>

                <div class="report-card stock-card">
                    <div class="report-card-top">
                        <div class="report-icon">
                            <i data-lucide="boxes"></i>
                        </div>
                        <span class="report-number">04</span>
                    </div>

                    <div class="report-card-content">
                        <h3>Laporan Stok</h3>
                        <p>Persediaan dan mutasi barang</p>
                    </div>

                    <div class="report-card-footer">
                        <span>Inventori & Gudang</span>
                        <i data-lucide="arrow-up-right"></i>
                    </div>
                </div>

            </div>

            <div class="report-section">

                <div class="section-heading">
                    <div>
                        <span class="section-eyebrow">REPORT SETTINGS</span>
                        <h2>Filter Laporan</h2>
                        <p>Atur periode, kategori laporan, dan format ekspor.</p>
                    </div>

                    <div class="filter-heading-icon">
                        <i data-lucide="sliders-horizontal"></i>
                    </div>
                </div>

                <form id="reportForm" class="report-filter-form">

                    <div class="filter-group">
                        <label for="reportType">Kategori Laporan</label>
                        <div class="input-wrapper">
                            <i data-lucide="file-text"></i>
                            <select id="reportType" name="report_type">
                                <option value="rap">Laporan RAP</option>
                                <option value="keuangan">Laporan Keuangan</option>
                                <option value="hutang">Hutang & Piutang</option>
                                <option value="stok">Laporan Stok</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="startDate">Tanggal Mulai</label>
                        <div class="input-wrapper">
                            <i data-lucide="calendar"></i>
                            <input type="date" id="startDate" name="start_date">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="endDate">Tanggal Akhir</label>
                        <div class="input-wrapper">
                            <i data-lucide="calendar-check"></i>
                            <input type="date" id="endDate" name="end_date">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="exportFormat">Format Ekspor</label>
                        <div class="input-wrapper">
                            <i data-lucide="file-down"></i>
                            <select id="exportFormat" name="format">
                                <option value="excel">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                                <option value="pdf">PDF (.pdf)</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="button" class="btn-reset" id="resetFilter">
                            <i data-lucide="rotate-ccw"></i>
                            Reset
                        </button>

                        <button type="button" class="btn-preview" id="previewReport">
                            <i data-lucide="eye"></i>
                            Preview Laporan
                        </button>

                        <button type="button" class="btn-export" id="exportReport">
                            <i data-lucide="download"></i>
                            Ekspor Data
                        </button>
                    </div>

                </form>
            </div>

            <div class="export-info">
                <div class="export-info-icon">
                    <i data-lucide="info"></i>
                </div>

                <div>
                    <h4>Informasi Ekspor Laporan</h4>
                    <p>
                        Pilih kategori dan periode laporan sebelum melakukan
                        ekspor. Data akan disiapkan sesuai filter yang dipilih
                        dan format file yang ditentukan.
                    </p>
                </div>
            </div>


            <div class="report-section preview-section">

                <div class="section-heading preview-heading">
                    <div>
                        <span class="section-eyebrow">DATA OVERVIEW</span>
                        <h2>Preview Laporan</h2>
                        <p id="previewDescription">
                            Pratinjau konsep laporan perusahaan.
                        </p>
                    </div>

                    <span class="preview-status">
                        <span></span>
                        Preview
                    </span>
                </div>

                <div class="preview-table-wrapper">
                    <table class="preview-table">
                        <thead id="previewHead">
                            <tr>
                                <th>No.</th>
                                <th>Nama Proyek</th>
                                <th>Anggaran</th>
                                <th>Realisasi</th>
                                <th>Selisih</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody id="previewBody">
                            <tr>
                                <td colspan="6" class="empty-preview">
                                    <i data-lucide="file-search"></i>
                                    <strong>Belum ada preview laporan</strong>
                                    <span>
                                        Pilih kategori laporan dan klik
                                        Preview Laporan.
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="preview-footer">
                    <span id="previewCount">Menunggu data laporan</span>
                    <span> DHD Interior Management System</span>
                </div>

            </div>


            <div class="format-section">

                <div class="format-heading">
                    <div>
                        <span class="section-eyebrow">DOWNLOAD OPTIONS</span>
                        <h2>Format File Tersedia</h2>
                        <p>Pilih format sesuai kebutuhan dokumentasi.</p>
                    </div>
                </div>

                <div class="format-grid">

                    <div class="format-card">
                        <div class="format-icon excel-icon">
                            <i data-lucide="file-spreadsheet"></i>
                        </div>
                        <div>
                            <h3>Microsoft Excel</h3>
                            <p>Format .xlsx untuk pengolahan data dan perhitungan.</p>
                        </div>
                        <span class="format-label excel-label">EXCEL</span>
                    </div>

                    <div class="format-card">
                        <div class="format-icon csv-icon">
                            <i data-lucide="file-text"></i>
                        </div>
                        <div>
                            <h3>CSV File</h3>
                            <p>Format .csv untuk pertukaran dan pengolahan data.</p>
                        </div>
                        <span class="format-label csv-label">CSV</span>
                    </div>

                    <div class="format-card">
                        <div class="format-icon pdf-icon">
                            <i data-lucide="file-pdf"></i>
                        </div>
                        <div>
                            <h3>PDF Document</h3>
                            <p>Format .pdf untuk dokumentasi dan pencetakan.</p>
                        </div>
                        <span class="format-label pdf-label">PDF</span>
                    </div>

                </div>

            </div>

            <div class="page-disclaimer">
                <i data-lucide="info"></i>
                Preview tampilan dan data di halaman ini belum terhubung
                dengan sistem ekspor database.
            </div>

        </section>

    </main>

    <script>
        lucide.createIcons();


        const currentDate = document.getElementById('currentDate');

        currentDate.textContent = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        const reportType = document.getElementById('reportType');
        const previewHead = document.getElementById('previewHead');
        const previewBody = document.getElementById('previewBody');
        const previewDescription = document.getElementById('previewDescription');
        const previewCount = document.getElementById('previewCount');

        const previewColumns = {
            rap: {
                title: 'Preview Laporan RAP',
                columns: ['No.', 'Nama Proyek', 'Anggaran', 'Realisasi', 'Selisih', 'Status']
            },
            keuangan: {
                title: 'Preview Laporan Keuangan',
                columns: ['No.', 'Tanggal', 'Keterangan', 'Kategori', 'Jenis', 'Nominal']
            },
            hutang: {
                title: 'Preview Hutang & Piutang',
                columns: ['No.', 'Nama Pihak', 'Jenis', 'Tanggal', 'Jatuh Tempo', 'Nominal']
            },
            stok: {
                title: 'Preview Laporan Stok',
                columns: ['No.', 'Kode Barang', 'Nama Material', 'Stok', 'Satuan', 'Status']
            }
        };

        function renderHeader() {
            const selected = previewColumns[reportType.value];

            previewHead.innerHTML = `
                <tr>
                    ${selected.columns.map(column => `<th>${column}</th>`).join('')}
                </tr>
            `;

            previewBody.innerHTML = `
                <tr>
                    <td colspan="6" class="empty-preview">
                        <strong>${selected.title}</strong>
                        <span>Silakan hubungkan data database untuk menampilkan laporan.</span>
                    </td>
                </tr>
            `;

            previewDescription.textContent = selected.title;
            previewCount.textContent = 'Belum ada data aktual';
            lucide.createIcons();
        }

        document.getElementById('previewReport').addEventListener('click', function () {
            renderHeader();
        });

        reportType.addEventListener('change', renderHeader);

        document.getElementById('resetFilter').addEventListener('click', function () {
            document.getElementById('reportForm').reset();

            renderHeader();
        });


        document.getElementById('exportReport').addEventListener('click', function () {
            alert('Fitur ekspor akan aktif setelah integrasi Controller dan database selesai.');
        });


        function changeTheme() {
            document.body.classList.toggle('dark-theme');
        }
    </script>

</body>
</html>
