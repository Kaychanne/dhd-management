<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Master Harga</title>

    @vite([
        'resources/css/masterharga.css'
    ])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<div class="dashboard-wrapper">

    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
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
            <a href="#"
               class="menu-item">

                <i data-lucide="clipboard-list"></i>

                <span>RAP & Anggaran</span>

            </a>


            {{-- INVENTORI --}}
            <div class="menu-group">

                <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}"
                   class="menu-item">

                    <i data-lucide="package"></i>

                    <span>Inventori & Stok</span>

                    <i data-lucide="chevron-up"
                       class="arrow"></i>

                </a>


                <div class="submenu open">

                    {{-- MASTER HARGA --}}
                    <a href="#" class="menu-item active">

                        Master Harga

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
            <a href="#"
               class="menu-item">

                <i data-lucide="wallet"></i>

                <span>Operasional & Finansial</span>

            </a>


            {{-- LAPORAN --}}
            <a href="#"
               class="menu-item">

                <i data-lucide="file-text"></i>

                <span>Laporan</span>

            </a>


            {{-- PENGATURAN --}}
            <a href="#"
               class="menu-item">

                <i data-lucide="settings"></i>

                <span>Pengaturan</span>

            </a>

        </nav>


        {{-- =====================================================
            SIDEBAR FOOTER
        ====================================================== --}}
        <div class="sidebar-footer">

            <div class="orange-line"></div>

            <p>
                DHD Interior<br>
                Management System
            </p>


            <button type="button"
                    class="theme-button"
                    onclick="changeTheme()">

                <i data-lucide="palette"></i>

                <span>Change Theme</span>

            </button>


            @if(Route::has('logout'))

                <form method="POST"
                      action="{{ route('logout') }}"
                      class="logout-form">

                    @csrf

                    <button type="submit"
                            class="logout-button">

                        <i data-lucide="log-out"></i>

                        <span>Logout</span>

                    </button>

                </form>

            @endif

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

            <div class="search-box">

                <i data-lucide="search"></i>

                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Cari material..."
                >

            </div>


            <div class="topbar-right">


                {{-- DATE --}}
                <div class="date-info">

                    <i data-lucide="calendar-days"></i>

                    <div>

                        <strong id="currentDate"></strong>

                        <span id="currentTime"></span>

                    </div>

                </div>


                {{-- NOTIFICATION --}}
                <button type="button"
                        class="notification-button">

                    <i data-lucide="bell"></i>

                    <span class="notification-dot"></span>

                </button>


                {{-- ADMIN --}}
                <div class="admin-profile">

                    <div class="admin-avatar">
                        ST
                    </div>

                    <span>Steven</span>

                    <i data-lucide="chevron-down"></i>

                </div>

            </div>

        </header>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <section class="content">

            <div class="master-page">


                {{-- =================================================
                    PAGE HEADER
                ================================================== --}}
                <div class="master-header">

                    <div class="master-title">

                        <h1>

                            <i data-lucide="tags"></i>

                            Master Harga

                        </h1>

                        <p>
                            Kelola daftar harga material yang digunakan
                            dalam proyek.
                        </p>

                    </div>


                    <div class="master-header-actions">

                        <button type="button"
                                class="btn btn-primary"
                                onclick="openPriceModal()">

                            <i data-lucide="plus"></i>

                            Tambah Harga

                        </button>

                    </div>

                </div>


                {{-- =================================================
                    STATISTIC
                ================================================== --}}
                <div class="master-stats">


                    {{-- TOTAL MATERIAL --}}
                    <div class="master-stat-card">

                        <div class="master-stat-icon purple">

                            <i data-lucide="package"></i>

                        </div>

                        <div>

                            <span>Total Material</span>

                            <strong>
                                {{ number_format($totalMaterials ?? 0, 0, ',', '.') }}
                            </strong>

                            <small>
                                Material terdaftar
                            </small>

                        </div>

                    </div>


                    {{-- TOTAL HARGA --}}
                    <div class="master-stat-card">

                        <div class="master-stat-icon green">

                            <i data-lucide="badge-dollar-sign"></i>

                        </div>

                        <div>

                            <span>Data Harga</span>

                            <strong>
                                {{ number_format($totalPrices ?? 0, 0, ',', '.') }}
                            </strong>

                            <small>
                                Harga material
                            </small>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    TABLE
                ================================================== --}}
                <div class="table-card">


                    {{-- TABLE HEADER --}}
                    <div class="table-header-action">


                        <div class="table-title">

                            <i data-lucide="list"></i>

                            <h2>
                                Daftar Harga Material
                            </h2>

                        </div>


                        {{-- SEARCH --}}
                        <div class="master-search">

                            <i data-lucide="search"></i>

                            <input
                                type="text"
                                id="hargaSearch"
                                placeholder="Cari kode, material atau kategori..."
                                onkeyup="searchHarga()"
                            >

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table class="data-table master-data-table"
                               id="hargaTable">

                            <thead>

                            <tr>

                                <th width="50">
                                    <input
                                        type="checkbox"
                                        id="checkAll">
                                </th>

                                <th>
                                    Kode
                                </th>

                                <th>
                                    Material
                                </th>

                                <th>
                                    Kategori
                                </th>

                                <th>
                                    Harga
                                </th>

                                <th>
                                    Satuan
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Aksi
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @forelse($prices ?? [] as $price)

                                <tr>


                                    {{-- CHECKBOX --}}
                                    <td>

                                        <input
                                            type="checkbox"
                                            class="row-check"
                                            value="{{ $price->id }}">

                                    </td>


                                    {{-- KODE --}}
                                    <td>

                                        {{ $price->material_code ?? '-' }}

                                    </td>


                                    {{-- MATERIAL --}}
                                    <td>

                                        <div class="material-name">

                                            <div class="material-mini-icon">

                                                <i data-lucide="package"></i>

                                            </div>

                                            <span>

                                                {{ $price->material_name ?? '-' }}

                                            </span>

                                        </div>

                                    </td>


                                    {{-- KATEGORI --}}
                                    <td>

                                        {{ $price->category_name ?? '-' }}

                                    </td>


                                    {{-- HARGA --}}
                                    <td class="price-value">

                                        Rp
                                        {{ number_format($price->price ?? 0, 0, ',', '.') }}

                                    </td>


                                    {{-- SATUAN --}}
                                    <td>

                                        {{ $price->unit ?? '-' }}

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if(($price->status ?? 'active') === 'active')

                                            <span class="badge badge-success">
                                                Aktif
                                            </span>

                                        @else

                                            <span class="badge badge-danger">
                                                Nonaktif
                                            </span>

                                        @endif

                                    </td>


                                    {{-- AKSI --}}
                                    <td>

                                        <div class="action-dropdown">


                                            <button
                                                type="button"
                                                class="btn-action"
                                                onclick="toggleDropdown(event, 'price-{{ $price->id }}')">

                                                <i data-lucide="more-horizontal"></i>

                                            </button>


                                            <div
                                                id="price-{{ $price->id }}"
                                                class="dropdown-menu-action">


                                                {{-- DETAIL --}}
                                                <button
                                                    type="button"
                                                    class="dropdown-item-action"
                                                    onclick="showPriceDetail(
                                                        '{{ addslashes($price->material_name ?? '-') }}',
                                                        '{{ $price->material_code ?? '-' }}',
                                                        '{{ $price->category_name ?? '-' }}',
                                                        '{{ $price->price ?? 0 }}',
                                                        '{{ $price->unit ?? '-' }}'
                                                    )">

                                                    <i data-lucide="eye"></i>

                                                    Detail

                                                </button>


                                                {{-- EDIT --}}
                                                <button
                                                    type="button"
                                                    class="dropdown-item-action"
                                                    onclick="editPrice({{ $price->id }})">

                                                    <i data-lucide="edit-3"></i>

                                                    Edit

                                                </button>


                                                {{-- DELETE --}}
                                                <button
                                                    type="button"
                                                    class="dropdown-item-action delete"
                                                    onclick="deletePrice({{ $price->id }})">

                                                    <i data-lucide="trash-2"></i>

                                                    Hapus

                                                </button>

                                            </div>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="empty-state">

                                        <i data-lucide="package-open"></i>

                                        <strong>
                                            Belum ada data harga
                                        </strong>

                                        <span>
                                            Data harga material belum tersedia.
                                        </span>

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


