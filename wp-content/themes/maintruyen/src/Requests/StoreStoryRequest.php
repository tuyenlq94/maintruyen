<?php

namespace T_Shop\Requests;

class StoreStoryRequest
{
    public static function validate(array $data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }

        if (mb_strlen($data['title']) > 255) {
            $errors['title'] = 'Title too long';
        }

        return [
            'success' => empty($errors),
            'errors' => $errors,
        ];
    }
}