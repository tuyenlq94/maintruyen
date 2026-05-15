<?php

namespace T_Shop\Controllers;

use T_Shop\Services\StoryService;
use T_Shop\Services\ChapterService;

class ReaderController
{
    protected $stories;

    protected $chapters;

    public function __construct()
    {
        $this->stories = new StoryService();

        $this->chapters = new ChapterService();
    }

    public function show(
        $storySlug,
        $chapterSlug
    ) {

        $story = $this->stories
            ->detail($storySlug);

        if (!$story) {
            return $this->abort404();
        }

        $chapter = $this->chapters
            ->detail(
                $story->id,
                $chapterSlug
            );

        if (!$chapter) {
            return $this->abort404();
        }

        include get_theme_file_path(
            'templates/reader/show.php'
        );
    }

    protected function abort404()
    {
        global $wp_query;

        $wp_query->set_404();

        status_header(404);

        exit;
    }
}