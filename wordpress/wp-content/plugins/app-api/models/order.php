<?php

class Order {

    public static function create_post($user_id) {

        return wp_insert_post([
            'post_type'   => 'app_order',
            'post_status' => 'publish',
            'post_title'  => 'Commande - ' . uniqid(),
            'post_author' => $user_id
        ]);
    }
}
