<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Dashboard</title>

    @vite(['resources/css/dashboard.css'])

    <script src="https://unpkg.com/lucide@latest"></script>

</head>


<body>


<div class="dashboard-wrapper">


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}

    <aside class="sidebar">


        <div class="sidebar-brand">

            <div class="brand-logo">
                DHD
            </div>

            <div class="brand-text">

                <strong>
                    Interior
                </strong>

                <span>
                    Management System
                </span>

            </div>

        </div>



        <nav class="sidebar-menu">


            {{-- DASHBOARD --}}

            <a
                href="{{ route('dashboard') }}"
                class="menu-item active"
            >

                <i data-lucide="layout-dashboard"></i>

                <span>
                    Dashboard
                </span>

            </a>



            {{-- RAP --}}

            <a
                href="#"
                class="menu-item"
            >

                <i data-lucide="chart-no-axes-combined"></i>

                <span>
                    RAP & Anggaran
                </span>

            </a>



            {{-- INVENTORI --}}

            <div class="menu-group">

            <a
                href="{{ route('inventori.index') }}"
                class="menu-item {{ request()->routeIs('inventori.index') ? 'active' : '' }}"
            >
                <i data-lucide="package"></i>

                <span>
                    Inventori & Stok
                </span>

                <i
                    data-lucide="chevron-down"
                    class="arrow"
                ></i>
            </a>


            <div class="submenu">

                <a href="{{ route('inventori.index') }}">
                    Dashboard Inventori
                </a>

                <a href="#">
                    Master Harga & Supplier
                </a>

                <a href="#">
                    Pembelian & Stok
                </a>

                <a href="#">
                    Stok Gudang
                </a>

                <a href="#">
                    Mutasi Stok
                </a>

                <a href="#">
                    Stok Opname
                </a>

            </div>

        </div>



            {{-- OPERASIONAL --}}

            <a
                href="#"
                class="menu-item"
            >

                <i data-lucide="wallet"></i>

                <span>
                    Operasional & Finansial
                </span>

            </a>



            {{-- LAPORAN --}}

            <a
                href="#"
                class="menu-item"
            >

                <i data-lucide="file-text"></i>

                <span>
                    Laporan
                </span>

            </a>


        </nav>



        {{-- SIDEBAR FOOTER --}}

        <div class="sidebar-footer">

    <div class="orange-line"></div>

    <p>
        Better Planning.
    </p>

    <p>
        Smoother Project.
    </p>



    {{-- CHANGE THEME --}}

    <button
        type="button"
        class="theme-button"
        onclick="changeTheme()"
    >

        <i data-lucide="palette"></i>

        <span>
            Change Theme
        </span>

    </button>
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form"
        >

            @csrf

            <button
                type="submit"
                class="logout-button"
            >

                <i data-lucide="log-out"></i>

                <span>
                    Logout
                </span>

            </button>

        </form>

    </div>


    </aside>



    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <main class="main-content">


        {{-- =====================================================
            TOPBAR
        ====================================================== --}}

        <header class="topbar">


            {{-- SEARCH --}}

            <div class="search-box">

                <i data-lucide="search"></i>

                <input
                    type="text"
                    placeholder="Cari proyek, barang, supplier atau transaksi..."
                >

            </div>



            <div class="topbar-right">


                {{-- DATE --}}

                <div class="date-info">

                    <i data-lucide="calendar-days"></i>

                    <div>

                        <strong>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </strong>

                        <span>
                            {{ now()->format('H:i') }} WIB
                        </span>

                    </div>

                </div>



                {{-- NOTIFICATION --}}

                <button
                    type="button"
                    class="notification-button"
                >

                    <i data-lucide="bell"></i>

                    <span class="notification-dot"></span>

                </button>



                {{-- PROFILE --}}

                <div class="admin-profile">


                    <div class="admin-avatar">

                        {{ strtoupper(
                            substr(
                                Auth::user()->name ?? 'AD',
                                0,
                                2
                            )
                        ) }}

                    </div>


                    <span>

                        {{ Auth::user()->name ?? 'Admin' }}

                    </span>


                    <i data-lucide="chevron-down"></i>


                </div>


            </div>


        </header>



        {{-- =====================================================
            DASHBOARD CONTENT
        ====================================================== --}}

        <section class="content">


            {{-- =================================================
                KPI CARDS
            ================================================== --}}

            <div class="kpi-grid">


                {{-- TOTAL RAP --}}

                <div class="kpi-card">


                    <div class="kpi-icon">

                        <i data-lucide="coins"></i>

                    </div>


                    <div class="kpi-content">

                        <span>
                            Total Nilai RAP
                        </span>

                        <strong>
                            Rp 100.000.000
                        </strong>

                        <small class="positive">
                            ↗ +0% dari bulan lalu
                        </small>

                    </div>


                    <i
                        data-lucide="chevron-right"
                        class="kpi-arrow"
                    ></i>


                </div>



                {{-- REALISASI BELANJA --}}

                <div class="kpi-card">


                    <div class="kpi-icon">

                        <i data-lucide="wallet-cards"></i>

                    </div>


                    <div class="kpi-content">

                        <span>
                            Realisasi Belanja
                        </span>

                        <strong>
                            Rp 42.000.000
                        </strong>


                        <div class="mini-progress">

                            <div
                                style="width:60%"
                            ></div>

                        </div>


                        <small>
                            60% dari anggaran efektif
                        </small>

                    </div>


                    <i
                        data-lucide="chevron-right"
                        class="kpi-arrow"
                    ></i>


                </div>



                {{-- TOTAL STOK --}}

                <div class="kpi-card">


                    <div class="kpi-icon">

                        <i data-lucide="package"></i>

                    </div>


                    <div class="kpi-content">

                        <span>
                            Total Stok
                        </span>

                        <strong>
                            256 Item
                        </strong>

                        <small class="positive">
                            ↗ +12% dari bulan lalu
                        </small>

                    </div>


                    <i
                        data-lucide="chevron-right"
                        class="kpi-arrow"
                    ></i>


                </div>


            </div>



            {{-- =================================================
                MAIN DASHBOARD GRID
            ================================================== --}}

            <div class="dashboard-grid">


                {{-- =================================================
                    LEFT COLUMN
                ================================================== --}}

                <div class="left-column">


                    {{-- =================================================
                        PROGRESS PROYEK
                    ================================================== --}}

                    <div class="dashboard-card project-card">


                        <div class="card-header">


                            <div>

                                <h2>

                                    <i data-lucide="house"></i>

                                    Progress Proyek

                                </h2>


                                <p>
                                    Pantau perkembangan proyek yang sedang berjalan.
                                </p>

                            </div>


                            <a href="#">
                                Lihat Semua Proyek →
                            </a>


                        </div>



                        {{-- FILTER --}}

                        <div class="project-filter">


                            <button
                                class="filter active"
                                type="button"
                            >
                                Semua
                            </button>


                            <button
                                class="filter"
                                type="button"
                            >
                                Berjalan
                            </button>


                            <button
                                class="filter"
                                type="button"
                            >
                                Pending
                            </button>


                            <button
                                class="filter"
                                type="button"
                            >
                                Selesai
                            </button>


                        </div>



                        {{-- PROJECT TABLE --}}

                        <div class="project-table">


                            {{-- HEADER --}}

                            <div class="project-row project-head">

                                <span>
                                    Proyek
                                </span>

                                <span>
                                    Alamat
                                </span>

                                <span>
                                    Progress
                                </span>

                                <span>
                                    Status
                                </span>

                                <span>
                                    Deadline
                                </span>

                            </div>



                            {{-- PROJECT 1 --}}

                            <div class="project-row">


                                <span class="project-name">

                                    <i data-lucide="house"></i>

                                    Rumah Bapak Andi

                                </span>


                                <span>
                                    Jl. Melati No. 12, Pontianak
                                </span>


                                <span class="progress-value">

                                    65%

                                    <span class="progress-bar">

                                        <div
                                            style="width:65%"
                                        ></div>

                                    </span>

                                </span>


                                <span>

                                    <span class="status running">
                                        Berjalan
                                    </span>

                                </span>


                                <span>
                                    15 Okt 2026
                                </span>


                            </div>



                            {{-- PROJECT 2 --}}

                            <div class="project-row">


                                <span class="project-name">

                                    <i data-lucide="house"></i>

                                    Rumah Ibu Rina

                                </span>


                                <span>
                                    Jl. Anggrek No. 8, Pontianak
                                </span>


                                <span class="progress-value">

                                    30%

                                    <span class="progress-bar">

                                        <div
                                            style="width:30%"
                                        ></div>

                                    </span>

                                </span>


                                <span>

                                    <span class="status pending">
                                        Pending
                                    </span>

                                </span>


                                <span>
                                    28 Okt 2026
                                </span>


                            </div>



                            {{-- PROJECT 3 --}}

                            <div class="project-row">


                                <span class="project-name">

                                    <i data-lucide="house"></i>

                                    Rumah Tuan Hadi

                                </span>


                                <span>
                                    Jl. Kamboja No. 5, Pontianak
                                </span>


                                <span class="progress-value">

                                    90%

                                    <span class="progress-bar">

                                        <div
                                            style="width:90%"
                                        ></div>

                                    </span>

                                </span>


                                <span>

                                    <span class="status running">
                                        Berjalan
                                    </span>

                                </span>


                                <span>
                                    10 Okt 2026
                                </span>


                            </div>



                            {{-- PROJECT 4 --}}

                            <div class="project-row">


                                <span class="project-name">

                                    <i data-lucide="house"></i>

                                    Rumah Ibu Sari

                                </span>


                                <span>
                                    Jl. Flamboyan No. 20, Pontianak
                                </span>


                                <span class="progress-value">

                                    15%

                                    <span class="progress-bar">

                                        <div
                                            style="width:15%"
                                        ></div>

                                    </span>

                                </span>


                                <span>

                                    <span class="status pending">
                                        Pending
                                    </span>

                                </span>


                                <span>
                                    5 Nov 2026
                                </span>


                            </div>


                        </div>


                    </div>



                    {{-- =================================================
                        HUTANG & PIUTANG
                    ================================================== --}}

                    <div class="dashboard-card finance-debt-card">


                        <div class="card-header compact">


                            <div>

                                <h2>

                                    <i data-lucide="wallet-cards"></i>

                                    Hutang & Piutang

                                </h2>


                                <p>
                                    Ringkasan tagihan yang perlu diperhatikan.
                                </p>

                            </div>


                            <a href="#">
                                Lihat Detail →
                            </a>


                        </div>



                        <div class="debt-receivable-grid">


                            {{-- PIUTANG --}}

                            <div class="money-summary receivable">


                                <div class="money-icon">

                                    <i data-lucide="arrow-up-right"></i>

                                </div>


                                <div class="money-info">

                                    <span>
                                        Total Piutang
                                    </span>

                                    <strong>
                                        Rp 18.500.000
                                    </strong>

                                    <small>
                                        2 klien aktif
                                    </small>

                                </div>


                            </div>



                            {{-- HUTANG --}}

                            <div class="money-summary payable">


                                <div class="money-icon">

                                    <i data-lucide="arrow-down-right"></i>

                                </div>


                                <div class="money-info">

                                    <span>
                                        Total Hutang
                                    </span>

                                    <strong>
                                        Rp 25.750.000
                                    </strong>

                                    <small>
                                        4 tagihan supplier
                                    </small>

                                </div>


                            </div>



                            {{-- JATUH TEMPO --}}

                            <div class="money-summary due">


                                <div class="money-icon">

                                    <i data-lucide="calendar-clock"></i>

                                </div>


                                <div class="money-info">

                                    <span>
                                        Jatuh Tempo
                                    </span>

                                    <strong>
                                        3 Transaksi
                                    </strong>

                                    <small>
                                        Perlu diperiksa
                                    </small>

                                </div>


                            </div>


                        </div>


                    </div>


                </div>



                {{-- =================================================
                    RIGHT COLUMN
                ================================================== --}}

                <div class="right-column">


                    {{-- =================================================
                        RINGKASAN KEUANGAN
                    ================================================== --}}

                    <div class="dashboard-card financial-card">


                        <div class="card-header compact">


                            <div>

                                <h2>

                                    <i data-lucide="chart-no-axes-combined"></i>

                                    Ringkasan Keuangan

                                </h2>


                                <p>
                                    Kondisi anggaran proyek saat ini.
                                </p>

                            </div>


                            <a href="#">
                                Detail →
                            </a>


                        </div>



                        <div class="financial-content">


                            {{-- ANGGARAN EFEKTIF --}}

                            <div class="financial-main">


                                <div>

                                    <span>
                                        Anggaran Efektif
                                    </span>

                                    <strong>
                                        Rp 70.000.000
                                    </strong>

                                </div>


                                <i data-lucide="wallet"></i>


                            </div>



                            {{-- REALISASI --}}

                            <div class="financial-row">


                                <div>

                                    <span>
                                        Realisasi Belanja
                                    </span>

                                    <strong>
                                        Rp 42.000.000
                                    </strong>

                                </div>


                                <span class="financial-percent">
                                    60%
                                </span>


                            </div>



                            {{-- PROGRESS BELANJA --}}

                            <div class="budget-progress">


                                <div class="budget-progress-head">

                                    <span>
                                        Progress Belanja
                                    </span>

                                    <strong>
                                        60%
                                    </strong>

                                </div>


                                <div class="budget-bar">

                                    <div
                                        style="width:60%"
                                    ></div>

                                </div>


                            </div>



                            {{-- SISA ANGGARAN --}}

                            <div class="financial-row remaining">


                                <div>

                                    <span>
                                        Sisa Anggaran
                                    </span>

                                    <strong>
                                        Rp 28.000.000
                                    </strong>

                                </div>


                                <i data-lucide="circle-dollar-sign"></i>


                            </div>



                            {{-- STATUS --}}

                            <div class="budget-status">

                                <i data-lucide="circle-check"></i>

                                <span>
                                    Pengeluaran masih dalam batas aman.
                                </span>

                            </div>


                        </div>


                    </div>



                    {{-- =================================================
                        QUICK ACTION
                    ================================================== --}}

                    <div class="dashboard-card quick-card">


                        <div class="card-header compact">


                            <h2>

                                <i data-lucide="zap"></i>

                                Quick Action

                            </h2>


                        </div>



                        <div class="quick-grid">


                            {{-- PEMBELIAN --}}

                            <a
                                href="#"
                                class="quick-action"
                            >

                                <i data-lucide="shopping-cart"></i>


                                <div>

                                    <strong>
                                        Pembelian
                                    </strong>

                                    <span>
                                        Barang masuk
                                    </span>

                                </div>

                            </a>



                            {{-- PROYEK --}}

                            <a
                                href="#"
                                class="quick-action"
                            >

                                <i data-lucide="folder-plus"></i>


                                <div>

                                    <strong>
                                        Proyek
                                    </strong>

                                    <span>
                                        Tambah proyek
                                    </span>

                                </div>

                            </a>



                            {{-- STOK --}}

                            <a
                                href="#"
                                class="quick-action"
                            >

                                <i data-lucide="boxes"></i>


                                <div>

                                    <strong>
                                        Stok
                                    </strong>

                                    <span>
                                        Kelola gudang
                                    </span>

                                </div>

                            </a>



                            {{-- LAPORAN --}}

                            <a
                                href="#"
                                class="quick-action"
                            >

                                <i data-lucide="file-chart-column"></i>


                                <div>

                                    <strong>
                                        Laporan
                                    </strong>

                                    <span>
                                        Ekspor data
                                    </span>

                                </div>

                            </a>


                        </div>


                    </div>


                </div>


            </div>



            {{-- =================================================
                FOOTER
            ================================================== --}}

            <footer class="dashboard-footer">

                © {{ date('Y') }} DHD Interior.
                All rights reserved.

            </footer>


        </section>


    </main>


