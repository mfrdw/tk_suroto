<?= $this->extend('auth/layout') ?>
<?= $this->section('auth_content') ?>
<?php
helper(['form', 'url']);
$old = session()->getFlashdata('auth_old') ?? [];
?>
<p class="form-eyebrow">Selamat datang kembali</p>
<h1>Masuk ke akunmu</h1>
<p class="form-intro">Lanjutkan belanja dan temukan produk favoritmu.</p>
<?= $this->include('auth/messages') ?>
<form class="auth-form" action="<?= esc(base_url('auth/login-process'), 'attr') ?>" method="post">
    <?= csrf_field() ?>
    <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" autocomplete="username" inputmode="email" placeholder="nama@email.com" maxlength="254" value="<?= esc($old['email'] ?? '', 'attr') ?>" required></div>
    <div class="field"><label for="password">Password</label><div class="password-wrap"><input id="password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required><button type="button" class="password-toggle" data-password-toggle="password" aria-controls="password" aria-pressed="false" hidden>Lihat</button></div></div>
    <button type="submit" class="submit-btn">Masuk</button>
</form>
<p class="form-switch">Belum punya akun? <a href="<?= esc(base_url('auth/register'), 'attr') ?>">Daftar sekarang</a></p>
<?= $this->endSection() ?>
