<?php
namespace TitanWeb;

use WP_REST_Request;

/**
 * Lazy load một template part.
 * Sau khi khởi tạo class này, thì thêm `<template hl-id="template-part-name">` vào trong theme là phần template đó sẽ được load khi có tương tác của user (di chuyển chuột, ấn phím, ...)
 */
class HtmlLazyLoad {
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'output_script' ] );
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function output_script(): void {
		?>
		<script>
		{
			const load = () => {
				const restUrl = '<?= esc_url( rest_url( 'hl/load' ) ) ?>';
				document.querySelectorAll( '[hl-id]' ).forEach( el => {
					const id = el.getAttribute( 'hl-id' );

					fetch( `${restUrl}?id=${id}` )
						.then( response => response.json() )
						.then( response => el.outerHTML = response.content );
				} );
			}
			const events = ["mouseover","keydown","touchmove","touchstart"];
			const trigger = () => {
				load();
				events.forEach(e => window.removeEventListener(e, trigger, {passive: true}));
			}
			events.forEach(e => window.addEventListener(e, trigger, {passive: true}));
		}
		</script>
		<?php
	}

	public function register_routes(): void {
		register_rest_route( 'hl', 'load', [
			'methods'             => 'GET',
			'permission_callback' => '__return_true',
			'callback'            => [ $this, 'load' ],
		] );
	}

	public function load( WP_REST_Request $request ): array {
		$id = $request->get_param( 'id' );

		return [
			'content' => $this->load_template_part( $id ),
		];
	}

	private function load_template_part( string $name ): string {
		ob_start();
		get_template_part( $name );
		return ob_get_clean();
	}
}
