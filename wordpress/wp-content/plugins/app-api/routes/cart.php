<?php

require_once __DIR__ . '/../services/CartService.php';
require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../middlewares/auth.php';

function register_cart_routes() {

    // Voir panier
    register_rest_route('app', '/cart', [
        'methods' => 'GET',
        'callback' => function () {

            $cart = CartService::get_cart();
            $detailed = [];

            foreach ($cart as $item) {
                $product = ProductService::get_by_id($item['product_id']);
                if ($product) {
                    $product['quantity'] = $item['quantity'];
                    $product['total'] = $product['price'] * $item['quantity'];
                    $detailed[] = $product;
                }
            }

            return api_response($detailed);
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Ajouter produit
    register_rest_route('app', '/cart/add', [
        'methods' => 'POST',
        'callback' => function ($request) {

            $product_id = intval($request->get_param('product_id'));
            $quantity = intval($request->get_param('quantity') ?: 1);

            if (!$product_id) {
                return api_response(null, 'Produit requis', 400);
            }

            return api_response(
                CartService::add_item($product_id, $quantity),
                'Produit ajouté au panier'
            );
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Modifier quantité
    register_rest_route('app', '/cart/update', [
        'methods' => 'PUT',
        'callback' => function ($request) {

            return api_response(
                CartService::update_item(
                    intval($request->get_param('product_id')),
                    intval($request->get_param('quantity'))
                ),
                'Panier mis à jour'
            );
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Supprimer produit
    register_rest_route('app', '/cart/remove', [
        'methods' => 'DELETE',
        'callback' => function ($request) {

            return api_response(
                CartService::remove_item(intval($request->get_param('product_id'))),
                'Produit retiré'
            );
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // Vider panier
    register_rest_route('app', '/cart/clear', [
        'methods' => 'DELETE',
        'callback' => fn() => api_response(
            CartService::clear(),
            'Panier vidé'
        ),
        'permission_callback' => 'app_api_auth_middleware'
    ]);
}
