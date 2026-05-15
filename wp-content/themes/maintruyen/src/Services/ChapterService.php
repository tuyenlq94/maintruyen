<?php

namespace T_Shop\Services;

use T_Shop\Repositories\ChapterRepository;
use T_Shop\Repositories\MangaImageRepository;

class ChapterService
{
    protected $chapters;

    protected $images;

    public function __construct()
    {
        $this->chapters = new ChapterRepository();

        $this->images = new MangaImageRepository();
    }

    public function create(array $data)
    {
        $slug = 'chuong-' . $data['chapter_number'];

        if (
            $this->chapters->existsChapterNumber(
                $data['story_id'],
                $data['chapter_number']
            )
        ) {
            throw new \Exception(
                'Chapter already exists'
            );
        }

        $chapterId = $this->chapters->create([
            'story_id' => (int) $data['story_id'],
            'chapter_number' => $data['chapter_number'],
            'slug' => $slug,
            'title' => sanitize_text_field(
                $data['title'] ?? ''
            ),
            'content' => wp_kses_post(
                $data['content'] ?? ''
            ),
            'content_type' => (int) (
                $data['content_type'] ?? 1
            ),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Manga Images
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data['images'])
            && is_array($data['images'])
        ) {

            foreach ($data['images'] as $index => $image) {

                $this->images->create([
                    'chapter_id' => $chapterId,
                    'image_order' => $index + 1,
                    'image_url' => esc_url_raw($image),
                    'created_at' => current_time('mysql'),
                ]);
            }
        }

        return $chapterId;
    }

    public function detail(
        $storyId,
        $slug
    ) {
        $chapter = $this->chapters
            ->findByStoryAndSlug(
                $storyId,
                $slug
            );

        if (!$chapter) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Manga Images
        |--------------------------------------------------------------------------
        */

        if ((int) $chapter->content_type === 2) {

            $chapter->images = $this->images
                ->getByChapter($chapter->id);
        }

        return $chapter;
    }

    public function delete($id)
    {
        $this->images->deleteByChapter($id);

        return $this->chapters->delete($id);
    }
}