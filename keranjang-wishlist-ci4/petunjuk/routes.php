<?php
// Gabungkan baris ini ke app/Config/Routes.php; jangan mengganti seluruh file.
// Hapus/perbarui route lama yang sama agar tidak ada duplikasi.
$routes->get('keranjang', 'Keranjang::index');
$routes->post('keranjang/tambah', 'Keranjang::tambah');
$routes->post('keranjang/update', 'Keranjang::update');
$routes->post('keranjang/hapus/(:num)', 'Keranjang::hapus/$1');
$routes->post('keranjang/clear', 'Keranjang::clear');
$routes->get('wishlist', 'Wishlist::index');
$routes->post('wishlist/tambah/(:num)', 'Wishlist::tambah/$1');
$routes->post('wishlist/hapus/(:num)', 'Wishlist::hapus/$1');
