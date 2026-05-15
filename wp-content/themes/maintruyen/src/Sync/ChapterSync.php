<?php

namespace T_Shop\Sync;

use T_Shop\Services\ChapterService;

class ChapterSync
{
    public function __construct()
    {
        add_action( 'save_post_chapter', [$this, 'sync'], 10, 3);
    }

    public function sync( $postId, $post, $update ) {   
        if (wp_is_post_revision($postId)) {
            return;
        }

        $service = new ChapterService();

        $service->syncFromPost($postId);
    }
}