<?php

namespace T_Shop\Middleware;

class NormalizeUrl
{
    public static function handle()
    {
        $requestUri = $_SERVER['REQUEST_URI'];

        $normalized = strtolower($requestUri);

        if ($requestUri !== $normalized) {

            wp_redirect(
                home_url($normalized),
                301
            );

            exit;
        }
    }
}