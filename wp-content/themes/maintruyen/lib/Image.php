<?php
namespace TitanWeb;

class Image {
	public static function output( string $name, int $width = 0, bool $retina = true, array $attributes = [] ): void {
		$url = get_template_directory_uri() . "/images/$name";
		$url = self::get_cdn_url( $url, $width, $retina );

		$attributes['src'] = $url;

		$output = '<img';
		foreach ( $attributes as $key => $value ) {
			$output .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}
		$output .= '>';

		echo wp_kses_post( $output );
	}

	public static function get_cdn_url( string $url, int $width = 0, bool $retina = true ): string {
		if ( wp_get_environment_type() !== 'production' ) {
			return $url;
		}

		$url = 'https://i0.wp.com/' . str_replace( [ 'http://', 'https://' ], '', $url );

		if ( $width ) {
			$url = add_query_arg( 'w', $width * ( $retina ? 2 : 1 ), $url ); // For retina screens.
		}

		return $url;
	}
}
