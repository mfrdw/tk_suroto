<div class="market-topbar">
    <div class="container">
        <div class="row align-items-center gy-2">
            <div class="col-lg-6">
                <div class="d-flex flex-wrap align-items-center gap-3 small">
                    <span class="text-muted">
                        <i class="fa-solid fa-store me-1 text-primary"></i>
                        Selamat datang di
                        <strong><?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?></strong>
                    </span>
                    <span class="topbar-divider d-none d-md-inline"></span>
                    <span class="text-success fw-semibold">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        100% Produk Pilihan
                    </span>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="d-flex flex-wrap justify-content-lg-end align-items-center gap-3 small">
                    <a href="#" class="topbar-link">
                        <i class="fa-solid fa-tags me-1"></i>
                        Promo Hari Ini
                    </a>
                    <a href="#" class="topbar-link">
                        <i class="fa-solid fa-phone me-1"></i>
                        Kontak
                    </a>
                    <a href="#" class="topbar-link">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Lokasi
                    </a>
                    <?php if (session()->get('logged_in')): ?>
                    <span class="topbar-link fw-semibold text-dark">
                        <i class="fa-regular fa-user me-1 text-primary"></i>
                        <?= esc(session()->get('email') ?? '') ?>
                    </span>
                    <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="topbar-link fw-semibold text-dark">
                        <i class="fa-regular fa-user me-1 text-primary"></i>
                        Login
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>