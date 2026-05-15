<?php

namespace T_Shop\PostTypes;

class ChapterPostType
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
        register_post_type('chapter', [

            'labels' => [
                'name' => 'Chapters',
                'singular_name' => 'Chapter',
            ],

            'public' => false,

            'show_ui' => true,

            'show_in_menu' => true,

            'supports' => [
                'title',
                'editor',
            ],

            'show_in_rest' => true,

            'rewrite' => false,
        ]);
    }
}