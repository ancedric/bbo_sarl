<?php
require_once __DIR__ . '/wp-load.php';

$server = rest_get_server();
$routes = $server->get_routes();

header('Content-Type: application/json');
echo json_encode($routes, JSON_PRETTY_PRINT);
