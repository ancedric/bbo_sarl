<?php

use Stripe\Stripe;
use Stripe\Webhook;

require_once __DIR__ . '/../services/PaymentService.php';
require_once __DIR__ . '/../helpers/response.php';

function register_webhook_routes() {

    register_rest_route('app', '/webhook/stripe', [
        'methods' => 'POST',
        'callback' => function ($request) {

            $payload = $request->get_body();
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

            Stripe::setApiKey(STRIPE_SECRET_KEY);

            try {
                $event = Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    STRIPE_WEBHOOK_SECRET
                );
            } catch (\Exception $e) {
                return api_response(null, 'Signature Stripe invalide', 400);
            }

            switch ($event->type) {

                case 'payment_intent.succeeded':

                    $intent = $event->data->object;
                    $order_id = $intent->metadata->order_id ?? null;

                    if ($order_id) {
                        PaymentService::mark_as_paid(
                            intval($order_id),
                            'stripe',
                            $intent->id
                        );
                    }
                    break;

                case 'payment_intent.payment_failed':

                    $intent = $event->data->object;
                    $order_id = $intent->metadata->order_id ?? null;

                    if ($order_id) {
                        PaymentService::fail(
                            intval($order_id),
                            $intent->last_payment_error->message ?? 'Paiement échoué'
                        );
                    }
                    break;
            }

            return api_response(['received' => true]);
        },
        'permission_callback' => '__return_true'
    ]);
}
