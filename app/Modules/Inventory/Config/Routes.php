<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Inventory Web Routes
$routes->group('inventory', ['namespace' => 'App\Modules\Inventory\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('stock',       'InventoryController::stock');
    $routes->get('transfers',   'InventoryController::transfers');
    $routes->get('opname',      'InventoryController::opname');
    $routes->get('products',    'ProductController::index');
    $routes->get('products/new','ProductController::create');
    $routes->post('products',   'ProductController::store');
    $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
    $routes->post('products/update/(:num)', 'ProductController::update/$1');
    $routes->get('products/delete/(:num)', 'ProductController::delete/$1');
    $routes->post('categories',            'ProductController::categoryStore');
});

// Inventory API Routes
$routes->group('api/v1/inventory', ['namespace' => 'App\Modules\Inventory\Controllers\API'], function ($routes) {
    $routes->get('stocks',       'InventoryApiController::getStocks');
    $routes->post('transfers',   'InventoryApiController::createTransfer');
    $routes->get('products',     'InventoryApiController::getProducts');
    $routes->post('products',    'InventoryApiController::createProduct');
    $routes->get('products/(:num)', 'InventoryApiController::getProduct/$1');
});
