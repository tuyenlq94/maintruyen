<?php

namespace T_Shop\Repositories;

class MangaImageRepository extends BaseRepository
{
    protected $table = 'story_chapter_images';

    public function getByChapter($chapterId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    image_order,
                    image_url,
                    width,
                    height
                FROM {$this->table()}
                WHERE chapter_id = %d
                ORDER BY image_order ASC
            ", $chapterId)
        );
    }

    public function deleteByChapter($chapterId)
    {
        global $wpdb;

        return $wpdb->delete(
            $this->table(),
            [
                'chapter_id' => $chapterId
            ]
        );
    }
}