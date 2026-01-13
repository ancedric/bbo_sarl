<?php

class Product {
    public $id;
    public $title;
    public $description;
    public $price;
    public $image;

    public function __construct($post) {
        $this->id = $post->ID;
        $this->title = $post->post_title;
        $this->description = $post->post_content;
        $this->price = get_post_meta($post->ID, 'price', true);
        $this->image = get_the_post_thumbnail_url($post->ID);
    }
}
