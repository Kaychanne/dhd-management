<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Laporan</title>

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
        {{-- INVENTORI & STOK (MENU GROUP) --}}
        <div class="menu-group">
            <a href="{{ route('inventori.index') }}"
               class="menu-item {{ request()->routeIs('masterharga.*', 'supplier.*', 'pembelianstok.*', 'stokgudang.*') ? 'active' : '' }}">
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
                    Pembelian &amp; Stok
                </a>
                <a href="{{ route('stokgudang.index') }}"
                   class="{{ request()->routeIs('stokgudang.*') ? 'active' : '' }}">
                    Stok Gudang
                </a>
            </div>
        </div>

        {{-- LAPORAN --}}
        <a href="{{ route('laporan.index') }}" class="menu-item menu-toggle active">
            <i data-lucide="file-text"></i>
            <span>Laporan</span>
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
            <input type="text" id="globalSearch" placeholder="Cari data laporan...">
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
                <h1>Laporan &amp; Ekspor Data</h1>
                <p>
                    Kelola, lihat, dan unduh laporan pembelian serta stok gudang dalam satu tempat.
                </p>
            </div>
        </div>

        {{-- KARTU PILIHAN LAPORAN (KHUSUS PEMBELIAN & STOK GUDANG) --}}
        <div class="report-grid">

            <div class="report-card purchase-card" id="cardPembelian" style="cursor: pointer;">
                <div class="report-card-top">
                    <div class="report-icon">
                        <i data-lucide="shopping-cart"></i>
                    </div>
                    <span class="report-number">01</span>
                </div>

                <div class="report-card-content">
                    <h3>Pembelian &amp; Stok</h3>
                    <p>Riwayat transaksi pembelian material dan ongkir</p>
                </div>

                <div class="report-card-footer">
                    <span>Transaksi Masuk</span>
                    <i data-lucide="arrow-up-right"></i>
                </div>
            </div>

            <div class="report-card stock-card" id="cardStok" style="cursor: pointer;">
                <div class="report-card-top">
                    <div class="report-icon">
                        <i data-lucide="boxes"></i>
                    </div>
                    <span class="report-number">02</span>
                </div>

                <div class="report-card-content">
                    <h3>Stok Gudang</h3>
                    <p>Persediaan barang, harga pokok, dan nilai stok</p>
                </div>

                <div class="report-card-footer">
                    <span>Inventori &amp; Gudang</span>
                    <i data-lucide="arrow-up-right"></i>
                </div>
            </div>

        </div>

        {{-- FILTER LAPORAN --}}
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
                            <option value="pembelian">Laporan Pembelian &amp; Stok</option>
                            <option value="stok">Laporan Stok Gudang</option>
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
                    Pilih kategori (<strong>Pembelian &amp; Stok</strong> atau <strong>Stok Gudang</strong>) dan periode laporan sebelum melakukan ekspor. Data akan disiapkan sesuai filter yang dipilih dalam format Excel, CSV, atau PDF.
                </p>
            </div>
        </div>

        {{-- PREVIEW LAPORAN --}}
        <div class="report-section preview-section">

            <div class="section-heading preview-heading">
                <div>
                    <span class="section-eyebrow">DATA OVERVIEW</span>
                    <h2>Preview Laporan</h2>
                    <p id="previewDescription">
                        Pratinjau laporan Pembelian &amp; Stok.
                    </p>
                </div>

                <span class="preview-status">
                    <span></span>
                    Preview
                </span>
            </div>

            <div class="preview-table-wrapper">
                <table class="preview-table" id="previewTable">
                    <thead id="previewHead">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>No. Invoice</th>
                            <th>Supplier</th>
                            <th>Proyek</th>
                            <th>Total Unit</th>
                            <th>Ongkos Kirim</th>
                            <th>Total Pembelian</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="previewBody">
                        <tr>
                            <td colspan="9" class="empty-preview">
                                <i data-lucide="file-search"></i>
                                <strong>Memuat preview laporan...</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="preview-footer">
                <span id="previewCount">Menunggu data laporan</span>
                <span>DHD Interior Management System</span>
            </div>

        </div>

    </section>