</div>



{{-- =========================================================
    JAVASCRIPT
========================================================= --}}

<script>


    /* LUCIDE */

    lucide.createIcons();



    /* =====================================================
       INVENTORY SUBMENU
    ====================================================== */

    function toggleInventory() {


        const submenu =
            document.getElementById('inventorySubmenu');


        const toggle =
            document.querySelector('.menu-toggle');


        submenu.classList.toggle('open');


        toggle.classList.toggle('expanded');


    }



    /* =====================================================
       PROJECT FILTER
    ====================================================== */

    document
        .querySelectorAll('.filter')
        .forEach(button => {


            button.addEventListener(
                'click',
                function () {


                    document
                        .querySelectorAll('.filter')
                        .forEach(btn => {

                            btn.classList.remove(
                                'active'
                            );

                        });


                    this.classList.add(
                        'active'
                    );


                }
            );


        });

        function changeTheme() {

            document.body.classList.toggle('pink-theme');

            const isPink =
                document.body.classList.contains('pink-theme');

            localStorage.setItem(
                'dhd-theme',
                isPink ? 'pink' : 'blue'
            );
        }


        /* Simpan theme walaupun halaman direfresh */

        if (localStorage.getItem('dhd-theme') === 'pink') {

            document.body.classList.add('pink-theme');

        }
</script>


</body>

</html>