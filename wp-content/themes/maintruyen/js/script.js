jQuery( function ( $ ) {
	var clickEvent = 'ontouchstart' in window ? 'touchstart' : 'click';

	let $window = window,
		$body = $( 'body' );

	function slickSlide() {
		$( '.new-home__inner' ).slick( {
			slidesToShow: 4,
			dots: false,
			arrows: true,
			autoplay: false,
			rows: 0,
			autoplaySpeed: 4000,
			responsive: [
				{
					breakpoint: 991,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 600,
					settings: {
						dots: true,
						slidesToShow: 1
					}
				}
			]
		} );
	};

	function counterNumber() {
		var run = false;
		if ( !$body.hasClass( 'page-template-home-page' ) ) {
			return;
		}

		$window.scroll( function () {
			var top = $( '.number-home' ).offset().top - window.innerHeight;

			if ( run || $window.scrollTop() <= top ) {
				return;
			}

			$( '.count' ).each( function () {
				$( this ).prop( 'Counter', 0 ).animate( {
					Counter: $( this ).text()
				}, {
					duration: 4000,
					easing: 'swing',
					step: function ( now ) {
						$( this ).text( Math.ceil( now ) );
					}
				} );
			} );

			run = true;
		} );
	}

	function popupLogout() {
		$( '.popup-modal' ).magnificPopup( {
			type: 'inline',
			preloader: false,
			modal: true
		} );
		$( document ).on( 'click', '.popup-modal-dismiss', function ( e ) {
			e.preventDefault();
			$.magnificPopup.close();
		} );
	}

	function toggleMenu() {
		const nav = document.querySelector( '.header__menu' );
		const bg = document.querySelector( '.bgDart' );
		if ( !nav ) {
			return;
		}
		const menu = nav.querySelector( 'ul' ),
			button = document.querySelector( '.menu-toggle' );

		menu.setAttribute( 'aria-expanded', 'false' );
		button.addEventListener( 'click', () => {
			nav.classList.toggle( 'is-open' );
			bg.classList.toggle( 'open' );
		} );
		bg.addEventListener( 'click', () => {
			nav.classList.remove( 'is-open' );
			bg.classList.remove( 'open' );
		} );
	}

	slickSlide();
	counterNumber();
	popupLogout();
	toggleMenu();
} );
