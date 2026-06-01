<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// POS Web Routes
$routes->group('pos', ['filter' => 'session'], function ($routes) {
    $routes->get('terminal',  'App\Modules\POS\Controllers\POSController::terminal');
    $routes->get('sessions',  'App\Modules\POS\Controllers\POSController::sessions');
});

// POS API Routes
$routes->group('api/v1/pos', function ($routes) {
    $routes->post('transactions',     'App\Modules\POS\Controllers\API\POSApiController::createTransaction');
    $routes->post('sessions/open',    'App\Modules\POS\Controllers\API\POSApiController::openSession');
    $routes->post('sessions/close',   'App\Modules\POS\Controllers\API\POSApiController::closeSession');
    $routes->get('transactions',      'App\Modules\POS\Controllers\API\POSApiController::listTransactions');
    $routes->get('transactions/(:num)', 'App\Modules\POS\Controllers\API\POSApiController::getTransaction/$1');
});
