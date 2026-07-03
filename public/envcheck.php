<?php
header('Content-Type: text/plain');
echo "getenv('app.baseURL'): " . var_export(getenv('app.baseURL'), true) . "\n";
echo "\$_SERVER['app.baseURL']: " . var_export($_SERVER['app.baseURL'] ?? null, true) . "\n";
echo "\$_ENV['app.baseURL']: " . var_export($_ENV['app.baseURL'] ?? null, true) . "\n";
echo "getenv('PORT'): " . var_export(getenv('PORT'), true) . "\n";
echo "getenv('CI_ENVIRONMENT'): " . var_export(getenv('CI_ENVIRONMENT'), true) . "\n";
