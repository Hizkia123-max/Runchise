<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Authentication Module Routes
$routes->group('auth', function ($routes) {
    $routes->get('login',  'App\Modules\Authentication\Controllers\AuthController::login');
    $routes->post('login', 'App\Modules\Authentication\Controllers\AuthController::loginPost');
    $routes->get('logout', 'App\Modules\Authentication\Controllers\AuthController::logout');
});

// Authentication API Routes
$routes->group('api/v1/auth', ['filter' => ''], function ($routes) {
    $routes->post('login', 'App\Modules\Authentication\Controllers\API\AuthApiController::login');
});
