<?php

namespace T_Shop\Support;

class Url
{
    public static function story($story)
    {
        return home_url(
            '/truyen/' . $story->slug
        );
    }

    public static function chapter(
        $story,
        $chapter
    ) {

        return home_url(
            '/truyen/' .
            $story->slug .
            '/' .
            $chapter->slug
        );
    }

    public static function genre($genre)
    {
        return home_url(
            '/the-loai/' . $genre->slug
        );
    }
}