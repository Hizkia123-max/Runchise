<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';
$db = \Config\Database::connect();
$res = $db->table('products')
    ->select('products.*, categories.name as category_name')
    ->join('categories', 'categories.id = products.category_id', 'left')
    ->get()->getResultArray();
foreach($res as $p) {
    echo $p['id'] . ' - ' . $p['name'] . "\n";
}