{{-- =========================================================
    MODAL TAMBAH HARGA
========================================================== --}}
<div class="modal-overlay"
     id="priceModal">


    <div class="master-modal">


        {{-- MODAL HEADER --}}
        <div class="modal-header">

            <div>

                <span class="modal-icon">

                    <i data-lucide="badge-dollar-sign"></i>

                </span>


                <div>

                    <h3>
                        Tambah Harga Material
                    </h3>

                    <p>
                        Tambahkan harga baru untuk material.
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closePriceModal()"
                class="modal-close">

                <i data-lucide="x"></i>

            </button>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ Route::has('masterharga.price.store') ? route('masterharga.price.store') : '#' }}">

            @csrf


            <div class="modal-body">


                {{-- MATERIAL --}}
                <div class="form-group">

                    <label>
                        Material
                    </label>


                    <select
                        name="material_id"
                        required>

                        <option value="">
                            Pilih Material
                        </option>


                        @foreach($materials ?? [] as $material)

                            <option value="{{ $material->id }}">

                                {{ $material->code ?? 'MAT-' . str_pad($material->id, 3, '0', STR_PAD_LEFT) }}
                                -
                                {{ $material->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- HARGA + SATUAN --}}
                <div class="form-row">


                    <div class="form-group">

                        <label>
                            Harga
                        </label>


                        <input
                            type="number"
                            name="price"
                            min="0"
                            required
                            placeholder="0">

                    </div>


                    <div class="form-group">

                        <label>
                            Satuan
                        </label>


                        <input
                            type="text"
                            name="unit"
                            placeholder="lembar / m² / pcs">

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="form-group">

                    <label>
                        Status
                    </label>


                    <select name="status">

                        <option value="active">
                            Aktif
                        </option>

                        <option value="inactive">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>


            {{-- MODAL FOOTER --}}
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-outline"
                    onclick="closePriceModal()">

                    Batal

                </button>


                <button
                    type="submit"
                    class="btn btn-primary">

                    <i data-lucide="save"></i>

                    Simpan Harga

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    MODAL DETAIL
========================================================== --}}
<div class="modal-overlay"
     id="detailModal">


    <div class="master-modal">


        <div class="modal-header">

            <div>

                <span class="modal-icon">

                    <i data-lucide="eye"></i>

                </span>


                <div>

                    <h3>
                        Detail Harga
                    </h3>

                    <p>
                        Informasi harga material.
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeDetailModal()"
                class="modal-close">

                <i data-lucide="x"></i>

            </button>

        </div>


        <div class="modal-body">

            <div class="detail-row">

                <span>
                    Kode Material
                </span>

                <strong id="detailCode">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Material
                </span>

                <strong id="detailMaterial">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Kategori
                </span>

                <strong id="detailCategory">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Harga
                </span>

                <strong id="detailPrice">
                    -
                </strong>

            </div>


            <div class="detail-row">

                <span>
                    Satuan
                </span>

                <strong id="detailUnit">
                    -
                </strong>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn btn-outline"
                onclick="closeDetailModal()">

                Tutup

            </button>

        </div>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================== --}}
