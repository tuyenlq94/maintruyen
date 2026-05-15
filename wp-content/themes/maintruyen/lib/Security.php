<?php
namespace TitanWeb;

class Security {
	public function __construct() {
		add_filter( 'login_errors', [ $this, 'login_errors' ] );
		add_filter( 'upload_mimes', [ $this, 'restrict_uploads' ] );
		add_filter( 'auth_cookie_expiration', [ $this, 'set_session_timeout' ] );
	}

	public function login_errors(): string {
		return __( 'Something is wrong! Please try again.', 'titanweb' );
	}

	public function restrict_uploads(): array {
		return [
			'jpe?g' => 'image/jpeg',
			'gif'   => 'image/gif',
			'png'   => 'image/png',
			'mp4'   => 'video/mp4',
			'pdf'   => 'application/pdf',
			'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		];
	}

	public function set_session_timeout(): int {
		return 15 * MINUTE_IN_SECONDS;
	}
}
