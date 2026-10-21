<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Tambah Material</title>

    @vite(['resources/css/inventori.css'])

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
</aside>
                {{-- HEADER PAGE --}}
    <main class="main-content">
        <section class="content">
                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ Route::has('materials.index') ? route('materials.index') : (Route::has('inventori.index') ? route('inventori.index') : '#') }}">Inventori</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Tambah Material Baru</span>
                        </div>
                        <h1>Tambah Material Baru</h1>
                        <p>Isi formulir di bawah ini untuk menambahkan data material ke dalam sistem.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ Route::has('materials.index') ? route('materials.index') : (Route::has('inventori.index') ? route('inventori.index') : '#') }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- FORM CREATE MATERIAL --}}
              <form action="{{ route('materials.store') }}" method="POST" class="form-card">
                    @csrf

                    <div class="form-body">

                        {{-- SEKSI 1: INFORMASI UTAMA --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="package"></i>
                                Informasi Dasar Material
                            </h3>

                            <div class="form-grid">

                                {{-- Code --}}
                                <div class="form-group">
                                    <label for="code">Kode Material (Code) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="code" 
                                        name="code" 
                                        value="{{ old('code') }}" 
                                        placeholder="Contoh: HPL-001" 
                                        required
                                    >
                                    @error('code')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Name --}}
                                <div class="form-group">
                                    <label for="name">Nama Material (Name) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        value="{{ old('name') }}" 
                                        placeholder="Contoh: HPL TACO TH-001" 
                                        required
                                    >
                                    @error('name')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Category ID --}}
                                <div class="form-group">
                                    <label for="category_id">Kategori (Category) <span class="required">*</span></label>
                                    <select id="category_id" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Brand ID --}}
                                <div class="form-group">
                                    <label for="brand_id">Brand / Merk <span class="required">*</span></label>
                                    <select id="brand_id" name="brand_id" required>
                                        <option value="">-- Pilih Brand --</option>
                                        @foreach($brands ?? [] as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Series --}}
                                <div class="form-group col-span-2">
                                    <label for="series">Seri (Series) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="series" 
                                        name="series" 
                                        value="{{ old('series') }}" 
                                        placeholder="Contoh: Solid Color / Woodgrain / Metal"
                                        required
                                    >
                                    @error('series')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 2: SPESIFIKASI & TAMPILAN --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="sliders"></i>
                                Spesifikasi & Atribut Material
                            </h3>

                            <div class="form-grid">

                                {{-- Specification --}}
                                <div class="form-group col-span-2">
                                    <label for="specification">Spesifikasi Detail (Specification) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="specification" 
                                        name="specification" 
                                        value="{{ old('specification') }}" 
                                        placeholder="Contoh: High Pressure Laminate 0.8mm"
                                        required
                                    >
                                    @error('specification')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Size --}}
                                <div class="form-group">
                                    <label for="size">Ukuran (Size) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="size" 
                                        name="size" 
                                        value="{{ old('size') }}" 
                                        placeholder="Contoh: 1220mm x 2440mm / 0.8mm"
                                        required
                                    >
                                    @error('size')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Color --}}
                                <div class="form-group">
                                    <label for="color">Warna (Color) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="color" 
                                        name="color" 
                                        value="{{ old('color') }}" 
                                        placeholder="Contoh: Warm White, Natural Oak"
                                        required
                                    >
                                    @error('color')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Motif --}}
                                <div class="form-group">
                                    <label for="motif">Motif <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="motif" 
                                        name="motif" 
                                        value="{{ old('motif') }}" 
                                        placeholder="Contoh: Kayu Serat, Polos, Marmer"
                                        required
                                    >
                                    @error('motif')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Finish --}}
                                <div class="form-group">
                                    <label for="finish">Finishing (Finish) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="finish" 
                                        name="finish" 
                                        value="{{ old('finish') }}" 
                                        placeholder="Contoh: Matte, Glossy, Texture"
                                        required
                                    >
                                    @error('finish')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        {{-- SEKSI 3: SATUAN, STOK & DESKRIPSI --}}
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="archive"></i>
                                Satuan, Minimum Stok & Deskripsi
                            </h3>

                            <div class="form-grid">

                                {{-- Unit --}}
                                <div class="form-group">
                                    <label for="unit">Satuan (Unit) <span class="required">*</span></label>
                                    <input 
                                        type="text" 
                                        id="unit" 
                                        name="unit" 
                                        value="{{ old('unit') }}" 
                                        placeholder="Contoh: Lembar, Roll, Pcs, Set, Kaleng"
                                        maxlength="30"
                                        required
                                    >
                                    @error('unit')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Minimum Stock --}}
                                <div class="form-group">
                                    <label for="minimum_stock">Minimum Stok (Minimum Stock) <span class="required">*</span></label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="minimum_stock" 
                                        name="minimum_stock" 
                                        value="{{ old('minimum_stock', '5.00') }}" 
                                        placeholder="5.00"
                                        required
                                    >
                                    @error('minimum_stock')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="form-group col-span-2">
                                    <label for="description">Deskripsi / Keterangan (Description) <span class="required">*</span></label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        rows="4" 
                                        placeholder="Tuliskan penjelasan detail atau catatan khusus mengenai material ini..."
                                        required
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- FORM FOOTER / BUTTONS --}}
                    <div class="form-footer">
                        <a href="{{ Route::has('materials.index') ? route('materials.index') : (Route::has('inventori.index') ? route('inventori.index') : '#') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Simpan Material
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
            dateElement.textContent = now.toLocaleDateString('en-US', {
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