<?php

namespace T_Shop\PostTypes;

class StoryPostType
{
    public function __construct()
    {
        add_action(
            'init',
            [$this, 'register']
        );
    }

    public function register()
    {
        register_post_type('story', [

            'labels' => [
                'name' => 'Stories',
                'singular_name' => 'Story',
            ],

            'public' => true,

            'show_in_menu' => true,

            'menu_icon' => 'dashicons-book',

            'supports' => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
            ],

            'show_in_rest' => true,

            'rewrite' => false,
        ]);
    }
}