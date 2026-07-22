<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Purchasing Web Routes
$routes->group('purchasing', ['namespace' => 'App\Modules\Purchasing\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('orders',             'PurchasingController::purchaseOrders');
    $routes->get('orders/create',      'PurchasingController::createPO');
    $routes->post('orders',            'PurchasingController::storePO');
    $routes->get('receive/(:num)',     'PurchasingController::receivePO/$1');
    $routes->post('receive',           'PurchasingController::storeReceiving');
    $routes->get('receivings',         'PurchasingController::receivingHistory');
    $routes->get('suppliers',          'PurchasingController::suppliers');
    $routes->post('suppliers',         'PurchasingController::storeSupplier');
    $routes->get('returns',            'PurchasingController::returns');
    $routes->post('returns/process',   'PurchasingController::processReturn');
    $routes->get('payments/(:num)',    'PurchasingController::payments/$1');
    $routes->post('payments/store',    'PurchasingController::storePayment');
});
