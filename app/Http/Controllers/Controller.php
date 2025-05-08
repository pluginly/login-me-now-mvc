<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\LoginProviders\BrowserToken\JWTAuth;
use LoginMeNow\App\LoginProviders\BrowserToken\OnetimeNumber;

class Controller {
	/**
	 * Generate the onetime number
	 */
	public function generate_onetime_number( \WP_REST_Request $request ) {
		$result = JWTAuth::init()->validate_token( $request, 'user_id' );

		if ( is_numeric( $result ) ) {
			$link = OnetimeNumber::init()->get_shareable_link( $result );
			wp_send_json_success( $link );
		}

		wp_send_json_error( ['status' => $result] );
	}
}