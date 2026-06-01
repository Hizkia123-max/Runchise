<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Inventory Web Routes
$routes->group('inventory', ['filter' => 'session'], function ($routes) {
    $routes->get('stock',       'App\Modules\Inventory\Controllers\InventoryController::stock');
    $routes->get('transfers',   'App\Modules\Inventory\Controllers\InventoryController::transfers');
    $routes->get('opname',      'App\Modules\Inventory\Controllers\InventoryController::opname');
    $routes->get('products',    'App\Modules\Inventory\Controllers\ProductController::index');
    $routes->get('products/new','App\Modules\Inventory\Controllers\ProductController::create');
    $routes->post('products',   'App\Modules\Inventory\Controllers\ProductController::store');
});

// Inventory API Routes
$routes->group('api/v1/inventory', function ($routes) {
    $routes->get('stocks',       'App\Modules\Inventory\Controllers\API\InventoryApiController::getStocks');
    $routes->post('transfers',   'App\Modules\Inventory\Controllers\API\InventoryApiController::createTransfer');
    $routes->get('products',     'App\Modules\Inventory\Controllers\API\InventoryApiController::getProducts');
    $routes->post('products',    'App\Modules\Inventory\Controllers\API\InventoryApiController::createProduct');
    $routes->get('products/(:num)', 'App\Modules\Inventory\Controllers\API\InventoryApiController::getProduct/$1');
});
