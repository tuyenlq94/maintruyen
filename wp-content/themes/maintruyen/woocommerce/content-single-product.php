<?php
use Hirosart\Image;
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<?php
	/**
	 * Hook: woocommerce_before_single_product_summary.
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">
		<div class="selling-fast">
			<img src="<?php echo get_template_directory_uri() . '/images/carts.png' ?>" width="20" height="20">
			<div class="text">Selling fast!&nbsp; <span class='random'>15</span>+ people have this in their carts.</div>
		</div>
		<div class="product-meta">
		<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
		</div>
		<?php
		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
		do_action( 'woocommerce_single_product_summary' );
		$id        = get_the_ID();
		$home      = get_page_by_path( 'home' );
		$title     = get_post_meta( $id, 'title_package', true );
		$package   = get_post_meta( $id, 'package', true );
		$questions = get_post_meta( $home->ID, 'question_list', true );
		?>
		<div class="single-tab">
			<?php if ( $package ) { ?>
				<div class="tab">
					<label class="tab-label active"><?php echo $title; ?></label>
					<div class="tab-content"><?= wp_kses_post( $package )?></div>
				</div>
			<?php } ?>
			<?php if ( get_the_content() ) { ?>
				<div class="tab">
					<label class="tab-label active"><?php echo esc_html__( 'Description', 'hirosart' ); ?></label>
					<div class="tab-content">
						<div class="desc-product"><?php the_content() ?></div>
						<button class="product_btn">See more</button>
					</div>
				</div>
			<?php } ?>
			<?php if ( get_option( 'setting_product' ) ) { ?>
				<div class="tab">
					<label class="tab-label"><?php echo esc_html__( 'Shipping info', 'hirosart' ); ?></label>
					<div class="tab-content"><?php echo get_option( 'setting_product' )['shipping-field']; ?></div>
				</div>
			<?php } ?>
			<?php if ( $questions ) { ?>
				<div class="tab">
					<label class="tab-label"><?php echo esc_html__( 'Frequently Asked Questions', 'hirosart' ); ?></label>
					<div class="tab-content">
						<div class="questions__list tabs">
							<?php
							foreach ( $questions as $question ) :

								?>
								<div class="tab">
									<label class="tab-label"><span><?php Image::icon( 'question' ); ?><span><?= esc_html( $question['name'] );?></span></span></label>
									<div class="tab-content"><?= wp_kses_post( $question['content'] )?></div>
								</div>
								<?php
								endforeach;
							?>
						</div>
					</div>
				</div>
			<?php } ?>

		</div>
		<div class="popup-ques">
			<label><?php echo esc_html__( 'You have a question', 'hirosart' ) ?></label>
			<button class='btn'><?php echo esc_html__( 'Write your question', 'hirosart' ) ?></button>
			<div class="modal">
				<div class="modal-content">
					<span class="close">&times;</span>
					<div class="contact__form"><?php echo do_shortcode( '[fluentform id="1"]' ); ?></div>
				</div>
			</div>
		</div>
			</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	comments_template();
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
