<?php

require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../middlewares/auth.php';

function register_product_routes() {

    // LISTE
    register_rest_route('app', '/products', [
        'methods' => 'GET',
        'callback' => function () {
            return api_response(ProductService::get_all());
        },
        'permission_callback' => '__return_true'
    ]);

    // DÉTAIL
    register_rest_route('app', '/products/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => function ($request) {
            $product = ProductService::get_by_id($request['id']);

            if (!$product) {
                return api_response(null, 'Produit introuvable', 404);
            }

            return api_response($product);
        },
        'permission_callback' => '__return_true'
    ]);

    // CRÉATION (JWT)
    register_rest_route('app', '/products', [
        'methods' => 'POST',
        'callback' => function ($request) {

            $data = [
                'title' => $request->get_param('title'),
                'description' => $request->get_param('description'),
                'price' => $request->get_param('price')
            ];

            if (!$data['title'] || !$data['price']) {
                return api_response(null, 'Titre et prix requis', 400);
            }

            return api_response(
                ProductService::create($data),
                'Produit créé',
                201
            );
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // MISE À JOUR (JWT)
    register_rest_route('app', '/products/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => function ($request) {

            $product = ProductService::update(
                $request['id'],
                $request->get_params()
            );

            if (!$product) {
                return api_response(null, 'Produit introuvable', 404);
            }

            return api_response($product, 'Produit mis à jour');
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

    // SUPPRESSION (JWT)
    register_rest_route('app', '/products/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => function ($request) {

            if (!ProductService::delete($request['id'])) {
                return api_response(null, 'Produit introuvable', 404);
            }

            return api_response(null, 'Produit supprimé');
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);
}
