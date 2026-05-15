<?php
namespace T_Shop;

class Menu {
	public function __construct() {
		add_filter( 'walker_nav_menu_start_el', [ $this, 'add_dropdown_icons' ], 10, 4 );
	}

	public function add_dropdown_icons( $output, $item, $depth, $args ) {
		if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			// Translators: %s - Item label.
			$output = '<div class="menu-text">' . $output . '<button class="sub-menu-toggle" aria-expanded="false"><span class="screen-reader-text">' . esc_html( sprintf( __( 'Show submenu for %s', 'starter-theme' ), $item->title ) ) . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.293 9.293 12 13.586 7.707 9.293l-1.414 1.414L12 16.414l5.707-5.707z"></path></svg></button></div>';
		}
		return $output;
	}
}
