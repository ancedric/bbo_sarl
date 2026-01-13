<?php
/**
 * Plugin Name: App API
 */

defined('ABSPATH') or die;

require_once __DIR__ . '/routes/auth.php';
require_once __DIR__ . '/routes/products.php';
require_once __DIR__ . '/services/ProductService.php';
require_once __DIR__ . '/routes/cart.php';
require_once __DIR__ . '/routes/orders.php';
require_once __DIR__ . '/routes/payments.php';
require_once __DIR__ . '/routes/webhooks.php';
require_once __DIR__ . '/stripe/stripe-php/init.php';

add_action('init', ['ProductService', 'register_post_type']);
add_action('init', function () {
    register_post_type('app_order', [
        'label' => 'Commandes',
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'author']
    ]);
});

add_action('rest_api_init', 'register_auth_routes');
add_action('rest_api_init', 'register_product_routes');
add_action('rest_api_init', 'register_cart_routes');
add_action('rest_api_init', 'register_order_routes');
add_action('rest_api_init', 'register_payment_routes');
add_action('rest_api_init', 'register_webhook_routes');




add_action('rest_api_init', function () {
  header("Access-Control-Allow-Origin: http://localhost:5173");
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
  header("Access-Control-Allow-Headers: Content-Type, Authorization");
});
