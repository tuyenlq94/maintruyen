<?php
namespace T_Shop;

class AssetOptimization {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'handle_assets' ], PHP_INT_MAX );
		add_action( 'wp_footer', [ $this, 'handle_assets' ], 15 ); // WordPress prints footer scripts at priority 20, so we must run before that.

		// Jetpack.
		add_filter( 'jetpack_implode_frontend_css', '__return_false', PHP_INT_MAX );
		add_filter( 'jetpack_sharing_counts', '__return_false', PHP_INT_MAX );
	}

	public function handle_assets(): void {
		$this->disable_assets();
		$this->remove_polyfill_dependencies( [ 'regenerator-runtime', 'wp-polyfill-inert', 'wp-polyfill' ] );
	}

	private function disable_assets() {
		wp_dequeue_style( 'classic-theme-styles' );
		wp_dequeue_script( 'jetpack-photon' );
		wp_dequeue_script( 'jquery-ui-autocomplete' );

		// WooCommerce.
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_script( 'woo-tracks' );
		if ( is_front_page() || is_archive() || is_home() || is_single() ) {
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-smallscreen' );
		}

		if ( is_tax( 'product_cat' ) ) {
			// WC Fields Factory plugin.
			wp_dequeue_style( 'wcff-jquery-ui-style' );
			wp_dequeue_style( 'wcff-timepicker-style' );
			wp_dequeue_style( 'wcff-colorpicker-style' );
			wp_dequeue_style( 'wcff-client-style' );
		}

		if ( is_singular( 'product' ) ) {
			wp_dequeue_script( 'ppcp-smart-button' );
		}

		if ( is_single() ) {
			// WooCommerce.
			wp_dequeue_style( 'woocommerce' );

			// Woo Quickview plugin.
			wp_dequeue_style( 'sp_wqv-button-icons' );
			wp_dequeue_style( 'wqv-magnific-popup' );
			wp_dequeue_style( 'wqv-perfect-scrollbar' );
			wp_dequeue_style( 'wqv-fontello' );
			wp_dequeue_style( 'wqv-fancy-box' );
			wp_dequeue_style( 'wqv-style' );
			wp_dequeue_style( 'wqv-custom' );

			// WooCommerce.
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'jquery-blockui' );

			// Woo Quickview plugin.
			wp_dequeue_script( 'wqv-magnific-popup-js' );
			wp_dequeue_script( 'wqv-config-js' );
			wp_dequeue_script( 'wqv-facybox' );
			wp_dequeue_script( 'wqv-perfect-scrollbar-js' );
		}
	}

	private function remove_polyfill_dependencies( array $dependencies ): void {
		global $wp_scripts;
		$scripts = array_keys( $wp_scripts->registered );
		foreach ( $scripts as $script ) {
			$deps                                    = $wp_scripts->registered[ $script ]->deps;
			$deps                                    = array_values( array_diff( $deps, $dependencies ) );
			$wp_scripts->registered[ $script ]->deps = $deps;
		}
	}
}
