<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php
$heroImage = 'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=1200&q=80';

$productPlaceholders = [
    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1585386959984-a41552231658?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=800&q=80',
];
?>

<div class="market-home py-4">
    <div class="container">

        <!-- HERO -->
        <section class="hero-market mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <div class="hero-copy">
                        <span class="hero-label">
                            <i class="fa-solid fa-bolt me-2"></i>
                            Marketplace Modern untuk Belanja Harian
                        </span>

                        <h1 class="hero-title mt-3">
                            Belanja Produk Favoritmu
                            <span>Lebih Cepat, Hemat, dan Aman</span>
                        </h1>

                        <p class="hero-desc">
                            Temukan berbagai produk unggulan dari
                            <strong><?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?></strong>
                            dengan tampilan modern, harga bersahabat, dan pengalaman belanja yang nyaman.
                        </p>

                        <div class="hero-cta d-flex flex-wrap gap-3 mt-4">
                            <a href="<?= base_url('produk') ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fa-solid fa-bag-shopping me-2"></i>
                                Mulai Belanja
                            </a>
                            <a href="<?= base_url('kontak') ?>" class="btn btn-outline-dark btn-lg rounded-pill px-4">
                                <i class="fa-solid fa-headset me-2"></i>
                                Hubungi Kami
                            </a>
                        </div>

                        <div class="hero-badges mt-4">
                            <span><i class="fa-solid fa-shield-halved"></i> Transaksi Aman</span>
                            <span><i class="fa-solid fa-truck-fast"></i> Pengiriman Cepat</span>
                            <span><i class="fa-solid fa-star"></i> Produk Pilihan</span>
                        </div>

                        <div class="hero-stats row g-3 mt-4">
                            <div class="col-4">
                                <div class="stat-box">
                                    <h4><?= count($produk ?? []) ?>+</h4>
                                    <p>Produk</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <h4><?= count($kategori ?? []) ?>+</h4>
                                    <p>Kategori</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-box">
                                    <h4>24/7</h4>
                                    <p>Support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-visual">
                        <div class="hero-image-wrap">
                            <img src="<?= $heroImage ?>" alt="Banner toko online modern" class="img-fluid">
                        </div>

                        <div class="floating-card floating-rating">
                            <small>Rating Toko</small>
                            <strong>4.9/5</strong>
                            <span><i class="fa-solid fa-star text-warning"></i> Pelanggan puas</span>
                        </div>

                        <div class="floating-card floating-order">
                            <small>Pesanan</small>
                            <strong>1.2K+</strong>
                            <span><i class="fa-solid fa-box text-primary"></i> Terkirim</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- KATEGORI CEPAT -->
        <?php if (!empty($kategori)): ?>
        <section class="market-section mb-4">
            <div class="section-head mb-3">
                <div>
                    <h2>Kategori Populer</h2>
                    <p>Pilih kategori favorit dan temukan produk yang kamu butuhkan</p>
                </div>
                <a href="<?= base_url('produk') ?>" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua</a>
            </div>

            <div class="row g-3">
                <?php foreach ($kategori as $kat): ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="<?= base_url('produk?kategori=' . $kat['id_kategori']) ?>" class="text-decoration-none">
                        <div class="category-market-card h-100">
                            <div class="category-market-icon">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <h6><?= esc($kat['nama_kategori']) ?></h6>
                            <small>Produk pilihan terbaik</small>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- STRIP PROMO -->
        <section class="promo-strip mb-4">
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="promo-main-box">
                        <div>
                            <span class="promo-badge">FLASH DEAL</span>
                            <h3>Promo Spesial Marketplace</h3>
                            <p>Diskon menarik untuk berbagai produk pilihan setiap hari.</p>
                            <a href="<?= base_url('produk') ?>" class="btn btn-light rounded-pill px-4">
                                Belanja Sekarang
                            </a>
                        </div>
                        <div class="promo-icon">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="promo-side-box">
                        <span class="promo-badge promo-badge-dark">GRATIS ONGKIR</span>
                        <h4>Belanja makin hemat</h4>
                        <p>Nikmati pengalaman belanja yang cepat dan efisien.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- USP -->
        <section class="market-section mb-4">
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-market-card h-100">
                        <div class="feature-market-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h6>Pengiriman Cepat</h6>
                        <p>Produk diproses dan dikirim lebih cepat dengan layanan terpercaya.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-market-card h-100">
                        <div class="feature-market-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h6>Pembayaran Aman</h6>
                        <p>Belanja lebih tenang dengan sistem transaksi yang aman dan terpercaya.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-market-card h-100">
                        <div class="feature-market-icon">
                            <i class="fa-solid fa-medal"></i>
                        </div>
                        <h6>Kualitas Terjamin</h6>
                        <p>Produk dipilih dengan kualitas terbaik untuk kepuasan pelanggan.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-market-card h-100">
                        <div class="feature-market-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h6>Support Responsif</h6>
                        <p>Tim siap membantu kebutuhan dan pertanyaan pelanggan dengan cepat.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUK TERBARU -->
        <section class="market-section mb-5">
            <div class="section-head mb-3">
                <div>
                    <h2>Produk Terbaru</h2>
                    <p>Produk pilihan dengan tampilan marketplace modern dan informatif</p>
                </div>
                <a href="<?= base_url('produk') ?>" class="btn btn-outline-primary rounded-pill px-4">Semua Produk</a>
            </div>

            <div class="row g-4">
                <?php if (!empty($produk)): ?>
                <?php foreach ($produk as $i => $item): ?>
                <?php
                            $harga = (int)($item['harga'] ?? 0);
                            $diskonPersen = [10, 15, 20, 5][$i % 4];
                            $hargaCoret = $harga > 0 ? $harga + round($harga * ($diskonPersen / 100)) : 0;
                            $rating = number_format(4.7 + (($i % 3) * 0.1), 1);
                            $terjual = 20 + ($i * 7);
                            $productImage = $productPlaceholders[$i % count($productPlaceholders)];
                        ?>
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="card market-product-card h-100 border-0">
                        <div class="market-product-thumb">
                            <a href="<?= base_url('produk/' . $item['slug_produk']) ?>">
                                <img src="<?= $productImage ?>" alt="<?= esc($item['nama_produk']) ?>"
                                    class="card-img-top">
                            </a>

                            <div class="product-badges">
                                <span class="badge bg-danger">-<?= $diskonPersen ?>%</span>
                                <span class="badge bg-dark-subtle text-dark">Baru</span>
                            </div>

                            <button type="button" class="wishlist-btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="product-category-badge">
                                    <?= esc($item['nama_kategori'] ?? 'Kategori') ?>
                                </span>
                            </div>

                            <h5 class="product-name">
                                <a href="<?= base_url('produk/' . $item['slug_produk']) ?>">
                                    <?= esc($item['nama_produk']) ?>
                                </a>
                            </h5>

                            <div class="product-meta mb-2">
                                <span><i class="fa-solid fa-star text-warning"></i> <?= $rating ?></span>
                                <span class="dot"></span>
                                <span><?= $terjual ?> terjual</span>
                            </div>

                            <div class="product-price-wrap mb-3">
                                <?php if ($hargaCoret > $harga): ?>
                                <div class="old-price">
                                    Rp <?= number_format($hargaCoret, 0, ',', '.') ?>
                                </div>
                                <?php endif; ?>

                                <div class="current-price">
                                    Rp <?= number_format($harga, 0, ',', '.') ?>
                                </div>
                            </div>

                            <div class="product-store mb-3">
                                <i class="fa-solid fa-store me-1 text-primary"></i>
                                <?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?>
                            </div>

                            <div class="mt-auto d-grid gap-2">
                                <a href="<?= base_url('produk/' . $item['slug_produk']) ?>"
                                    class="btn btn-primary rounded-pill">
                                    <i class="fa-solid fa-eye me-2"></i>
                                    Lihat Detail
                                </a>

                                <a href="<?= base_url('keranjang') ?>" class="btn btn-outline-secondary rounded-pill">
                                    <i class="fa-solid fa-cart-plus me-2"></i>
                                    Tambah ke Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light border rounded-4 text-center py-5">
                        <i class="fa-solid fa-box-open fa-2x text-primary mb-3"></i>
                        <h5 class="fw-bold">Produk belum tersedia</h5>
                        <p class="text-muted mb-0">Silakan tambahkan produk agar halaman marketplace tampil penuh.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</div>

<?= $this->endSection() ?>