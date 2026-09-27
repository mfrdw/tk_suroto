<footer class="footer-marketplace mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3"><?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?></h4>
                <p class="text-secondary mb-3">
                    <?= esc($konfigurasi['deskripsi'] ?? 'Marketplace modern dengan produk berkualitas dan pelayanan terbaik.') ?>
                </p>

                <div class="d-flex gap-2 social-links">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <h6 class="fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?= base_url('/') ?>">Beranda</a></li>
                    <li><a href="<?= base_url('produk') ?>">Produk</a></li>
                    <li><a href="<?= base_url('tentang') ?>">Tentang Kami</a></li>
                    <li><a href="<?= base_url('kontak') ?>">Kontak</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold mb-3">Bantuan</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Cara Belanja</a></li>
                    <li><a href="#">Metode Pembayaran</a></li>
                    <li><a href="#">Pengiriman</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-4">
                <h6 class="fw-bold mb-3">Kontak</h6>
                <ul class="list-unstyled footer-contact">
                    <li><i class="fa-solid fa-location-dot me-2"></i><?= esc($konfigurasi['alamat'] ?? '-') ?></li>
                    <li><i class="fa-solid fa-phone me-2"></i><?= esc($konfigurasi['telepon'] ?? '-') ?></li>
                    <li><i class="fa-solid fa-envelope me-2"></i><?= esc($konfigurasi['email'] ?? '-') ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-bottom border-top">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 text-secondary small">
                © <?= date('Y') ?> <?= esc($konfigurasi['nama_toko'] ?? 'Toko Suroto') ?>. All rights reserved.
            </p>
            <p class="mb-0 text-secondary small">
                Tampilan marketplace modern - CI4 + Bootstrap 5.3
            </p>
        </div>
    </div>
</footer>