<?php

use Stripe\Stripe;
use Stripe\PaymentIntent;

require_once __DIR__ . '/../services/PaymentService.php';
require_once __DIR__ . '/../services/OrderService.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../middlewares/auth.php';

function register_payment_routes() {

    // Création PaymentIntent Stripe
    register_rest_route('app', '/pay/stripe-intent', [
        'methods' => 'POST',
        'callback' => function($req) {

            $order_id = intval($req->get_param('order_id'));
            if (!$order_id) {
                return api_response(null, 'order_id manquant', 400);
            }

            $order = OrderService::get_order($order_id);
            if (!$order) return api_response(null, 'Commande introuvable', 404);

            // Montant en centimes (Stripe utilise la plus petite unité)
            $amount = intval($order['total'] * 100); 

            // Initialisation Stripe
            Stripe::setApiKey(STRIPE_SECRET_KEY);

            try {
                $intent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'usd', // ou 'xaf' si disponible
                    'metadata' => [
                        'order_id' => $order_id
                    ]
                ]);

                return api_response([
                    'client_secret' => $intent->client_secret,
                    'order_id' => $order_id
                ], 'PaymentIntent créé');

            } catch (\Exception $e) {
                return api_response(null, 'Erreur Stripe: '.$e->getMessage(), 500);
            }
        },
        'permission_callback' => 'app_api_auth_middleware'
    ]);

}
