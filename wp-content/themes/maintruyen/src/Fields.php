<?php
namespace T_Shop;

class Fields {
	public function __construct() {
		add_filter( 'rwmb_meta_boxes', [ $this, 'register_fields' ] );
	}

	public function register_fields( array $meta_boxes ): array {
		$meta_boxes[] = $this->banners();

		return $meta_boxes;
	}

	private function banners(): array {
		return [
			'title'      => __( 'Banners', 'starter-theme' ),
			'id'         => 'banner',
			'post_types' => [ 'page' ],
			'fields'     => [
				[
					'id'          => 'banners',
					'type'        => 'group',
					'clone'       => true,
					'sort'        => true,
					'collapsible' => true,
					'group_title' => __( 'Banner {#}', 'starter-theme' ),
					'fields'      => [
						[
							'id'   => 'image',
							'name' => __( 'Image', 'starter-theme' ),
							'type' => 'single_image',
							'desc' => __( 'Recommended size: 1920x560', 'starter-theme' ),
						],
						[
							'id'   => 'link',
							'name' => __( 'Link', 'starter-theme' ),
						],
					],
				],
			],
		];
	}
}
