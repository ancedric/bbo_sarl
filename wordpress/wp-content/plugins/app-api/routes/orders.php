<?php

require_once __DIR__ . '/../services/OrderService.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../middlewares/auth.php';

function register_order_routes() {

    // Créer une commande
    register_rest_route('app', '/orders', [
        'methods' => 'POST',
        'callback' => fn() =>
            api_response(
                OrderService::create_from_cart(),
                'Commande créée'
            ),
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Liste des commandes
    register_rest_route('app', '/orders', [
        'methods' => 'GET',
        'callback' => fn() =>
            api_response(
                OrderService::get_user_orders()
            ),
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Détail commande
    register_rest_route('app', '/orders/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => function ($req) {
            return api_response(
                OrderService::get_order($req['id'])
            );
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);
}
