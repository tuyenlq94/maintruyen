<?php
namespace TitanWeb;

class Assets {
	public static function css( string $name, bool $inline = false ): void {
		$file = get_template_directory() . "/css/$name.css";

		if ( $inline ) {
			echo '<style>', file_get_contents( $file ), '</style>';
			return;
		}

		wp_enqueue_style( $name, get_template_directory_uri() . "/css/$name.css", [], filemtime( $file ) );
	}

	public static function js( string $name, array $deps = [], array $data = [] ): void {
		wp_enqueue_script( $name, get_template_directory_uri() . "/js/$name.js", $deps, filemtime( get_template_directory() . "/js/$name.js" ), true );

		if ( $data ) {
			wp_localize_script( $name, $name, $data );
		}
	}

	public static function template_css( $templates, string $name, bool $inline = false ): void {
		if ( is_page_template( $templates ) ) {
			self::css( $name, $inline );
		}
	}

	public static function template_js( $templates, string $name, array $deps = [], array $data = [] ): void {
		if ( is_page_template( $templates ) ) {
			self::js( $name, $deps, $data );
		}
	}
}
