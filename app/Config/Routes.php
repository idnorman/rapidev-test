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
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

//Login
$routes->group('auth', static function ($routes){
    $routes->get('login', 'Auth::login', ['as' => 'login']);
    $routes->post('login', 'Auth::loginProcess', ['as' => 'login_process']);
    $routes->get('logout', 'Auth::logout', ['as' => 'logout', 'filter' => 'auth']);    
});


//Promotion
$routes->group('promotion', ['filter' => 'auth'], static function ($routes){
    $routes->get('', 'Promotion::index', ['as' => 'promotion_index']);
    $routes->get('create', 'Promotion::create', ['as' => 'promotion_create']);
    $routes->post('store', 'Promotion::store', ['as' => 'promotion_store']);
    $routes->get('edit/(:num)', 'Promotion::edit/$1', ['as' => 'promotion_edit']);
    $routes->put('update', 'Promotion::update', ['as' => 'promotion_update']);
    $routes->get('delete/(:num)', 'Promotion::delete/$1', ['as' => 'promotion_delete']);
});

$routes->get('property/(:any)/rooms', 'Property::getRoomRates');
$routes->get('reservation/(:any)', 'Room::reservation');

// /reservation/:room_id?check_in=2022-08-11&check_out=2022-08-12&amount=1
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
