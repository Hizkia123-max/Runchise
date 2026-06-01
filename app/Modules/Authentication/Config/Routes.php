<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Authentication Module Routes
$routes->group('auth', ['namespace' => 'App\Modules\Authentication\Controllers'], function ($routes) {
    $routes->get('login',  'AuthController::login');
    $routes->post('login', 'AuthController::loginPost');
    $routes->get('logout', 'AuthController::logout');
});

// Authentication API Routes
$routes->group('api/v1/auth', ['namespace' => 'App\Modules\Authentication\Controllers\API'], function ($routes) {
    $routes->post('login', 'AuthApiController::login');
});
