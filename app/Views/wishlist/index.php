<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<?php
helper(['form', 'url']);
$items = $wishlist ?? [];
$rupiah = static function ($nilai) { return 'Rp ' . number_format((float) $nilai, 0, ',', '.'); };
?>
<?= $this->include('partials/belanja_styles') ?>
<div class="belanja-page">
    <header class="bp-heading"><div><div class="bp-eyebrow">Simpan yang kamu suka</div><h1>Wishlist Saya</h1><p><?= count($items) ?> produk favorit untuk belanja berikutnya.</p></div><div class="bp-actions"><a href="<?= esc(base_url('produk'), 'attr') ?>" class="btn btn-light border rounded-pill">Jelajahi Produk</a><a href="<?= esc(base_url('keranjang'), 'attr') ?>" class="btn btn-primary rounded-pill"><i class="fa-solid fa-cart-shopping me-2" aria-hidden="true"></i>Lihat Keranjang</a></div></header>
    <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $color): ?>
        <?php if ($message = session()->getFlashdata($key)): ?><div class="alert alert-<?= $color ?> bp-flash" role="alert"><?= esc($message) ?></div><?php endif; ?>
    <?php endforeach; ?>
    <?php if (empty($items)): ?>
        <section class="bp-panel bp-empty"><div class="bp-empty-icon"><i class="fa-regular fa-heart" aria-hidden="true"></i></div><h2>Belum ada produk favorit</h2><p>Simpan produk yang kamu suka agar lebih mudah ditemukan saat ingin belanja.</p><a class="btn btn-primary rounded-pill px-4" href="<?= esc(base_url('produk'), 'attr') ?>">Jelajahi Produk</a></section>
    <?php else: ?>
        <div class="bp-wishlist-grid">
            <?php foreach ($items as $item): ?>
                <?php
                $id = (int) $item['id_wishlist'];
                $adaProduk = !empty($item['nama_produk']);
                $nama = $item['nama_produk'] ?? 'Produk tidak tersedia';
                $stok = max(0, (int) ($item['stok'] ?? 0));
                $detail = !empty($item['slug_produk']) ? base_url('produk/' . rawurlencode($item['slug_produk'])) : null;
                ?>
                <article class="bp-panel bp-wishlist-card">
                    <div class="bp-thumb bp-wishlist-thumb" aria-hidden="true"><i class="fa-solid fa-box-open"></i></div>
                    <div class="bp-wishlist-body"><div class="bp-category"><?= esc($item['nama_kategori'] ?? 'Produk') ?></div><h2 class="bp-name"><?php if ($adaProduk && $detail): ?><a href="<?= esc($detail, 'attr') ?>"><?= esc($nama) ?></a><?php else: ?><?= esc($nama) ?><?php endif; ?></h2><div class="bp-price"><?= esc($rupiah($item['harga'] ?? 0)) ?></div>
                        <div class="bp-stock <?= (!$adaProduk || $stok < 1) ? 'bp-stock-warning' : '' ?>"><?php if (!$adaProduk): ?>Produk tidak tersedia<?php elseif ($stok < 1): ?>Stok habis<?php else: ?>Stok tersedia: <?= $stok ?><?php endif; ?></div>
                        <div class="bp-wishlist-actions">
                            <?php if ($adaProduk && $stok > 0): ?><form action="<?= esc(base_url('keranjang/tambah'), 'attr') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="id_produk" value="<?= (int) $item['id_produk'] ?>"><input type="hidden" name="qty" value="1"><input type="hidden" name="redirect" value="wishlist"><button type="submit" class="btn btn-primary rounded-pill"><i class="fa-solid fa-cart-plus me-1" aria-hidden="true"></i>Tambah ke Keranjang</button></form><?php else: ?><button type="button" class="btn btn-secondary rounded-pill" disabled>Belum Tersedia</button><?php endif; ?>
                            <?php if ($adaProduk && $detail): ?><a class="btn btn-outline-primary rounded-pill" href="<?= esc($detail, 'attr') ?>">Lihat Detail</a><?php endif; ?>
                            <form action="<?= esc(base_url('wishlist/hapus/' . $id), 'attr') ?>" method="post" data-confirm="Hapus produk ini dari wishlist?"><?= csrf_field() ?><button type="submit" class="bp-remove">Hapus dari Wishlist</button></form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->include('partials/belanja_scripts') ?>
<?= $this->endSection() ?>
