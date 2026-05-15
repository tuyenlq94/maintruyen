<?php
namespace T_Shop\Database;

class CreateDatabase {
	const DB_VERSION = '1.0.0';
	
	public function __construct() {
		add_action( 'after_switch_theme', [ $this, 'my_story_create_tables' ] );
		add_action('init', [$this, 'maybe_upgrade_database']);
	}

	public function my_story_create_tables()
	{
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		$tables = [];

		/*
		|--------------------------------------------------------------------------
		| STORY TABLE
		|--------------------------------------------------------------------------
		*/
		$story_table = $wpdb->prefix . 'story';
		$tables[] = "CREATE TABLE {$story_table} (
			id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

			slug VARCHAR(255) NOT NULL UNIQUE,
    		title VARCHAR(255) NOT NULL,

			alternative_titles TEXT NULL,
			description LONGTEXT NULL,

			thumbnail VARCHAR(500) NULL,
    		banner VARCHAR(500) NULL,

			author_name VARCHAR(255) NULL,
    		artist_name VARCHAR(255) NULL,

			story_type TINYINT NOT NULL DEFAULT 1, /* 1=novel, 2=manga */

			status TINYINT NOT NULL DEFAULT 1, /* 1=ongoing, 2=completed, 3=hiatus */

			language VARCHAR(20) DEFAULT 'vi',

			total_chapters INT UNSIGNED DEFAULT 0,

			total_views BIGINT UNSIGNED DEFAULT 0,

			rating_avg DECIMAL(3,2) DEFAULT 0,
    		rating_count INT UNSIGNED DEFAULT 0,

			latest_chapter_id BIGINT UNSIGNED NULL,
			published_at DATETIME NULL,

			created_at DATETIME NOT NULL,
    		updated_at DATETIME NOT NULL,

			INDEX idx_slug(slug),
			INDEX idx_status(status),
			INDEX idx_views(total_views),
			INDEX idx_latest_chapter(latest_chapter_id),
			FULLTEXT idx_title(title)

		) {$charset_collate};";
		
		/*
		|--------------------------------------------------------------------------
		| CHAPTER TABLE
		|--------------------------------------------------------------------------
		*/
		$chapter_table = $wpdb->prefix . 'story_chapter';
		$tables[] = "CREATE TABLE {$chapter_table} (
			id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

			story_id BIGINT UNSIGNED NOT NULL,

			chapter_number DECIMAL(10,2) NOT NULL,

			slug VARCHAR(255) NOT NULL,

			title VARCHAR(255) NULL,

			content LONGTEXT NULL,

			content_type TINYINT DEFAULT 1, /* 1=text, 2=image */

			image_count INT DEFAULT 0,

			view_count BIGINT UNSIGNED DEFAULT 0,

			previous_chapter_id BIGINT UNSIGNED NULL,
			next_chapter_id BIGINT UNSIGNED NULL,

			published_at DATETIME NULL,

			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,

			UNIQUE KEY uniq_story_chapter(story_id, chapter_number),

			INDEX idx_story(story_id),
			INDEX idx_published(published_at),
			INDEX idx_views(view_count)

		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| GENRE TABLE
		|--------------------------------------------------------------------------
		*/
		 $genre_table = $wpdb->prefix . 'story_genre';

		$tables[] = "CREATE TABLE {$genre_table} (
			id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

			slug VARCHAR(255) UNIQUE,
			name VARCHAR(255),

			created_at DATETIME NOT NULL

		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| RELATIONSHIP TABLE
		|--------------------------------------------------------------------------
		*/
		$relationship_table = $wpdb->prefix . 'story_genre_relationship';

		$tables[] = "CREATE TABLE {$relationship_table} (
			story_id BIGINT UNSIGNED NOT NULL,
			genre_id BIGINT UNSIGNED NOT NULL,

			PRIMARY KEY(story_id, genre_id),

			INDEX idx_genre(genre_id)

		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| Bookmark TABLE
		|--------------------------------------------------------------------------
		*/
		$bookmark_table = $wpdb->prefix . 'story_bookmark';
		$tables[] = "CREATE TABLE {$bookmark_table} (
			user_id BIGINT UNSIGNED NOT NULL,
			story_id BIGINT UNSIGNED NOT NULL,

			latest_read_chapter_id BIGINT UNSIGNED NULL,

			created_at DATETIME NOT NULL,

			PRIMARY KEY(user_id, story_id),

			INDEX idx_story(story_id)

		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| History TABLE
		|--------------------------------------------------------------------------
		*/
		$history_table = $wpdb->prefix . 'story_history';
		$tables[] = "CREATE TABLE {$history_table} (
			id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

			user_id BIGINT UNSIGNED NULL,

			session_id VARCHAR(255) NULL,

			story_id BIGINT UNSIGNED NOT NULL,
			chapter_id BIGINT UNSIGNED NOT NULL,

			progress_percent TINYINT DEFAULT 0,

			last_read_at DATETIME NOT NULL,

			INDEX idx_user(user_id),
			INDEX idx_story(story_id),
			INDEX idx_last_read(last_read_at)

		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| Trending TABLE
		|--------------------------------------------------------------------------
		*/
		$trending_table = $wpdb->prefix . 'story_views_daily';
		$tables[] = "CREATE TABLE {$trending_table} (
			story_id BIGINT UNSIGNED NOT NULL,

			view_date DATE NOT NULL,

			total_views INT UNSIGNED DEFAULT 0,

			PRIMARY KEY(story_id, view_date),

			INDEX idx_date(view_date)
		) {$charset_collate};";

		/*
		|--------------------------------------------------------------------------
		| Chapter image TABLE
		|--------------------------------------------------------------------------
		*/
		$chapter_images_table = $wpdb->prefix . 'story_chapter_images';
		$tables[] = "CREATE TABLE {$chapter_images_table} (
			id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

			chapter_id BIGINT UNSIGNED NOT NULL,

			image_order INT NOT NULL,

			image_url VARCHAR(500) NOT NULL,

			width INT NULL,
			height INT NULL,

			created_at DATETIME NOT NULL,

			INDEX idx_chapter(chapter_id)
		) {$charset_collate};";

		foreach ($tables as $sql) {
			dbDelta($sql);
		}

		update_option(
			't_shop_story_db_version',
			self::DB_VERSION
		);
	}

	public function maybe_upgrade_database()
	{
		
		$installed_version = get_option('t_shop_story_db_version');

		if ($installed_version !== self::DB_VERSION) {
			$this->my_story_create_tables();
		}
	}
}