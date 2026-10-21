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
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">DHD</div>
            <div class="brand-text">
                <strong>Interior</strong>
                <span>Management System</span>
            </div>
        </div>
        <nav class="sidebar-menu">
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-content">
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari proyek, barang, supplier...">
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

        <section class="content">
            <div class="inventory-page">

                {{-- HEADER PAGE --}}
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ route('supplier.index') }}">Supplier</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Edit Supplier</span>
                        </div>
                        <h1>Edit Supplier: {{ $supplier->name }}</h1>
                        <p>Perbarui informasi detail data supplier di bawah ini.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ route('supplier.index') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- FORM EDIT SUPPLIER --}}
                <form action="{{ route('supplier.update', $supplier->id) }}" method="POST" class="form-card">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI UTAMA SUPPLIER --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="truck"></i>
                                Informasi Identitas Supplier
                            </h3>

                            <div class="form-grid">

                                {{-- Name --}}
                                <div class="form-group col-span-2">
                                    <label for="name">Nama Supplier / Perusahaan <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name', $supplier->name) }}" 
                                        placeholder="Contoh: PT. Kayu Jaya Abadi" 
                                        required
                                    >
                                    @error('name')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Contact Person --}}
                                <div class="form-group">
                                    <label for="contact_person">Contact Person (Sales / Penanggung Jawab)</label>
                                    <input 
                                        type="text" 
                                        id="contact_person" 
                                        name="contact_person" 
                                        value="{{ old('contact_person', $supplier->contact_person) }}" 
                                        placeholder="Contoh: Pak Budi / Ibu Siska"
                                    >
                                    @error('contact_person')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="form-group">
                                    <label for="phone">No. Telepon / WhatsApp</label>
                                    <input 
                                        type="text" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone', $supplier->phone) }}" 
                                        placeholder="Contoh: 08123456789"
                                    >
                                    @error('phone')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 2: KONTAK & ALAMAT --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="map-pin"></i>
                                Email & Alamat Operasional
                            </h3>

                            <div class="form-grid">

                                {{-- Email --}}
                                <div class="form-group col-span-2">
                                    <label for="email">Email Resmi</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $supplier->email) }}" 
                                        placeholder="Contoh: sales@kayujaya.com"
                                    >
                                    @error('email')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="form-group col-span-2">
                                    <label for="address">Alamat Lengkap Kantor / Gudang Supplier</label>
                                    <textarea 
                                        id="address" 
                                        name="address" 
                                        rows="4" 
                                        placeholder="Tuliskan jalan, nomor, kecamatan, kota dan lokasi fisik supplier..."
                                    >{{ old('address', $supplier->address) }}</textarea>
                                    @error('address')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ route('supplier.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Perbarui Supplier
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
        if (document.body.classList.contains('pink-theme')) {
            localStorage.setItem('dhd-theme', 'pink');
        } else {
            localStorage.setItem('dhd-theme', 'default');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const theme = localStorage.getItem('dhd-theme');
        if (theme === 'pink') {
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