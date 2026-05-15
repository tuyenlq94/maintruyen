<?php
namespace TitanWeb;

class ImageCache {
	public static function cache_images( array $image_ids ): void {
		update_meta_cache( 'post', $image_ids );
		self::cache_posts( $image_ids );
	}

	public static function cache_post_thumbnails( array $posts ): void {
		$image_ids = array_map( function( $p ) {
			return get_post_thumbnail_id( $p );
		}, $posts );
		self::cache_images( $image_ids );
	}

	public static function cache_image_advanced( string $field_id ): void {
		$image_ids = get_post_meta( get_the_ID(), $field_id, false );
		self::cache_images( $image_ids );
	}

	private static function cache_posts( array $post_ids ): void {
		if ( empty( $post_ids ) ) {
			return;
		}

		$post_ids = implode( ',', $post_ids );

		global $wpdb;
		$sql   = "SELECT * FROM $wpdb->posts WHERE ID IN ($post_ids)";
		$posts = $wpdb->get_results( $sql );

		foreach ( $posts as $post ) {
			$post = sanitize_post( $post, 'raw' );
			wp_cache_add( $post->ID, $post, 'posts' );
		}
	}
}
