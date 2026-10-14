# Login & Registrasi — Toko Suroto / CI4

## Yang diperbaiki

- Controller berada di app/Controllers/Auth.php dengan namespace App\Controllers dan class Auth.
- Auth memakai App\Models\Models_User, sesuai deklarasi class yang dikirim, serta App\Models\Models_Konfigurasi, sesuai nama model pada screenshot sebelumnya.
- Login/registrasi memakai layout auth tersendiri, CSS responsif, dan ikon SVG lokal. Tidak perlu Bootstrap/CDN atau perubahan marketplace.css untuk menampilkan kedua halaman ini.
- Action form mengikuti route yang diberikan: auth/login-process dan auth/register-process. Nama method PHP tetap login_process dan register_process.
- Login berhasil membuat session id_user, nama, email, logged_in=true, role=user, kemudian membuka /keranjang. Akun nonaktif tidak dapat masuk.
- Registrasi memvalidasi nama/email/telepon/password/konfirmasi, menyimpan password dengan password_hash(), lalu mengarahkan ke login. Password tidak ditaruh di flashdata atau diisi ulang pada HTML.
- Input email dinormalisasi ke lowercase sebelum pemeriksaan unik dan penyimpanan. Input yang gagal selain password tetap terisi saat kembali ke form.
- Session ID diregenerasi setelah login. Form memiliki token CSRF; filter CSRF harus aktif di project.

## Cara pasang

1. Backup Auth.php, Models_User.php, view auth, dan Routes.php yang sudah ada.
2. Salin folder app dari paket ke folder app project, gabungkan foldernya.
3. Pastikan app/Models/Models_Konfigurasi.php mendeklarasikan class Models_Konfigurasi di namespace App\Models dan menyediakan getKonfigurasi(). Model konfigurasi tidak disertakan karena isi aslinya belum diberikan.
4. Jika Models_User saat ini berada di file gabungan Models_Admin_User.php, pindahkan deklarasi class Models_User ke file Models_User.php yang disertakan. Jangan membiarkan class yang sama dideklarasikan dua kali ketika file gabungan masih dimuat. Models_Admin tidak dipakai oleh login pelanggan ini; pertahankan model admin di file tersendiri bila digunakan bagian admin. Jangan menempelkan kode model ke Auth.php.
5. Gabungkan group auth berikut ke app/Config/Routes.php. Hapus/perbarui group auth lama yang sama, bukan seluruh Routes.php. Jangan menambahkan tag <?php kedua.

```php
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login-process', 'Auth::login_process');
    $routes->get('register', 'Auth::register');
    $routes->post('register-process', 'Auth::register_process');
    $routes->get('logout', 'Auth::logout');
});
```

6. Pastikan filter csrf aktif dalam globals['before'] di app/Config/Filters.php. Tambahkan 'csrf' pada array before yang ada tanpa menghapus filter lain.
7. Buka /auth/register, buat akun pelanggan, lalu masuk melalui /auth/login. Redirect setelah masuk adalah /keranjang dari paket keranjang sebelumnya. Link logout sesuai route yang diminta adalah base_url('auth/logout').

## Cek error 404

Pesan `Controller or its method is not found: App\Controllers\Auth::login` berarti routing belum menemukan controller/method tersebut. Import model yang tidak cocok adalah masalah terpisah yang juga diperbaiki di paket.

Pastikan nama file Auth.php, class Auth, namespace App\Controllers, dan public function login() persis seperti dalam paket. Di server Linux, huruf besar-kecil penting. Dari root project, jalankan `php spark routes` dan periksa GET auth/login serta POST auth/login-process. Jika error berubah menjadi Class Models_User not found, periksa lokasi file model dan nama class; jika menjadi Invalid file auth/login.php, periksa penempatan folder view.

## Konfigurasi database yang diasumsikan

- Tabel user memiliki kolom id_user, nama, email, password, no_telp, alamat, status sebagaimana model yang diberikan. Field tambahan provinsi/kota/kode_pos/foto dan timestamp harus mengizinkan nilai kosong atau mempunyai default sesuai skema asli.
- status akun aktif bernilai string 'aktif', akun tidak aktif 'nonaktif'. Password akun lama harus sudah berupa hash yang cocok dengan password_verify(), sebagaimana kode awal; tidak ada dukungan password plaintext.
- Kolom password perlu cukup panjang untuk hash (umumnya VARCHAR(255)). Email sebaiknya memiliki constraint UNIQUE agar registrasi bersamaan tidak dapat menghasilkan email ganda. Paket tidak menjalankan migrasi atau mengubah database.
- getAllUser() mempertahankan orderBy('created_at') dari model awal, sehingga method tersebut memerlukan kolom created_at jika dipanggil. Alur login tidak memanggil method ini.
- Model admin tidak digabung menjadi autentikasi pelanggan. Tidak ada akun/password contoh yang ditanam ke database.

## Pembatasan percobaan

Controller menggunakan Throttler CI4 per IP: kapasitas 10 percobaan login dengan waktu pengisian ulang 60 detik; registrasi 5 dengan waktu pengisian ulang 300 detik. Pastikan cache memakai handler nyata, misalnya file dengan folder writable/cache yang dapat ditulis, bukan dummy. Ini adalah token bucket, bukan penghitung blok per menit. Sesuaikan konfigurasi proxy/IP jika aplikasi ada di belakang reverse proxy.

Referensi implementasi:
- https://codeigniter.com/user_guide/libraries/validation.html
- https://codeigniter4.github.io/CodeIgniter4/libraries/throttler.html
- https://www.codeigniter.com/user_guide/libraries/sessions.html

## Verifikasi

Sintaks PHP/JavaScript, kecocokan route dengan method/action form, token CSRF pada form, dan tidak adanya pengisian ulang password diperiksa secara statis. Belum diuji lewat runtime CI4/database atau browser karena project lengkap tidak tersedia.

Sesudah dipasang, periksa registrasi valid, email duplikat, konfirmasi password berbeda, login salah, akun nonaktif, login benar, session keranjang, dan logout.
