<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');        
$routes->post('/login/submit', 'AuthController::submit');
$routes->post('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register/submit', 'AuthController::submitRegister');
$routes->get('/profile', 'AuthController::profile');
$routes->get('/profile/edit', 'AuthController::edit');
$routes->post('/profile/update', 'AuthController::update');
$routes->post('/profile/delete', 'AuthController::delete');
$routes->get('/user/delete/(:num)', 'AuthController::delete/$1');
$routes->post('profile/delete/(:num)', 'AuthController::delete/$1');


$routes->get('/home', 'KitController::home');
$routes->post('/home', 'KitController::home');
$routes->get('search', 'KitController::search');
$routes->get('/katalog/(:any)', 'KitController::katalog/$1');
$routes->get('/katalog', 'KitController::katalog');
$routes->get('barang', 'KitController::index');
$routes->get('barang/add/(:any)', 'KitController::add/$1');
$routes->post('barang/store/(:any)', 'KitController::store/$1');
$routes->get('barang/edit/(:any)', 'KitController::edit/$1');
$routes->post('barang/update/(:any)', 'KitController::update/$1');
$routes->get('barang/delete/(:any)', 'KitController::delete/$1');

$routes->get('/keranjang/(:num)', 'ShopController::showKeranjang/$1');
$routes->get('/barang', 'ShopController::showBarang');
$routes->get('/keranjang/add/(:num)/(:num)', 'ShopController::addToCart/$1/$2');
$routes->post('/keranjang/update/(:num)/(:num)', 'ShopController::updateKeranjang/$1/$2');
$routes->get('/keranjang/delete/(:num)/(:num)', 'ShopController::deleteFromCart/$1/$2');
$routes->post('update-quantity/(:num)', 'ShopController::updateQuantity/$1');
$routes->post('keranjang/updateQuantity', 'ShopController::updateQuantity');
$routes->post('keranjang/removeItem', 'ShopController::updateQuantity');
$routes->get('/payment/(:num)', 'ShopController::showPayment/$1');
$routes->post('checkout/(:num)', 'ShopController::checkout/$1');
$routes->get('search/ajax', 'KitController::ajaxSearch');
$routes->get('search', 'KitController::search');
$routes->get('/keranjang_admin', 'ShopController::index');
$routes->post('/keranjang_admin/updateStatus/(:num)', 'ShopController::updateStatus/$1');
$routes->post('/keranjang_admin/delete/(:num)', 'ShopController::delete/$1');
$routes->get('/detail_pembelian', 'ShopController::detail_pembelian');
$routes->post('/keranjang/getOrderSummary', 'ShopController::getOrderSummary');
$routes->get('/keranjang/cartCount/(:num)', 'ShopController::getCartCount/$1');
