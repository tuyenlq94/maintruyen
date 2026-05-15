<?php

namespace T_Shop\Services;

use T_Shop\Repositories\StoryRepository;

class StoryService
{
    protected $stories;

    public function __construct()
    {
        $this->stories = new StoryRepository();
    }

    public function create(array $data) {
        $slug = sanitize_title($data['title']);

        if ($this->stories->existsBySlug($slug)) {

            $slug .= '-' . time();
        }

        return $this->stories->create([
            'slug' => $slug,
            'title' => sanitize_text_field($data['title']),
            'description' => wp_kses_post($data['description'] ?? ''),
            'thumbnail' => esc_url_raw($data['thumbnail'] ?? ''),
            'author_name' => sanitize_text_field($data['author_name'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'story_type' => (int) ($data['story_type'] ?? 1),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ]);
    }

	public function update($id, array $data)
    {
        return $this->stories->update($id, [
            'title' => sanitize_text_field($data['title']),
            'description' => wp_kses_post($data['description'] ?? ''),
            'thumbnail' => esc_url_raw($data['thumbnail'] ?? ''),
            'author_name' => sanitize_text_field($data['author_name'] ?? ''),
            'status' => (int) ($data['status'] ?? 1),
            'updated_at' => current_time('mysql'),
        ]);
    }

	public function delete($id)
    {
        return $this->stories->delete($id);
    }

    public function detail($slug)
    {
        return $this->stories->findBySlug($slug);
    }

    public function latest()
    {
        return $this->stories->latestUpdated();
    }

    public function trending()
    {
        return $this->stories->trending();
    }

	public function list($page = 1)
    {
        return $this->stories->paginate($page);
    }

	public function syncFromPost($postId)
	{
		$post = get_post($postId);

		$slug = $post->post_name;

		$data = [

			'slug' => $slug,

			'title' => $post->post_title,

			'description' => $post->post_content,

			'thumbnail' => get_the_post_thumbnail_url(
				$postId,
				'full'
			),

			'author_name' => get_field(
				'author_name',
				$postId
			),

			'status' => (int) get_field(
				'status',
				$postId
			),

			'updated_at' => current_time('mysql'),
		];

		$existing = $this->stories
			->findBySlug($slug);

		if ($existing) {

			$this->stories->update(
				$existing->id,
				$data
			);

		} else {

			$data['created_at']
				= current_time('mysql');

			$this->stories->create($data);
		}
	}
}