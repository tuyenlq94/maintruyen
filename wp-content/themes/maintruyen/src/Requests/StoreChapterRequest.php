<?php

namespace T_Shop\Requests;

class StoreChapterRequest
{
    public static function validate(array $data)
    {
        $errors = [];

        if (empty($data['story_id'])) {
            $errors['story_id'] = 'Story required';
        }

        if (empty($data['chapter_number'])) {
            $errors['chapter_number'] = 'Chapter required';
        }

        return [
            'success' => empty($errors),
            'errors' => $errors,
        ];
    }
}