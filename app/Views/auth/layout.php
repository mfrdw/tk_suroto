<?php
helper(['form', 'url']);
$namaToko = $konfigurasi['nama_toko'] ?? 'Toko Suroto';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= esc($title ?? 'Akun - Toko Suroto') ?></title>
    <?= $this->include('auth/styles') ?>
</head>
<body class="auth-page">
    <header class="auth-header"><a class="auth-brand" href="<?= esc(base_url(), 'attr') ?>"><span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 7h14l1 14H4L5 7Z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/></svg></span><span><?= esc($namaToko) ?><small>Belanja mudah, cepat, terpercaya</small></span></a><a class="back-link" href="<?= esc(base_url(), 'attr') ?>">← Kembali ke toko</a></header>
    <main class="auth-shell">
        <aside class="auth-story"><div class="story-top"><span class="story-label">TEMUKAN PILIHANMU</span><h2>Belanja nyaman,<br>mulai dari sini.</h2><p>Satu akun untuk menyimpan produk favorit dan melanjutkan belanja kapan saja.</p></div><div class="story-cards" aria-hidden="true"><div class="story-card"><span class="story-icon">♡</span><div><strong>Wishlist pilihanmu</strong><small>Simpan dulu, belanja nanti.</small></div></div><div class="story-card"><span class="story-icon">✓</span><div><strong>Keranjang lebih praktis</strong><small>Atur produk dan jumlahnya.</small></div></div></div><span class="story-footer"><?= esc($namaToko) ?> · Pilihan untuk kebutuhanmu</span></aside>
        <section class="auth-form-panel"><?= $this->renderSection('auth_content') ?></section>
    </main>
    <footer class="auth-footer">&copy; <?= date('Y') ?> <?= esc($namaToko) ?></footer>
    <script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.hidden = false;
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Sembunyikan' : 'Lihat';
            button.setAttribute('aria-pressed', show ? 'true' : 'false');
        });
    });
    </script>
</body>
</html>
