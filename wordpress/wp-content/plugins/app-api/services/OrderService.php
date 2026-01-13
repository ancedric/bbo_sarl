<?php

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/CartService.php';
require_once __DIR__ . '/ProductService.php';

class OrderService {

    public static function create_from_cart() {

        $user_id = get_current_user_id();
        $cart = CartService::get_cart();

        if (empty($cart)) {
            return new WP_Error('empty_cart', 'Panier vide', ['status' => 400]);
        }

        $order_id = Order::create_post($user_id);

        if (is_wp_error($order_id)) {
            return $order_id;
        }

        $items = [];
        $total = 0;

        foreach ($cart as $item) {
            $product = ProductService::get_by_id($item['product_id']);
            if (!$product) continue;

            $line_total = $product['price'] * $item['quantity'];
            $total += $line_total;

            $items[] = [
                'product_id' => $item['product_id'],
                'name'       => $product['name'],
                'price'      => $product['price'],
                'quantity'   => $item['quantity'],
                'total'      => $line_total
            ];
        }

        update_post_meta($order_id, 'items', $items);
        update_post_meta($order_id, 'total', $total);
        update_post_meta($order_id, 'status', 'pending');
        update_post_meta($order_id, 'created_at', current_time('mysql'));

        CartService::clear();

        return [
            'order_id' => $order_id,
            'total'    => $total,
            'status'   => 'pending'
        ];
    }

    public static function get_user_orders() {

        $orders = get_posts([
            'post_type'   => 'app_order',
            'author'      => get_current_user_id(),
            'numberposts' => -1
        ]);

        return array_map(function ($order) {

            return [
                'id'     => $order->ID,
                'total'  => get_post_meta($order->ID, 'total', true),
                'status' => get_post_meta($order->ID, 'status', true),
                'date'   => $order->post_date
            ];

        }, $orders);
    }

    public static function get_order($order_id) {

        $order = get_post($order_id);

        if (!$order || $order->post_author != get_current_user_id()) {
            return null;
        }

        return [
            'id'     => $order->ID,
            'items'  => get_post_meta($order_id, 'items', true),
            'total'  => get_post_meta($order_id, 'total', true),
            'status' => get_post_meta($order_id, 'status', true),
            'date'   => $order->post_date
        ];
    }
}
