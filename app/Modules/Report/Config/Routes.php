<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Report Web Routes
$routes->group('report', ['namespace' => 'App\Modules\Report\Controllers', 'filter' => 'session'], function ($routes) {
    $routes->get('sales',        'ReportController::salesReport');
    $routes->get('sales/numeric','ReportController::salesNumeric');
    $routes->get('sales/visual', 'ReportController::salesVisual');
    $routes->get('stock-card',   'ReportController::stockCard');
    $routes->get('stock-onhand', 'ReportController::stockOnHand');
    $routes->get('numeric',      'ReportController::numericReport');
});
