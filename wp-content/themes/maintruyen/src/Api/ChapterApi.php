<?php

namespace T_Shop\Api;

use T_Shop\Controllers\ChapterController;

class ChapterApi
{
    public function __construct()
    {
        add_action(
            'rest_api_init',
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes()
    {
        register_rest_route(
            'tshop/v1',
            '/chapters',
            [
                [
                    'methods' => 'POST',
                    'callback' => [
                        new ChapterController(),
                        'store'
                    ],
                    'permission_callback' => [
                        $this,
                        'adminPermission'
                    ],
                ],
            ]
        );

        register_rest_route(
            'tshop/v1',
            '/chapters/(?P<id>\d+)',
            [
                [
                    'methods' => 'DELETE',
                    'callback' => [
                        new ChapterController(),
                        'destroy'
                    ],
                    'permission_callback' => [
                        $this,
                        'adminPermission'
                    ],
                ],
            ]
        );
    }

    public function adminPermission()
    {
        return current_user_can(
            'manage_options'
        );
    }
}