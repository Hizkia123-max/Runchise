<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// POS Web Routes
$routes->group('pos', ['namespace' => 'App\Modules\POS\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('terminal',  'POSController::terminal');
    $routes->get('sessions',  'POSController::sessions');
});

// POS API Routes
$routes->group('api/v1/pos', ['namespace' => 'App\Modules\POS\Controllers\API'], function ($routes) {
    $routes->post('transactions',     'POSApiController::createTransaction');
    $routes->post('sessions/open',    'POSApiController::openSession');
    $routes->post('sessions/close',   'POSApiController::closeSession');
    $routes->get('transactions',      'POSApiController::listTransactions');
    $routes->get('transactions/(:num)', 'POSApiController::getTransaction/$1');
});
