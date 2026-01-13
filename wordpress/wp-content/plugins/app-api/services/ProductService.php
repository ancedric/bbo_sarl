<?php

class ProductService {

    public static function register_post_type() {
        register_post_type('product', [
            'label' => 'Produits',
            'public' => true,
            'show_in_rest' => false,
            'supports' => ['title', 'editor', 'thumbnail'],
            'has_archive' => true
        ]);
    }

    private static function format($post) {
        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'price' => get_post_meta($post->ID, 'price', true),
            'image' => get_the_post_thumbnail_url($post->ID)
        ];
    }

    public static function get_all() {
        $query = new WP_Query([
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ]);

        return array_map([self::class, 'format'], $query->posts);
    }

    public static function get_by_id($id) {
        $post = get_post($id);

        if (!$post || $post->post_type !== 'product') {
            return null;
        }

        return self::format($post);
    }

    public static function create($data) {

        $post_id = wp_insert_post([
            'post_type' => 'product',
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => wp_kses_post($data['description']),
            'post_status' => 'publish'
        ]);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        update_post_meta($post_id, 'price', floatval($data['price']));

        return self::get_by_id($post_id);
    }

    public static function update($id, $data) {

        $post = get_post($id);
        if (!$post || $post->post_type !== 'product') {
            return null;
        }

        wp_update_post([
            'ID' => $id,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => wp_kses_post($data['description'])
        ]);

        if (isset($data['price'])) {
            update_post_meta($id, 'price', floatval($data['price']));
        }

        return self::get_by_id($id);
    }

    public static function delete($id) {

        $post = get_post($id);
        if (!$post || $post->post_type !== 'product') {
            return false;
        }

        wp_delete_post($id, true);
        return true;
    }
}
