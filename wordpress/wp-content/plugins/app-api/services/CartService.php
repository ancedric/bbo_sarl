<?php

class CartService {

    private static function get_user_id() {
        return get_current_user_id();
    }

    public static function get_cart() {
        $cart = get_user_meta(self::get_user_id(), 'app_cart', true);
        return $cart ?: [];
    }

    public static function save_cart($cart) {
        update_user_meta(self::get_user_id(), 'app_cart', array_values($cart));
    }

    public static function add_item($product_id, $quantity = 1) {

        $cart = self::get_cart();

        foreach ($cart as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                self::save_cart($cart);
                return $cart;
            }
        }

        $cart[] = [
            'product_id' => $product_id,
            'quantity' => max(1, intval($quantity))
        ];

        self::save_cart($cart);
        return $cart;
    }

    public static function update_item($product_id, $quantity) {

        $cart = self::get_cart();

        foreach ($cart as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] = max(1, intval($quantity));
            }
        }

        self::save_cart($cart);
        return $cart;
    }

    public static function remove_item($product_id) {

        $cart = array_filter(
            self::get_cart(),
            fn($item) => $item['product_id'] != $product_id
        );

        self::save_cart($cart);
        return $cart;
    }

    public static function clear() {
        delete_user_meta(self::get_user_id(), 'app_cart');
        return [];
    }
}
