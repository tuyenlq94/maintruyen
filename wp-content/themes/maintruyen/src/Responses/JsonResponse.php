<?php

namespace T_Shop\Responses;

class JsonResponse
{
    public static function success($data = [])
    {
        wp_send_json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public static function error($errors = [], $status = 400)
    {
        wp_send_json([
            'success' => false,
            'errors' => $errors,
        ], $status);
    }
}