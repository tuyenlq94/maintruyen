<?php
namespace T_Shop;

use TitanWeb\GoToTop;
use T_Shop\Database\CreateDatabase;

require __DIR__ . '/vendor/autoload.php';

new Loader;
//new Settings;
//new Fields;
new Menu;
new GoToTop;

new CreateDatabase;

new \T_Shop\Api\StoryApi();
\T_Shop\Routing\Router::init();

//new \T_Shop\Admin\AdminMenu();

new \T_Shop\PostTypes\StoryPostType();
new \T_Shop\PostTypes\ChapterPostType();
new \T_Shop\Sync\StorySync();
new \T_Shop\Sync\ChapterSync();