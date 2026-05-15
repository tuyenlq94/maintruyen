<?php
namespace TitanWeb;

class GoToTop {
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'output' ] );
	}

	public function output() {
		?>
		<a href="#" class="go-to-top">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="m6.293 13.293 1.414 1.414L12 10.414l4.293 4.293 1.414-1.414L12 7.586z"></path></svg>
		</a>
		<style>
		.go-to-top {
			display: grid;
			place-items: center;
			width: 48px;
			height: 48px;
			border-radius: 4px;
			background: rgba(0, 0, 0, .2);
			position: fixed;
			right: 12px;
			bottom: 12px;
			transition: all 0.25s;
		}
		.go-to-top:hover {
			background: rgba(0, 0, 0, .3);
		}
		</style>
		<?php
	}
}
