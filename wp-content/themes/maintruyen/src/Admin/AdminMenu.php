<?php

namespace T_Shop\Admin;

class AdminMenu
{
    public function __construct()
    {
        add_action(
            'admin_menu',
            [$this, 'registerMenus']
        );
    }

    public function registerMenus()
    {
        add_menu_page(
            'Stories',
            'Stories',
            'manage_options',
            'tshop-stories',
            [$this, 'storyIndexPage'],
            'dashicons-book',
            5
        );

        add_submenu_page(
            'tshop-stories',
            'Add Story',
            'Add Story',
            'manage_options',
            'tshop-add-story',
            [$this, 'storyCreatePage']
        );
    }

    public function storyIndexPage()
    {
        include get_theme_file_path(
            'templates/admin/story/index.php'
        );
    }

    public function storyCreatePage()
    {
        include get_theme_file_path(
            'templates/admin/story/create.php'
        );
    }
}