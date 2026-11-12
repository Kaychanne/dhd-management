
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Edit Barang</title>

    @vite(['resources/css/masterharga.css'])
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
            <input type="text" placeholder="Cari material...">
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

            {{-- HEADER --}}
            <div class="master-header">

                <div class="master-title">

                    <h1>
                        <i data-lucide="package-plus"></i>
                        Edit Barang
                    </h1>

                    <p>
                        Perbarui informasi barang dan transaksi stok awal
                        di dalam inventori gudang.
                    </p>

                </div>

            </div>


            {{-- NOTIFICATION --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Periksa kembali data berikut:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- FORM --}}
            <div class="table-card" style="padding:25px;">

                <div class="table-title" style="margin-bottom:25px;">
                    <i data-lucide="clipboard-list"></i>
                    <h2>Informasi Barang Gudang</h2>
                </div>

                <form method="POST"
                      action="{{ route('stokgudang.update', $material->id) }}">

                    @csrf
                    @method('PUT')


                    {{-- INFORMASI BARANG --}}
                    <h3 style="margin-bottom:20px;">
                        Informasi Barang
                    </h3>

                    <div class="form-row">

                        <div class="form-group">
                            <label>Kode Barang *</label>

                            <input type="text"
                                   name="code"
                                   value="{{ old('code', $material->code) }}"
                                   placeholder="Contoh: BRG-001"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang *</label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $material->name) }}"
                                   placeholder="Masukkan nama barang"
                                   required>
                        </div>

                    </div>


                    <div class="form-row">

                    <div class="form-row">

                        {{-- KATEGORI --}}
                        <div class="form-group">
                            <label>Kategori *</label>

                            <select name="category_id" required>
                                <option value="">Pilih Kategori</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $material->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- NAMA MATERIAL --}}
                        <div class="form-group">
                            <label>Nama Material</label>

                            <input
                                type="text"
                                value="{{ $material->name }}"
                                readonly
                            >

                            <small>Material yang sedang kamu edit.</small>
                        </div>

                    </div>

                    {{-- BRAND --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label>Brand</label>

                            <input
                                type="text"
                                value="{{ $material->brand->name ?? '-' }}"
                                readonly
                            >

                            <small>Brand mengikuti data material.</small>
                        </div>
                    </div>
                    <div class="form-row">

                        <div class="form-group">
                            <label>Spesifikasi</label>

                            <input type="text"
                                   name="specification"
                                   value="{{ old('specification', $material->specification) }}"
                                   placeholder="Spesifikasi barang">
                        </div>

                        <div class="form-group">
                            <label>Satuan *</label>

                            <input type="text"
                                   name="unit"
                                   value="{{ old('unit', $material->unit) }}"
                                   placeholder="Contoh: Unit, Pcs, Meter"
                                   required>
                        </div>

                    </div>


                    <div class="form-group">
                        <label>Deskripsi Barang</label>

                        <textarea name="description"
                                  rows="3"
                                  placeholder="Keterangan barang...">{{ old('description', $material->description) }}</textarea>
                    </div>


                    <hr style="margin:25px 0; opacity:.2;">


                    {{-- INFORMASI STOK --}}
                    <h3 style="margin-bottom:20px;">
                        Informasi Stok Awal
                    </h3>

                    @if(!$initialTransaction)
                        <div class="alert alert-danger">
                            Belum ada transaksi barang masuk untuk material ini.
                            Isi jumlah stok dan informasi barang masuk untuk
                            membuat transaksi awal.
                        </div>
                    @endif

                    <div class="form-row">

                        <div class="form-group">
                            <label>Jumlah Stok Awal *</label>

                            <input type="number"
                                   name="quantity"
                                   min="0.01"
                                   step="0.01"
                                   value="{{ old('quantity', $initialTransaction->quantity ?? '') }}"
                                   placeholder="Masukkan jumlah stok"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Minimum Stok *</label>

                            <input type="number"
                                   name="minimum_stock"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('minimum_stock', $material->minimum_stock) }}"
                                   placeholder="Batas minimum stok"
                                   required>
                        </div>

                    </div>


                    <hr style="margin:25px 0; opacity:.2;">


                    {{-- INFORMASI PEMBELIAN --}}
                    <h3 style="margin-bottom:20px;">
                        Informasi Barang Masuk
                    </h3>


                    
                    <div class="form-row">

                        <div class="form-group">
                            <label>Supplier *</label>

                            <select name="supplier_id" required>
                                <option value="">Pilih Supplier</option>

                                @foreach($suppliers as $supplier)
                                    <option
                                        value="{{ $supplier->id }}"
                                        {{ old(
                                            'supplier_id',
                                            $initialTransaction->supplier_id ?? ''
                                        ) == $supplier->id ? 'selected' : '' }}
                                    >
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Harga Satuan *</label>

                            <input
                                type="number"
                                name="unit_price"
                                min="0"
                                step="0.01"
                                value="{{ old(
                                    'unit_price',
                                    $initialTransaction->unit_price ?? ''
                                ) }}"
                                placeholder="Masukkan harga satuan"
                                required
                            >
                        </div>

                    </div>



                    <div class="form-row">

                        <div class="form-group">
                            <label>Tanggal Barang Masuk *</label>

                            <input type="date"
                                   name="transaction_date"
                                   value="{{ old('transaction_date', $initialTransaction->transaction_date ?? date('Y-m-d')) }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label>Catatan</label>

                            <input type="text"
                                   name="notes"
                                   value="{{ old('notes', $initialTransaction->notes ?? '') }}"
                                   placeholder="Catatan tambahan...">
                        </div>

                    </div>


                    {{-- INFO --}}
                    <div style="padding:15px;background:#f1f5f9;border-radius:8px;margin-top:20px;">
                        <strong>Informasi:</strong>

                        <p style="margin:8px 0 0;color:#64748b;font-size:13px;">
                            Perubahan stok awal dan harga akan memperbarui
                            transaksi barang masuk pertama. Transaksi barang
                            masuk dan keluar lainnya tidak diubah.
                        </p>
                    </div>


                    {{-- BUTTON --}}
                    <div class="modal-footer"
                         style="margin-top:30px;">

                        <a href="{{ route('stokgudang.index') }}"
                           class="btn btn-outline"
                           style="text-decoration:none;">
                            Batal
                        </a>

                        <button type="submit"
                                class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>

</main>


{{-- JAVASCRIPT --}}
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

</script>

</body>
</html>
