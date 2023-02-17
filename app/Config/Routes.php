<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->cli('/websocket/start', 'Websocket::start');
$routes->cli('/websocket/send', 'Websocket::send');
$routes->get('/websocket/user/(:segment)', 'Websocket::user/$1');

$routes->get('/productos', 'Home::productos');

$routes->get('/admin-login', 'Admin::login');
$routes->post('/admin-login', 'Admin::login');
$routes->get('/logout' ,'Admin::logout');

$routes->get('/admin/productos', 'Admin::productos');
$routes->get('/admin/pedidos', 'Admin::pedidos');
$routes->get('/admin/procesar/(:num)', 'Admin::procesar/$1');
$routes->get('/admin/despachar/(:num)', 'Admin::despachar/$1');
$routes->get('/admin/cancelar/(:num)', 'Admin::cancelar/$1');
$routes->get('/admin/resumen', 'Admin::resumen');
//$routes->resource('login');
$routes->get('/login/(:any)/(:any)', 'Login::index/$1/$2');
$routes->get('/mesa/alerta', 'Mesa::alerta');
$routes->get('/mesa/atendido', 'Mesa::atendido');
$routes->get('/mesa/recibir/(:num)', 'Mesa::recibir/$1');
$routes->get('/mesa/list', 'Mesa::list');

$routes->resource('mesa',['filter' => 'authFilter']);
$routes->resource('producto',[]);
$routes->resource('orden',[]);
$routes->post('cliente/buscar','Cliente::buscar');
 $routes->post('cliente/guardar','Cliente::guardar');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
