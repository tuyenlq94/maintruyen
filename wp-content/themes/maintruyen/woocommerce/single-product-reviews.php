<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments" class="custom-comment">
		<h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			?>
			 <img src="<?php echo get_template_directory_uri() . '/images/start.png' ?>" width="14" height="14">
			<span class="text"><?php esc_html_e( 'Reviews', 'woocommerce' ); ?></span>
			<span class="count"><?php echo $count; ?></span>
		</h2>

		<?php if ( have_comments() ) : ?>
			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', [ 'callback' => 'woocommerce_comments' ] ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						[
							'prev_text' => '<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.3125 1.375L1.6875 7L7.3125 12.625" stroke="#1C1C1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>',
							'next_text' => '<svg width="9" height="14" viewBox="0 0 9 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M1.6875 1.375L7.3125 7L1.6875 12.625" stroke="#1C1C1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>',

						]
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
				$commenter    = wp_get_current_commenter();
				$comment_form = [
					/* translators: %s is product title */
					'title_reply'          => have_comments() ? '<img src="' . get_template_directory_uri() . '/images/pen.png' . '"' . 'width="14" height="14"' . '><span>Add your review</span>' : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
					/* translators: %s is product title */
					'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
					'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
					'title_reply_after'    => '</span>',
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'label_submit'         => esc_html__( 'Add review', 'woocommerce' ),
					'logged_in_as'         => '',
					'comment_field'        => '',
				];

				$name_email_required = (bool) get_option( 'require_name_email', 1 );
				$fields              = [
					'author' => [
						'label'       => __( 'Name', 'woocommerce' ),
						'type'        => 'text',
						'value'       => $commenter['comment_author'],
						'required'    => $name_email_required,
						'placeholder' => 'Your name',
					],
					'email'  => [
						'label'       => __( 'Email', 'woocommerce' ),
						'type'        => 'email',
						'value'       => $commenter['comment_author_email'],
						'required'    => $name_email_required,
						'placeholder' => 'Your email',
					],
				];

				$comment_form['fields'] = [];

				foreach ( $fields as $key => $field ) {
					$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
					$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

					if ( $field['required'] ) {
						$field_html .= '&nbsp;<span class="required">*</span>';
					}

					$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

					$comment_form['fields'][ $key ] = $field_html;
				}

				$account_page_url = wc_get_page_permalink( 'myaccount' );
				if ( $account_page_url ) {
					/* translators: %s opening and closing link tags respectively */
					$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
				}

				if ( wc_review_ratings_enabled() ) {
					$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
					</select></div>';
				}

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required placeholder="Your review"></textarea></p>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
