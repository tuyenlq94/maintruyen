<?php
namespace T_Shop;

use TitanWeb\Assets;

class Loader {
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	public function setup() {
		load_theme_textdomain( 'starter-theme', get_template_directory() . '/languages' );

		register_nav_menus([
			'primary' => esc_html__( 'Primary Menu', 'starter-theme' ),
		]);

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ] );

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'responsive-embeds' );
	}

	public function widgets_init() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'starter-theme' ),
				'id'            => 'sidebar-1',
				'before_widget' => '<aside class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	public function enqueue_assets() {
		wp_enqueue_style( 'starter-theme', get_stylesheet_uri(), [], filemtime( get_template_directory() . '/style.css' ) );
		Assets::js( 'script', [ 'jquery' ] );

		// wp_enqueue_style( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', [], '1.1.0' );
		// wp_enqueue_script( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', [ 'jquery' ], '1.1.0', true );

		// wp_enqueue_style( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', [], '1.8.1' );
		// wp_enqueue_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', [ 'jquery' ], '1.8.1', true );

		// Thêm style cho template
		// Assets::template_css( 'page-templates/home-page.php', 'home' );
	}
}
