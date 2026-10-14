<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php
helper(['form', 'url']);
$items = $keranjang ?? [];
$jumlahUnit = 0;
$totalHitung = 0;
$adaMasalahStok = false;
foreach ($items as $baris) {
    $jumlah = max(0, (int) ($baris['qty'] ?? 0));
    $jumlahUnit += $jumlah;
    $totalHitung += max(0, (float) ($baris['harga'] ?? 0)) * $jumlah;
    if (empty($baris['nama_produk']) || $jumlah < 1 || $jumlah > (int) ($baris['stok'] ?? 0)) $adaMasalahStok = true;
}
$totalBelanja = $total ?? $totalHitung;
$rupiah = static function ($nilai) { return 'Rp ' . number_format((float) $nilai, 0, ',', '.'); };
?>
<?= $this->include('partials/belanja_styles') ?>
<div class="belanja-page">
    <header class="bp-heading">
        <div><div class="bp-eyebrow">Belanja pilihanmu</div><h1>Keranjang Belanja</h1><p>Cek produk dan jumlah pesanan sebelum melanjutkan.</p></div>
        <div class="bp-actions"><a href="<?= esc(base_url('wishlist'), 'attr') ?>" class="btn btn-outline-primary rounded-pill"><i class="fa-regular fa-heart me-2" aria-hidden="true"></i>Wishlist</a><a href="<?= esc(base_url('produk'), 'attr') ?>" class="btn btn-light border rounded-pill">Lanjut Belanja</a></div>
    </header>
    <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $color): ?>
        <?php if ($message = session()->getFlashdata($key)): ?><div class="alert alert-<?= $color ?> bp-flash" role="alert"><?= esc($message) ?></div><?php endif; ?>
    <?php endforeach; ?>
    <?php if (empty($items)): ?>
        <section class="bp-panel bp-empty"><div class="bp-empty-icon"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i></div><h2>Keranjangmu masih kosong</h2><p>Yuk, cari produk yang kamu butuhkan dan tambahkan ke keranjang.</p><a class="btn btn-primary rounded-pill px-4" href="<?= esc(base_url('produk'), 'attr') ?>">Mulai Belanja</a></section>
    <?php else: ?>
        <div class="bp-layout">
            <section class="bp-panel" aria-label="Daftar produk dalam keranjang">
                <div class="bp-panel-head"><h2><?= count($items) ?> jenis produk <span class="bp-muted fw-normal">· <?= $jumlahUnit ?> barang</span></h2><form action="<?= esc(base_url('keranjang/clear'), 'attr') ?>" method="post" data-confirm="Kosongkan semua isi keranjang?"><?= csrf_field() ?><button class="bp-remove mt-0" type="submit"><i class="fa-regular fa-trash-can me-1" aria-hidden="true"></i>Kosongkan Keranjang</button></form></div>
                <?php foreach ($items as $item): ?>
                    <?php
                    $id = (int) $item['id_keranjang'];
                    $qty = max(0, (int) ($item['qty'] ?? 0));
                    $stok = max(0, (int) ($item['stok'] ?? 0));
                    $adaProduk = !empty($item['nama_produk']);
                    $nama = $item['nama_produk'] ?? 'Produk tidak tersedia';
                    $harga = max(0, (float) ($item['harga'] ?? 0));
                    $detail = !empty($item['slug_produk']) ? base_url('produk/' . rawurlencode($item['slug_produk'])) : null;
                    ?>
                    <article class="bp-item">
                        <div class="bp-thumb" aria-hidden="true"><i class="fa-solid fa-box-open"></i></div>
                        <div><div class="bp-category"><?= esc($item['nama_kategori'] ?? 'Produk') ?></div><h3 class="bp-name"><?php if ($adaProduk && $detail): ?><a href="<?= esc($detail, 'attr') ?>"><?= esc($nama) ?></a><?php else: ?><?= esc($nama) ?><?php endif; ?></h3><div class="bp-price"><?= esc($rupiah($harga)) ?> <small class="bp-muted fw-normal">/ barang</small></div>
                            <?php if (!$adaProduk): ?><div class="bp-stock bp-stock-warning">Produk sudah tidak tersedia. Hapus dari keranjang.</div>
                            <?php elseif ($stok < 1): ?><div class="bp-stock bp-stock-warning">Stok habis. Hapus produk untuk melanjutkan.</div>
                            <?php elseif ($qty > $stok): ?><div class="bp-stock bp-stock-warning">Stok tersisa <?= $stok ?>. Kurangi jumlah dan simpan.</div>
                            <?php else: ?><div class="bp-stock">Stok tersedia: <?= $stok ?></div><?php endif; ?>
                        </div>
                        <form class="bp-quantity-form" action="<?= esc(base_url('keranjang/update'), 'attr') ?>" method="post">
                            <?= csrf_field() ?><input type="hidden" name="id_keranjang" value="<?= $id ?>">
                            <label class="bp-qty-label" for="qty-<?= $id ?>">Jumlah</label>
                            <?php if ($adaProduk && $stok > 0): ?>
                                <div class="bp-qty"><button type="button" data-qty-step="-1" aria-label="Kurangi jumlah <?= esc($nama, 'attr') ?>">−</button><input id="qty-<?= $id ?>" type="number" inputmode="numeric" name="qty" min="1" max="<?= $stok ?>" step="1" value="<?= $qty ?>" required><button type="button" data-qty-step="1" aria-label="Tambah jumlah <?= esc($nama, 'attr') ?>">+</button></div><button type="submit" class="btn btn-outline-primary bp-update">Simpan Jumlah</button>
                            <?php else: ?><input id="qty-<?= $id ?>" type="number" class="form-control" value="<?= $qty ?>" disabled><?php endif; ?>
                        </form>
                        <div class="bp-subtotal"><small>Subtotal</small><strong class="bp-price"><?= esc($rupiah($harga * $qty)) ?></strong><form method="post" action="<?= esc(base_url('keranjang/hapus/' . $id), 'attr') ?>" data-confirm="Hapus produk ini dari keranjang?"><?= csrf_field() ?><button type="submit" class="bp-remove">Hapus Produk</button></form></div>
                    </article>
                <?php endforeach; ?>
            </section>
            <aside class="bp-panel bp-summary"><h2>Ringkasan Belanja</h2><div class="bp-summary-line"><span class="bp-muted">Jenis produk</span><strong><?= count($items) ?></strong></div><div class="bp-summary-line"><span class="bp-muted">Jumlah barang</span><strong><?= $jumlahUnit ?></strong></div><div class="bp-summary-total"><span>Total produk</span><strong><?= esc($rupiah($totalBelanja)) ?></strong></div><p class="bp-note">Total mengikuti jumlah yang sudah disimpan. Belum termasuk biaya pengiriman.</p>
                <?php if ($adaMasalahStok): ?><div class="alert alert-warning mt-3 mb-0 small" role="alert">Periksa stok dan jumlah produk sebelum melanjutkan.</div><?php endif; ?>
                <?php if (!empty($checkoutUrl) && !$adaMasalahStok): ?><a href="<?= esc($checkoutUrl, 'attr') ?>" class="btn btn-primary rounded-pill w-100 mt-3">Lanjut Checkout</a><?php else: ?><a href="<?= esc(base_url('produk'), 'attr') ?>" class="btn btn-primary rounded-pill w-100 mt-3">Cari Produk Lain</a><?php endif; ?>
            </aside>
        </div>
    <?php endif; ?>
</div>
<?= $this->include('partials/belanja_scripts') ?>
<?= $this->endSection() ?>