</main>

<script>
    lucide.createIcons();

    function updateClock() {
        const now = new Date();
        const dateEl = document.getElementById('currentDate');
        const timeEl = document.getElementById('currentTime');
        if (dateEl) {
            dateEl.textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }
        if (timeEl) {
            timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }) + ' WIB';
        }
    }
    updateClock();
    setInterval(updateClock, 1000);

    const reportType         = document.getElementById('reportType');
    const startDateInput     = document.getElementById('startDate');
    const endDateInput       = document.getElementById('endDate');
    const exportFormatInput  = document.getElementById('exportFormat');
    const previewHead        = document.getElementById('previewHead');
    const previewBody        = document.getElementById('previewBody');
    const previewDescription = document.getElementById('previewDescription');
    const previewCount       = document.getElementById('previewCount');

    async function loadPreview() {
        const params = new URLSearchParams({
            report_type: reportType.value,
            start_date: startDateInput.value,
            end_date: endDateInput.value,
        });

        previewBody.innerHTML = `
            <tr>
                <td colspan="12" class="empty-preview">
                    <strong>Memuat data laporan...</strong>
                </td>
            </tr>
        `;

        try {
            const response = await fetch(`{{ route('laporan.preview') }}?${params.toString()}`);
            const data = await response.json();

            previewDescription.textContent = data.title;

            previewHead.innerHTML = `
                <tr>
                    ${data.headings.map(col => `<th>${col}</th>`).join('')}
                </tr>
            `;

            if (!data.rows || data.rows.length === 0) {
                previewBody.innerHTML = `
                    <tr>
                        <td colspan="${data.headings.length}" class="empty-preview">
                            <strong>Tidak ada data ditemukan</strong>
                            <span>Coba ubah rentang tanggal atau pilih kategori laporan lain.</span>
                        </td>
                    </tr>
                `;
                previewCount.textContent = '0 data ditemukan';
            } else {
                previewBody.innerHTML = data.rows.map(row => `
                    <tr>
                        ${row.map(cell => `<td>${cell}</td>`).join('')}
                    </tr>
                `).join('');
                previewCount.textContent = `Menampilkan ${data.rows.length} baris data`;
            }
        } catch (error) {
            previewBody.innerHTML = `
                <tr>
                    <td colspan="12" class="empty-preview">
                        <strong>Gagal memuat preview</strong>
                        <span>Periksa koneksi atau log server Laravel.</span>
                    </td>
                </tr>
            `;
        }
    }

    document.getElementById('previewReport').addEventListener('click', loadPreview);
    reportType.addEventListener('change', loadPreview);

    document.getElementById('resetFilter').addEventListener('click', function () {
        document.getElementById('reportForm').reset();
        loadPreview();
    });

    document.getElementById('exportReport').addEventListener('click', function () {
        const params = new URLSearchParams({
            report_type: reportType.value,
            start_date: startDateInput.value,
            end_date: endDateInput.value,
            format: exportFormatInput.value,
        });

        window.location.href = `{{ route('laporan.export') }}?${params.toString()}`;
    });

    document.getElementById('cardPembelian')?.addEventListener('click', () => {
        reportType.value = 'pembelian';
        loadPreview();
    });

    document.getElementById('cardStok')?.addEventListener('click', () => {
        reportType.value = 'stok';
        loadPreview();
    });

    // Pencarian cepat di tabel preview
    document.getElementById('globalSearch')?.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#previewBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });

    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
    }

    if (localStorage.getItem('dhd-theme') === 'pink') {
        document.body.classList.add('pink-theme');
    }

    loadPreview();
</script>

</body>
</html>