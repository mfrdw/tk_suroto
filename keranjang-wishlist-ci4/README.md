# Keranjang & Wishlist — CI4 / Toko Suroto

## Penamaan mengikuti screenshot

| File | Class yang dipanggil |
| --- | --- |
| app/Models/Models_Keranjang_Wishlist.php | App\Models\Models_Keranjang_Wishlist |
| app/Models/Models_Produk_Kategori.php | App\Models\Models_Produk_Kategori |
| app/Models/Models_Konfigurasi.php | App\Models\Models_Konfigurasi |

Screenshot hanya memperlihatkan nama file. Paket mengasumsikan deklarasi class sama persis dengan nama file (termasuk huruf besar/kecil), dengan namespace App\Models. Dua model terakhir tetap menggunakan file project milikmu: Models_Produk_Kategori harus menyediakan getProdukById($id), dan Models_Konfigurasi harus menyediakan getKonfigurasi(), sesuai method pada controller yang dikirim. Isi kedua model itu belum diberikan, jadi kecocokan deklarasi tersebut perlu dicek di project.

## Cara pasang

1. Backup file controller, model gabungan, dan view yang akan diganti. Model gabungan dalam paket dibuat dari dua model yang dikirim lewat chat. Jika file aslimu punya method lain, pertahankan/gabungkan method itu.
2. Salin folder app dari paket ke folder app project, gabungkan foldernya. Ada 2 controller, 1 model gabungan, 2 view, dan 2 partial pendukung.
3. Gabungkan definisi pada petunjuk/routes.php ke app/Config/Routes.php, tanpa menambahkan tag PHP pembuka kedua. Ganti route lama yang sama agar tidak duplikat. Jangan mengganti seluruh file Routes.php.
4. Layout layout/template harus menyediakan renderSection('content'), Bootstrap 5, serta Font Awesome sebagaimana beranda. Style/script view dimuat otomatis melalui partial. Jika template membungkus section dalam container dengan max-width tetap, lepaskan pembatas itu agar halaman dapat melebar.
5. Pastikan filter `csrf` aktif pada globals['before'] di app/Config/Filters.php, gabungkan dengan konfigurasi yang sudah ada. Semua form sudah memakai csrf_field(). Gunakan route POST untuk tambah/update/hapus/clear. Controller paket juga menolak request non-POST untuk aksi tersebut.
6. Login, buka /keranjang dan /wishlist, lalu Ctrl+F5.

## Perilaku view

- Keranjang: subtotal, total, tombol −/+, Simpan Jumlah, hapus produk, kosongkan, kondisi kosong, dan peringatan stok.
- Perubahan jumlah baru tersimpan setelah menekan Simpan Jumlah; total tetap menunjukkan nilai yang sudah disimpan sampai halaman dimuat ulang.
- Wishlist: jumlah favorit, detail, stok, tambah ke keranjang, hapus, serta kondisi kosong. Produk tetap berada di wishlist setelah ditambahkan ke keranjang.
- Flash message success/error/info sudah didukung.
- Foto sementara berupa ikon kotak. Nama kolom foto serta direktori upload belum diberikan, sehingga tidak menebak path gambar.
- Tombol Checkout hanya muncul jika Keranjang::index() mengirim 'checkoutUrl' => base_url('checkout') dan tidak ada masalah stok. Aktifkan hanya jika route/controller checkout sudah ada. Default menampilkan Cari Produk Lain. Backend checkout tetap wajib mengecek ulang harga dan stok.
- Hapus dan kosongkan meminta konfirmasi saat JavaScript tersedia. Pengiriman form tetap bekerja tanpa JavaScript; jumlah bisa diketik langsung.

## Sambungkan tombol pada beranda/detail produk

Controller Wishlist tetap menggunakan tambah($id_produk) seperti kode terakhir yang dikirim. ID dikirim melalui URL; route dan form memakai POST. Pada view beranda lama, link ke /keranjang hanya membuka halaman, sedangkan tombol hati belum punya aksi. Ganti dengan form di dalam loop $item:

```php
<form action="<?= esc(base_url('keranjang/tambah'), 'attr') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id_produk" value="<?= (int) $item['id_produk'] ?>">
    <input type="hidden" name="qty" value="1">
    <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
</form>

<form action="<?= esc(base_url('wishlist/tambah/' . (int) $item['id_produk']), 'attr') ?>" method="post">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-outline-primary" aria-label="Simpan ke wishlist">
        <i class="fa-regular fa-heart" aria-hidden="true"></i>
    </button>
</form>
```

Jangan membuat form bersarang di dalam form lain. Penambahan wishlist diarahkan ke /wishlist agar hasilnya terlihat.

## Model gabungan dan controller

- Model gabungan memakai tabel default keranjang. insert/update/delete bawaan model dipakai untuk keranjang saja.
- Semua method wishlist menggunakan db->table('wishlist') secara eksplisit. Controller Wishlist memakai tambahWishlist() dan hapusWishlist(); jangan menggantinya dengan insert()/delete() umum karena akan mengarah ke tabel keranjang.
- Wrapper insert/update/delete lama dihapus; method bawaan Model CI4 digunakan langsung. Referensi: https://codeigniter.com/user_guide/models/model.html
- Join mempertahankan ID keranjang/wishlist ketika produk dihapus. Total memakai ekspresi SQL tetap COALESCE(SUM(p.harga * k.qty), 0), tanpa menyisipkan input pengguna ke ekspresi SQL.
- Wishlist diurutkan berdasarkan id_wishlist DESC, sehingga tidak membutuhkan kolom created_at. Jika perlu urutan waktu dan kolom tersebut tersedia, ubah orderBy menjadi w.created_at.
- Controller Keranjang sudah disesuaikan nama modelnya, ditambah validasi integer/qty, pengecekan login untuk seluruh aksi, pengecekan produk hilang, kepemilikan item, hasil penyimpanan, dan whitelist redirect. update dengan qty=0 tetap menghapus item sesuai perilaku lama; input view minimal 1 dan menyediakan tombol hapus tersendiri.
- Tidak ada perubahan skema database. Penambahan bersamaan masih memakai pola baca-lalu-tulis seperti kode awal; constraint unik (id_user, id_produk) dan transaksi perlu disesuaikan dengan skema asli bila hendak menangani request bersamaan.
- Model produk/kategori, model konfigurasi, model admin/user, dan model invoice/pesanan tidak diganti dalam paket ini.

## Verifikasi

Sintaks PHP diperiksa dengan parser. Pemeriksaan integrasi CI4/database dan visual browser belum dijalankan karena project lengkap, database, layout, serta isi model produk/konfigurasi belum tersedia.

Uji setelah pemasangan: keranjang kosong, tambah produk, simpan jumlah, stok kurang, stok habis, produk dihapus, hapus item, kosongkan, tambah favorit, favorit duplikat, tambah favorit ke keranjang, serta akun yang berbeda. Periksa tampilan desktop dan HP.