<script>

    /* =========================================================
       LUCIDE
    ========================================================== */

    lucide.createIcons();


    /* =========================================================
       TAMBAH HARGA MODAL
    ========================================================== */

    function openPriceModal()
    {
        const modal =
            document.getElementById('priceModal');

        if(modal)
        {
            modal.classList.add('show');
        }
    }


    function closePriceModal()
    {
        const modal =
            document.getElementById('priceModal');

        if(modal)
        {
            modal.classList.remove('show');
        }
    }


    /* =========================================================
       DETAIL
    ========================================================== */

    function showPriceDetail(
        material,
        code,
        category,
        price,
        unit
    )
    {
        document.getElementById('detailMaterial')
            .textContent = material;

        document.getElementById('detailCode')
            .textContent = code;

        document.getElementById('detailCategory')
            .textContent = category;

        document.getElementById('detailPrice')
            .textContent =
                'Rp ' +
                Number(price).toLocaleString('id-ID');

        document.getElementById('detailUnit')
            .textContent = unit;


        document
            .getElementById('detailModal')
            .classList.add('show');
    }


    function closeDetailModal()
    {
        document
            .getElementById('detailModal')
            .classList.remove('show');
    }


    /* =========================================================
       SEARCH
    ========================================================== */

    function searchHarga()
    {
        const input =
            document.getElementById('hargaSearch');

        const filter =
            input.value.toLowerCase();

        const table =
            document.getElementById('hargaTable');

        const rows =
            table.querySelectorAll('tbody tr');


        rows.forEach(row =>
        {
            const text =
                row.textContent.toLowerCase();

            row.style.display =
                text.includes(filter)
                    ? ''
                    : 'none';
        });
    }


    /* =========================================================
       GLOBAL SEARCH
    ========================================================== */

    const globalSearch =
        document.getElementById('globalSearch');


    if(globalSearch)
    {
        globalSearch.addEventListener(
            'keyup',
            function()
            {
                const filter =
                    this.value.toLowerCase();

                document
                    .querySelectorAll('#hargaTable tbody tr')
                    .forEach(row =>
                    {
                        row.style.display =
                            row.textContent
                                .toLowerCase()
                                .includes(filter)
                                    ? ''
                                    : 'none';
                    });
            }
        );
    }


    /* =========================================================
       DROPDOWN
    ========================================================== */

    function toggleDropdown(event, id)
    {
        event.stopPropagation();


        document
            .querySelectorAll('.dropdown-menu-action')
            .forEach(menu =>
            {
                if(menu.id !== id)
                {
                    menu.classList.remove('show');
                }
            });


        const menu =
            document.getElementById(id);


        if(menu)
        {
            menu.classList.toggle('show');
        }
    }


    window.addEventListener(
        'click',
        function()
        {
            document
                .querySelectorAll('.dropdown-menu-action')
                .forEach(menu =>
                {
                    menu.classList.remove('show');
                });
        }
    );


    /* =========================================================
       DELETE
    ========================================================== */

    function deletePrice(id)
    {
        if(!confirm(
            'Apakah kamu yakin ingin menghapus data harga ini?'
        ))
        {
            return;
        }


        /*
         * Belum melakukan request DELETE karena
         * route delete belum ditentukan.
         *
         * Setelah route/controller dibuat,
         * bagian ini tinggal diarahkan ke route tersebut.
         */

        alert(
            'Data ID ' +
            id +
            ' siap dihapus setelah route delete dibuat.'
        );
    }


    /* =========================================================
       EDIT
    ========================================================== */

    function editPrice(id)
    {
        /*
         * Tombol edit sementara diarahkan ke route
         * jika route masterharga.price.edit tersedia.
         */

        const editUrl =
            @json(Route::has('masterharga.price.edit')
                ? route('masterharga.price.edit', ':id')
                : '');

        if(editUrl)
        {
            window.location.href =
                editUrl.replace(':id', id);

            return;
        }


        alert(
            'Route edit harga belum dibuat.'
        );
    }


    /* =========================================================
       CHECK ALL
    ========================================================== */

    const checkAll =
        document.getElementById('checkAll');


    if(checkAll)
    {
        checkAll.addEventListener(
            'change',
            function()
            {
                document
                    .querySelectorAll('.row-check')
                    .forEach(
                        checkbox =>
                        checkbox.checked =
                            this.checked
                    );
            }
        );
    }


    /* =========================================================
       THEME
    ========================================================== */

    function changeTheme()
    {
        document.body.classList.toggle(
            'pink-theme'
        );


        localStorage.setItem(
            'dhd-theme',

            document.body.classList.contains(
                'pink-theme'
            )
                ? 'pink'
                : 'default'
        );
    }


    document.addEventListener(
        'DOMContentLoaded',
        function()
        {
            if(
                localStorage.getItem('dhd-theme')
                ===
                'pink'
            )
            {
                document.body.classList.add(
                    'pink-theme'
                );
            }
        }
    );


    /* =========================================================
       DATE & TIME
    ========================================================== */

    function updateDateTime()
    {
        const now =
            new Date();


        const dateElement =
            document.getElementById(
                'currentDate'
            );

        const timeElement =
            document.getElementById(
                'currentTime'
            );


        if(dateElement)
        {
            dateElement.textContent =
                now.toLocaleDateString(
                    'id-ID',
                    {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }
                );
        }


        if(timeElement)
        {
            timeElement.textContent =
                now.toLocaleTimeString(
                    'id-ID',
                    {
                        hour: '2-digit',
                        minute: '2-digit'
                    }
                ) + ' WIB';
        }
    }


    updateDateTime();

    setInterval(
        updateDateTime,
        1000
    );


    /* =========================================================
       CLOSE MODAL KLIK LUAR
    ========================================================== */

    document
        .querySelectorAll('.modal-overlay')
        .forEach(modal =>
        {
            modal.addEventListener(
                'click',
                function(event)
                {
                    if(
                        event.target === modal
                    )
                    {
                        modal.classList.remove(
                            'show'
                        );
                    }
                }
            );
        });

</script>

</body>
</html>