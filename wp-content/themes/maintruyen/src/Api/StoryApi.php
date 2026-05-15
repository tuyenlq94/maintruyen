<?php

namespace T_Shop\Api;

use T_Shop\Controllers\StoryController;
use T_Shop\Repositories\StoryRepository;

class StoryApi {

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes()
    {
        register_rest_route('tshop/v1', '/stories', [
            [
                'methods' => 'GET',
                'callback' => [new StoryController(), 'index'],
                'permission_callback' => '__return_true',
            ],
            [
                'methods' => 'POST',
                'callback' => [new StoryController(), 'store'],
                'permission_callback' => [$this, 'adminPermission'],
            ],
        ]);

		register_rest_route(
            'tshop/v1',
            '/stories/(?P<id>\d+)',
            [
                [
                    'methods' => 'PUT',
                    'callback' => [new StoryController(), 'update'],
                    'permission_callback' => [$this, 'adminPermission'],
                ],
                [
                    'methods' => 'DELETE',
                    'callback' => [new StoryController(), 'destroy'],
                    'permission_callback' => [$this, 'adminPermission'],
                ],
            ]
        );
    }

    public function adminPermission()
    {
        return current_user_can('manage_options');
    }
}