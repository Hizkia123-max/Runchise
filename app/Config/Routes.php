<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::dashboard', ['filter' => 'session']);
$routes->get('reset-database', 'Home::reset_database');
// Dynamically discover and load Routes.php files inside app/Modules
if (is_dir(APPPATH . 'Modules')) {
    $modulesDir = new DirectoryIterator(APPPATH . 'Modules');
    foreach ($modulesDir as $fileInfo) {
        if ($fileInfo->isDir() && !$fileInfo->isDot()) {
            $routesPath = $fileInfo->getPathname() . '/Config/Routes.php';
            if (file_exists($routesPath)) {
                require $routesPath;
            }
        }
    }
}
