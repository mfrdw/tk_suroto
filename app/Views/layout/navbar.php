<nav class="navbar navbar-expand-xl bg-white navbar-light shadow-sm sticky-top marketplace-navbar">
    <div class="container">

        <a class="navbar-brand marketplace-brand me-3" href="<?= base_url('/') ?>">
            <span class="brand-logo">
                <i class="fa-solid fa-bag-shopping"></i>
            </span>
            <span class="brand-text-wrap">
                <span class="brand-title"><?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?></span>
                <small>Belanja mudah, cepat, terpercaya</small>
            </span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav nav-market me-xl-3 mb-3 mb-xl-0">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('/') ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('produk') ?>">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('tentang') ?>">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('kontak') ?>">Kontak</a>
                </li>
            </ul>

            <form action="<?= base_url('produk') ?>" method="get" class="market-search ms-xl-auto me-xl-3 mb-3 mb-xl-0">
                <div class="input-group">
                    <span class="input-group-text search-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" class="form-control" name="q" placeholder="Cari produk, kategori, merek..."
                        value="<?= esc($_GET['q'] ?? '') ?>">
                    <button class="btn btn-primary search-btn" type="submit">
                        Cari
                    </button>
                </div>
            </form>

            <div class="market-actions d-flex align-items-center gap-2">
                <a href="<?= base_url('auth/login') ?>" class="btn market-btn-soft market-btn-pill">
                    <i class="fa-regular fa-user"></i>
                    <span>Akun</span>
                </a>

                <a href="<?= base_url('keranjang') ?>"
                    class="btn market-btn-soft market-btn-pill cart-btn position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Keranjang</span>

                    <?php if (($cartCount ?? 0) > 0): ?>
                    <span class="cart-badge"><?= esc($cartCount ?? 0) ?></span>
                    <?php endif; ?>
                </a>
            </div>

        </div>
    </div>
</nav>