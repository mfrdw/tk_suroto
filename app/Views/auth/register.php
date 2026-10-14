<?= $this->extend('auth/layout') ?>
<?= $this->section('auth_content') ?>
<?php
helper(['form', 'url']);
$old = session()->getFlashdata('auth_old') ?? [];
?>
<p class="form-eyebrow">Mulai pengalaman belanjamu</p>
<h1>Buat akun baru</h1>
<p class="form-intro">Daftar untuk menyimpan favorit dan mengatur keranjangmu.</p>
<?= $this->include('auth/messages') ?>
<form class="auth-form" action="<?= esc(base_url('auth/register-process'), 'attr') ?>" method="post">
    <?= csrf_field() ?>
    <div class="field"><label for="nama">Nama lengkap</label><input id="nama" name="nama" type="text" autocomplete="name" placeholder="Nama lengkap kamu" minlength="3" maxlength="100" value="<?= esc($old['nama'] ?? '', 'attr') ?>" required></div>
    <div class="form-row">
        <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" autocomplete="email" inputmode="email" placeholder="nama@email.com" maxlength="254" value="<?= esc($old['email'] ?? '', 'attr') ?>" required></div>
        <div class="field"><label for="no_telp">Nomor telepon</label><input id="no_telp" name="no_telp" type="tel" autocomplete="tel" placeholder="08xxxxxxxxxx" pattern="\+?[0-9]{10,15}" maxlength="16" aria-describedby="phone-help" value="<?= esc($old['no_telp'] ?? '', 'attr') ?>" required><p id="phone-help" class="field-note">10–15 digit, tanpa spasi.</p></div>
    </div>
    <div class="field"><label for="alamat">Alamat <span class="optional">(opsional)</span></label><textarea id="alamat" name="alamat" autocomplete="street-address" placeholder="Jalan, nomor rumah, dan wilayah" maxlength="500" rows="2"><?= esc($old['alamat'] ?? '') ?></textarea></div>
    <div class="field"><label for="password">Password</label><div class="password-wrap"><input id="password" name="password" type="password" autocomplete="new-password" placeholder="Buat password kamu" minlength="8" maxlength="72" aria-describedby="password-help" required><button type="button" class="password-toggle" data-password-toggle="password" aria-controls="password" aria-pressed="false" hidden>Lihat</button></div><p id="password-help" class="field-note">Minimal 8 karakter. Gunakan password yang unik.</p></div>
    <div class="field"><label for="password_confirm">Konfirmasi password</label><div class="password-wrap"><input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" placeholder="Ulangi password" minlength="8" maxlength="72" required><button type="button" class="password-toggle" data-password-toggle="password_confirm" aria-controls="password_confirm" aria-pressed="false" hidden>Lihat</button></div></div>
    <button type="submit" class="submit-btn">Daftar Sekarang</button>
</form>
<p class="form-switch">Sudah punya akun? <a href="<?= esc(base_url('auth/login'), 'attr') ?>">Masuk di sini</a></p>
<?= $this->endSection() ?>
