<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHD Interior - @yield('title', 'Pembelian & Stok')</title>
    @vite(['resources/css/pembelianstok.css', 'resources/css/pembelianstok-form.css'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">DHD</div>
        <div class="brand-text"><strong>Interior</strong><span>Management System</span></div>
    </div>
    <nav class="sidebar-menu">
        <div class="menu-group">
            <a href="{{ route('inventori.index') }}" class="menu-item menu-toggle menu-parent-active">
                <i data-lucide="package"></i><span>Inventori &amp; Stok</span>
            </a>
            <div class="submenu open">
                <a href="{{ route('masterharga.index') }}">Master Harga</a>
                <a href="{{ route('supplier.index') }}">Supplier</a>
                <a href="{{ route('pembelianstok.index') }}" class="active">Pembelian &amp; Stok</a>
                <a href="{{ route('stokgudang.index') }}">Stok Gudang</a>
            </div>
        </div>
        <a href="{{ route('laporan.index') }}" class="menu-item"><i data-lucide="file-text"></i><span>Laporan</span></a>
    </nav>
    <div class="sidebar-footer">
        <div class="orange-line"></div>
        <p>DHD Interior<br>Management System</p>
        <div class="footer-actions">
            <button type="button" class="theme-button" onclick="changeTheme()"><i data-lucide="palette"></i><span>Change Theme</span></button>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="logout-button"><i data-lucide="log-out"></i><span>Logout</span></button></form>
        </div>
    </div>
</aside>

<main class="main-content">
    <header class="topbar">
        <div class="search-box"><i data-lucide="search"></i><input id="globalSearch" type="search" placeholder="Cari invoice, supplier, material, atau proyek..."></div>
        <div class="topbar-right">
            <div class="date-info"><i data-lucide="calendar-days"></i><div><strong id="currentDate"></strong><span id="currentTime"></span></div></div>
            <button class="notification-button" type="button"><i data-lucide="bell"></i><span class="notification-dot"></span></button>
            <div class="admin-profile"><div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'ST', 0, 2)) }}</div><span>{{ auth()->user()->name ?? 'Admin' }}</span><i data-lucide="chevron-down"></i></div>
        </div>
    </header>

    <section class="content purchase-page">
        @yield('content')
    </section>
</main>

<script>
    if (window.lucide) lucide.createIcons();

    function updateClock() {
        const now = new Date();
        const date = document.getElementById('currentDate');
        const time = document.getElementById('currentTime');
        if (date) date.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
        if (time) time.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
    }
    updateClock();
    setInterval(updateClock, 1000);

    function changeTheme() {
        document.body.classList.toggle('pink-theme');
        localStorage.setItem('dhd-theme', document.body.classList.contains('pink-theme') ? 'pink' : 'default');
    }
    if (localStorage.getItem('dhd-theme') === 'pink') document.body.classList.add('pink-theme');
</script>
@stack('scripts')
</body>
</html>