<?php
namespace T_Shop;

use WP_Post;
use WP_Query;

class WooCommercePermalinks {
	public function __construct() {
		// Xoá permalink base của sản phẩm.
		add_filter( 'post_type_link', [ $this, 'remove_product_slug' ], 10, 2 );
		add_action( 'pre_get_posts', [ $this, 'query_single_product' ], 99 );

		// Xoá permalink base của product category.
		add_filter( 'term_link', [ $this, 'remove_category_slug' ], 10, 3 );
		// add_action( 'init', [ $this, 'create_category_rewrite_rules' ] );
		add_action( 'create_term', [ $this, 'flush_category_rewrite_rules' ], 10, 2 );
	}

	public function remove_product_slug( string $post_link, WP_Post $post ): string {
		// Kiểm tra trong Settings > Permalinks, phần product permalink để là "product/".
		return $post->post_type === 'product' ? str_replace( '/product/', '/', $post_link ) : $post_link;
	}

	/**
	 * Đảm bảo khi truy cập vào trang single product không bị lỗi 404.
	 * Set "post_type" khi mà query có chứa "name" hoặc "pagename" (giống post & page).
	 */
	public function query_single_product( WP_Query $query ) {
		if ( ! $query->is_main_query() || 2 !== count( $query->query ) || ! isset( $query->query['page'] ) ) {
			return;
		}

		if ( ! empty( $query->query['name'] ) ) {
			$query->set( 'post_type', [ 'post', 'product', 'page' ] );
		} elseif ( ! empty( $query->query['pagename'] ) && false === strpos( $query->query['pagename'], '/' ) ) {
			$query->set( 'post_type', [ 'post', 'product', 'page' ] );

			// We also need to set the name query var since redirect_guess_404_permalink() relies on it.
			$query->set( 'name', $query->query['pagename'] );
		}
	}

	public function remove_category_slug( $url, $term, $taxonomy ) {
		// Kiểm tra trong Settings > Permalinks, phần product category permalink để là "product-category".
		return $taxonomy === 'product_cat' ? str_replace( '/product-category', '', $url ) : $url;
	}

	public function create_category_rewrite_rules( $flash = false ) {
		$terms = get_terms( [
			'taxonomy'   => 'product_cat',
			'post_type'  => 'product',
			'hide_empty' => false,
		] );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$siteurl = esc_url( home_url( '/' ) );
			foreach ( $terms as $term ) {
				$term_slug = $term->slug;
				$baseterm  = str_replace( $siteurl, '', get_term_link( $term->term_id, 'product_cat' ) );
				add_rewrite_rule( $baseterm . '?$', 'index.php?product_cat=' . $term_slug, 'top' );
				add_rewrite_rule( $baseterm . 'page/([0-9]{1,})/?$', 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]', 'top' );
				add_rewrite_rule( $baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]', 'top' );
			}
		}
		if ( $flash === true ) {
			flush_rewrite_rules( false );
		}
	}

	public function flush_category_rewrite_rules( $term_id, $taxonomy ) {
		$this->create_category_rewrite_rules( true );
	}
}
