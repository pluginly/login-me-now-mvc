<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\Repositories\FacebookRepository;

class FacebookController {
	public bool $redirect_return;
	public function listen(): void {
		if ( ! array_key_exists( 'lmn-facebook', $_GET ) ) {
			return;
		}

		$code = $_GET['code'];
		if ( ! $code ) {
			wp_send_json_error( __( "Not authenticated", 'login-me-now' ) );
		}

		( new FacebookRepository() )->remote( $code );
	}
}