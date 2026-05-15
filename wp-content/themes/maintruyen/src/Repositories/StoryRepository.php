<?php

namespace T_Shop\Repositories;

class StoryRepository extends BaseRepository
{
    protected $table = 'story';

	public function paginate($page = 1, $perPage = 20)
    {
        global $wpdb;

        $offset = ($page - 1) * $perPage;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    slug,
                    title,
                    thumbnail,
                    status,
                    total_views,
                    created_at
                FROM {$this->table()}
                ORDER BY id DESC
                LIMIT %d OFFSET %d
            ", $perPage, $offset)
        );
    }

    public function findBySlug($slug)
    {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare("
                SELECT *
                FROM {$this->table()}
                WHERE slug = %s
                LIMIT 1
            ", $slug)
        );
    }

    public function latestUpdated($limit = 20)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    title,
                    slug,
                    thumbnail,
                    latest_chapter_id,
                    updated_at
                FROM {$this->table()}
                ORDER BY updated_at DESC
                LIMIT %d
            ", $limit)
        );
    }

    public function trending($limit = 20)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT
                    id,
                    title,
                    slug,
                    thumbnail,
                    total_views
                FROM {$this->table()}
                ORDER BY total_views DESC
                LIMIT %d
            ", $limit)
        );
    }

    public function incrementViews($storyId)
    {
        global $wpdb;

        return $wpdb->query(
            $wpdb->prepare("
                UPDATE {$this->table()}
                SET total_views = total_views + 1
                WHERE id = %d
            ", $storyId)
        );
    }

	public function existsBySlug($slug)
    {
        global $wpdb;

        return (bool) $wpdb->get_var(
            $wpdb->prepare("
                SELECT COUNT(*)
                FROM {$this->table()}
                WHERE slug = %s
            ", $slug)
        );
    }
}