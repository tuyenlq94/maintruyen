<?php

namespace T_Shop\Repositories;

class ChapterRepository extends BaseRepository
{
    protected $table = 'story_chapter';

    public function getByStory($storyId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    chapter_number,
                    slug,
                    title,
                    view_count,
                    published_at
                FROM {$this->table()}
                WHERE story_id = %d
                ORDER BY chapter_number ASC
            ", $storyId)
        );
    }

    public function findByStoryAndSlug(
        $storyId,
        $slug
    ) {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare("
                SELECT *
                FROM {$this->table()}
                WHERE story_id = %d
                AND slug = %s
                LIMIT 1
            ", $storyId, $slug)
        );
    }

    public function existsChapterNumber(
        $storyId,
        $chapterNumber
    ) {
        global $wpdb;

        return (bool) $wpdb->get_var(
            $wpdb->prepare("
                SELECT COUNT(*)
                FROM {$this->table()}
                WHERE story_id = %d
                AND chapter_number = %f
            ", $storyId, $chapterNumber)
        );
    }

    public function latestByStory(
        $storyId,
        $limit = 20
    ) {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    chapter_number,
                    slug,
                    title
                FROM {$this->table()}
                WHERE story_id = %d
                ORDER BY chapter_number DESC
                LIMIT %d
            ", $storyId, $limit)
        );
    }

    public function incrementViews($chapterId)
    {
        global $wpdb;

        return $wpdb->query(
            $wpdb->prepare("
                UPDATE {$this->table()}
                SET view_count = view_count + 1
                WHERE id = %d
            ", $chapterId)
        );
    }
}