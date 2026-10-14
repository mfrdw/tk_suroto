<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
|--------------------------------------------------------------------------
| FRONTEND ROUTES
|--------------------------------------------------------------------------
*/

// Home
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');


// Produk
$routes->get('produk', 'Home::produk');
$routes->get('produk/(:segment)', 'Home::detail/$1');


// Halaman Statis
$routes->get('tentang', 'Home::tentang');
$routes->get('kontak', 'Home::kontak');



/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/


// Keranjang


$routes->get('keranjang', 'Controller_Keranjang::index');
$routes->post('keranjang/tambah', 'Controller_Keranjang::tambah');
$routes->post('keranjang/update', 'Controller_Keranjang::update');
$routes->post('keranjang/hapus/(:num)', 'Controller_Keranjang::hapus/$1');
$routes->post('keranjang/clear', 'Controller_Keranjang::clear');




// Wishlist
$routes->group('wishlist', function($routes){

$routes->get('wishlist', 'Controller_Wishlist::index');
$routes->post('wishlist/tambah/(:num)', 'Controller_:tambah/$1');
$routes->post('wishlist/hapus/(:num)', 'Controller_:hapus/$1');

});


// Checkout
$routes->group('checkout', function($routes){

    $routes->get('/', 'Checkout::index');
    $routes->post('proses', 'Checkout::proses');
    $routes->post('upload-bukti', 'Checkout::upload_bukti');

});


// Pesanan
$routes->group('pesanan', function($routes){

    $routes->get('/', 'Pesanan::index');
    $routes->get('detail/(:num)', 'Pesanan::detail/$1');

});



/*
|--------------------------------------------------------------------------
| AUTH CUSTOMER
|--------------------------------------------------------------------------
*/


$routes->group('auth', function($routes){

    $routes->get('login', 'Controller_Auth::login');
    $routes->post('login-process', 'Controller_Auth::login_process');

    $routes->get('register', 'Controller_Auth::register');
    $routes->post('register-process', 'Controller_Auth::register_process');

    $routes->get('logout', 'Controller_Auth::logout');

});



/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/


$routes->group('admin', function($routes){

    $routes->get('login', 'Admin\AuthAdmin::login');
    $routes->post('login-process', 'Admin\AuthAdmin::login_process');
    $routes->get('logout', 'Admin\AuthAdmin::logout');


});



/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/


$routes->group('admin', ['filter'=>'adminAuth'], function($routes){


    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');



    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    $routes->group('kategori', function($routes){

        $routes->get('/', 'Admin\Kategori::index');

        $routes->get('tambah', 'Admin\Kategori::tambah');

        $routes->post('simpan', 'Admin\Kategori::simpan');

        $routes->get('edit/(:num)', 'Admin\Kategori::edit/$1');

        $routes->post('update/(:num)', 'Admin\Kategori::update/$1');

        $routes->get('hapus/(:num)', 'Admin\Kategori::hapus/$1');

    });



    /*
    |--------------------------------------------------------------------------
    | PRODUK
    |--------------------------------------------------------------------------
    */

    $routes->group('produk', function($routes){

        $routes->get('/', 'Admin\Produk::index');

        $routes->get('tambah', 'Admin\Produk::tambah');

        $routes->post('simpan', 'Admin\Produk::simpan');

        $routes->get('edit/(:num)', 'Admin\Produk::edit/$1');

        $routes->post('update/(:num)', 'Admin\Produk::update/$1');

        $routes->get('hapus/(:num)', 'Admin\Produk::hapus/$1');

    });



    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    $routes->group('user', function($routes){

        $routes->get('/', 'Admin\User::index');

        $routes->get('detail/(:num)', 'Admin\User::detail/$1');

        $routes->post(
            'update-status/(:num)',
            'Admin\User::update_status/$1'
        );

        $routes->get('hapus/(:num)', 'Admin\User::hapus/$1');

    });



    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    $routes->group('transaksi', function($routes){

        $routes->get('/', 'Admin\Transaksi::index');

        $routes->get(
            'detail/(:num)',
            'Admin\Transaksi::detail/$1'
        );

        $routes->post(
            'update-status/(:num)',
            'Admin\Transaksi::update_status/$1'
        );

    });


});



/*
|--------------------------------------------------------------------------
| API ROUTES
|--------------------------------------------------------------------------
*/


$routes->group('api', function($routes){

    $routes->get(
        'produk',
        'Api\Produk::index'
    );

    $routes->get(
        'produk/(:num)',
        'Api\Produk::show/$1'
    );

});



/*
|--------------------------------------------------------------------------
| SYSTEM ROUTES
|--------------------------------------------------------------------------
*/

// Hanya jika menggunakan Myth/Auth
// service('auth')->routes($routes);


return $routes;