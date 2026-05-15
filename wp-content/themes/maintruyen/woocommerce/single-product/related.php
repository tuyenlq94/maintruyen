<?php
use Hirosart\Assets;
use Hirosart\ImageOptimization;

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$view_product   = isset( $_COOKIE['view-product'] ) ? json_decode( $_COOKIE['view-product'] ) : [];
$view_product[] = get_the_ID();
$query          = new WP_Query( [
	'posts_per_page' => 6,
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'orderby'        => 'post__in',
	'post__in'       => array_reverse( $view_product ),
	'no_found_rows'  => true,
] );
?>
<section class="best-sellers">
	<div class="container">
		<h2 class="title"><?= esc_html__( 'Recently Viewed Products', 'hirosart' )?></h2>
		<div class="best-sellers__products">
			<?php
			ImageOptimization::cache_post_thumbnails( $query->posts );

			while ( $query->have_posts() ) {
				$query->the_post();
				Assets::products();
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
<?php
$terms = get_the_terms( get_the_ID(), 'product_cat' );

$term_ids = [];
foreach ( $terms as $term ) {
	$term_ids[] = $term->term_id;
}

if ( ! $term_ids ) {
	return;
}
$term_ids = array_values( $term_ids );

$arg = [
	'post_type'      => 'product',
	'posts_per_page' => 6,
	'post_status'    => 'publish',
	'tax_query'      => [
		[
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $term_ids,
		],
	],
	'no_found_rows'  => true,
];

$wc_query = new WP_Query( $arg );
?>
<section class="best-sellers">
	<div class="container">
		<h2 class="title"><?= esc_html__( 'Related products', 'hirosart' )?></h2>
		<div class="best-sellers__products">
			<?php
			ImageOptimization::cache_post_thumbnails( $wc_query->posts );

			while ( $wc_query->have_posts() ) {
				$wc_query->the_post();
				Assets::products();
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
