<?php

namespace T_Shop\Routing;

class Router
{
    public static function init()
    {
        add_action(
            'init',
            [self::class, 'registerRoutes']
        );

        add_filter(
            'query_vars',
            [self::class, 'registerQueryVars']
        );

        add_action(
            'template_redirect',
            [self::class, 'dispatch']
        );
    }

    public static function registerRoutes()
    {
        /*
        |--------------------------------------------------------------------------
        | Story
        |--------------------------------------------------------------------------
        */

        add_rewrite_rule(
            '^truyen/([^/]*)/?$',
            'index.php?story_slug=$matches[1]',
            'top'
        );

        /*
        |--------------------------------------------------------------------------
        | Chapter
        |--------------------------------------------------------------------------
        */

        add_rewrite_rule(
            '^truyen/([^/]*)/([^/]*)/?$',
            'index.php?story_slug=$matches[1]&chapter_slug=$matches[2]',
            'top'
        );

        /*
        |--------------------------------------------------------------------------
        | Genre
        |--------------------------------------------------------------------------
        */

        add_rewrite_rule(
            '^the-loai/([^/]*)/?$',
            'index.php?genre_slug=$matches[1]',
            'top'
        );
    }

    public static function registerQueryVars($vars)
    {
        $vars[] = 'story_slug';

        $vars[] = 'chapter_slug';

        $vars[] = 'genre_slug';

        return $vars;
    }

    public static function dispatch()
    {
        /*
        |--------------------------------------------------------------------------
        | Chapter Page
        |--------------------------------------------------------------------------
        */

        if (
            get_query_var('story_slug')
            && get_query_var('chapter_slug')
        ) {

            $controller = new \T_Shop\Controllers\ReaderController();

            $controller->show(
                get_query_var('story_slug'),
                get_query_var('chapter_slug')
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Story Page
        |--------------------------------------------------------------------------
        */

        if (get_query_var('story_slug')) {

            $controller = new \T_Shop\Controllers\StoryController();

            $controller->show(
                get_query_var('story_slug')
            );

            exit;
        }

        /*
        |--------------------------------------------------------------------------
        | Genre Page
        |--------------------------------------------------------------------------
        */

        if (get_query_var('genre_slug')) {

            $controller = new \T_Shop\Controllers\GenreController();

            $controller->show(
                get_query_var('genre_slug')
            );

            exit;
        }
    }
}