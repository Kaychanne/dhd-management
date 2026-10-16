<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DHD Interior - Edit Material</title>

    @vite(['resources/css/materials-create.css'])

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
            <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="menu-item">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}" class="menu-item active">
                <i data-lucide="package"></i>
                <span>Inventori & Stok</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="search-box">
                <i data-lucide="search"></i>
                <input type="text" placeholder="Cari proyek, barang, supplier...">
            </div>
            <div class="topbar-right">
                <div class="admin-profile">
                    <div class="admin-avatar">ST</div>
                    <span>Steven</span>
                </div>
            </div>
        </header>

        <section class="content">
            <div class="inventory-page">

                <div class="inventory-header">
                    <div class="inventory-title">
                        <div class="breadcrumb">
                            <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}">Inventori</a>
                            <i data-lucide="chevron-right"></i>
                            <span>Edit Material</span>
                        </div>
                        <h1>Edit Material: {{ $material->name }}</h1>
                        <p>Ubah informasi detail material di bawah ini.</p>
                    </div>

                    <div class="header-action">
                        <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}" class="btn btn-secondary">
                            <i data-lucide="arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>

              
                <form action="{{ Route::has('materials.update') ? route('materials.update', $material->id) : url('/materials/' . $material->id) }}" method="POST" class="form-card">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                   
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="package"></i>
                                Informasi Dasar Material
                            </h3>

                            <div class="form-grid">
                     
                                <div class="form-group">
                                    <label for="code">Kode Material (Code) <span class="required">*</span></label>
                                    <input type="text" id="code" name="code" value="{{ old('code', $material->code) }}" required>
                                    @error('code') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                           
                                <div class="form-group">
                                    <label for="name">Nama Material (Name) <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $material->name) }}" required>
                                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                        
                                <div class="form-group">
                                    <label for="category_id">Kategori <span class="required">*</span></label>
                                    <select id="category_id" name="category_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories ?? [] as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $material->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="brand_id">Brand / Merk <span class="required">*</span></label>
                                    <select id="brand_id" name="brand_id" required>
                                        <option value="">-- Pilih Brand --</option>
                                        @foreach($brands ?? [] as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $material->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="series">Seri (Series) <span class="required">*</span></label>
                                    <input type="text" id="series" name="series" value="{{ old('series', $material->series) }}" required>
                                    @error('series') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

         
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="sliders"></i>
                                Spesifikasi & Atribut
                            </h3>

                            <div class="form-grid">
                   
                                <div class="form-group col-span-2">
                                    <label for="specification">Spesifikasi Detail <span class="required">*</span></label>
                                    <input type="text" id="specification" name="specification" value="{{ old('specification', $material->specification) }}" required>
                                    @error('specification') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                          
                                <div class="form-group">
                                    <label for="size">Ukuran (Size) <span class="required">*</span></label>
                                    <input type="text" id="size" name="size" value="{{ old('size', $material->size) }}" required>
                                    @error('size') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                        
                                <div class="form-group">
                                    <label for="color">Warna (Color) <span class="required">*</span></label>
                                    <input type="text" id="color" name="color" value="{{ old('color', $material->color) }}" required>
                                    @error('color') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                        
                                <div class="form-group">
                                    <label for="motif">Motif <span class="required">*</span></label>
                                    <input type="text" id="motif" name="motif" value="{{ old('motif', $material->motif) }}" required>
                                    @error('motif') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                         
                                <div class="form-group">
                                    <label for="finish">Finishing <span class="required">*</span></label>
                                    <input type="text" id="finish" name="finish" value="{{ old('finish', $material->finish) }}" required>
                                    @error('finish') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                 
                        <div class="form-section">
                            <h3 class="section-title">
                                <i data-lucide="archive"></i>
                                Satuan, Stok & Deskripsi
                            </h3>

                            <div class="form-grid">
                    
                                <div class="form-group">
                                    <label for="unit">Satuan (Unit) <span class="required">*</span></label>
                                    <input type="text" id="unit" name="unit" value="{{ old('unit', $material->unit) }}" required>
                                    @error('unit') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="stok">Jumlah Stok Saat Ini <span class="required">*</span></label>
                                    <input type="number" id="stok" name="stok" value="{{ old('stok', $material->stok) }}" required>
                                    @error('stok') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="minimum_stock">Minimum Stok (Peringatan) <span class="required">*</span></label>
                                    <input type="number" step="0.01" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $material->minimum_stock) }}" required>
                                    @error('minimum_stock') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="description">Deskripsi <span class="required">*</span></label>
                                    <textarea id="description" name="description" rows="4" required>{{ old('description', $material->description) }}</textarea>
                                    @error('description') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    </div>

         
                    <div class="form-footer">
                        <a href="{{ Route::has('inventori.index') ? route('inventori.index') : '#' }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i>
                            Perbarui Material
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </main>
</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>