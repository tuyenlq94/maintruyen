<?php
namespace T_Shop;

class WooCommerce {

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'add_cart_fragment' ] );
		add_filter( 'woocommerce_breadcrumb_defaults', [ $this, 'change_breadcrumbs_args' ] );
		add_filter( 'woocommerce_product_tabs', [ $this, 'remove_all_product_tabs' ], 98 );
		add_filter( 'woocommerce_format_sale_price', [ $this, 'output_prices_for_variables' ], 10, 3 );
		add_filter( 'woocommerce_catalog_orderby', [ $this, 'change_catalog_orderby' ] );

		add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
		add_filter( 'woocommerce_product_post_type_link_parent_category_only', '__return_true' );

		// Giảm HTML.
		add_filter( 'wc_price', [ $this, 'reformat_price' ], 10, 3 );
		add_filter( 'woocommerce_get_star_rating_html', [ $this, 'change_star_rating_html' ], 10, 2 );
	}

	public function setup() {
		add_theme_support( 'woocommerce', [
			'thumbnail_image_width' => 258,
		] );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	public function add_cart_fragment( array $fragments ): array {
		global $woocommerce;
		$fragments['a.cart-customlocation'] = '<a class="cart-customlocation count-cart" href="' . wc_get_cart_url() . '">' . $woocommerce->cart->cart_contents_count . '</a>';
		return $fragments;
	}

	public function change_breadcrumbs_args(): array {
		return [
			'delimiter'   => '&emsp;&gt;&emsp;',
			'wrap_before' => '<div class="custom-breadcrumb"><nav class="woocommerce-breadcrumb container" itemprop="breadcrumb">',
			'wrap_after'  => '</nav></div>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'starter-theme' ),
		];
	}

	public function remove_all_product_tabs( array $tabs ): array {
		unset( $tabs['description'], $tabs['reviews'], $tabs['additional_information'] );
		return $tabs;
	}

	public function output_prices_for_variables( $price, $regular_price, $sale_price ) {
		global $product;

		if ( ! is_product() ) {
			return $price;
		}

		if ( ! $product->is_type( 'variable' ) ) {
			return $price;
		}

		$display = '';
		if ( $sale_price && floatval( $regular_price ) ) {
			$sale    = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
			$display = '<span class="sale_amount"> Save ' . $sale . '%</span>';
		}

		return '<del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del> <ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins>' . $display;
	}

	public function change_catalog_orderby( array $orderby ): array {
		$orderby = [
			'popularity' => __( 'Popularity', 'starter-theme' ),
			'rating'     => __( 'Average rating', 'starter-theme' ),
			'date'       => __( 'Latest', 'starter-theme' ),
			'price'      => __( 'Price: low to high', 'starter-theme' ),
			'price-desc' => __( 'Price: high to low', 'starter-theme' ),
		];
		return $orderby;
	}

	public function reformat_price( $return, $price, $args ) {
		$formatted_price = sprintf( $args['price_format'], get_woocommerce_currency_symbol( $args['currency'] ), $price );
		$return          = $formatted_price;

		if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
			$return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
		}

		return $return;
	}

	public function change_star_rating_html( $html, $rating ) {
		return '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"></span>';
	}
}
