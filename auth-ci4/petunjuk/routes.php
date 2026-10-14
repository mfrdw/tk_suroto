<?php
// Gabungkan ke app/Config/Routes.php; jangan mengganti seluruh file.
// Gunakan satu group auth ini; perbarui group lama agar tidak duplikat.
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login-process', 'Auth::login_process');
    $routes->get('register', 'Auth::register');
    $routes->post('register-process', 'Auth::register_process');
    $routes->get('logout', 'Auth::logout');
});
