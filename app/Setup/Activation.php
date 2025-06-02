<?php

namespace LoginMeNow\App\Setup;

defined( 'ABSPATH' ) || exit;

use LoginMeNow\App\Helpers\Random;
use LoginMeNow\App\Models\BrowserTokenModel;

class Activation {
	public function __construct() {
		$this->setup();
	}

	public function setup(): void {
		( new BrowserTokenModel )->create_table();

		/**
		 * Add the secret key if not exist
		 */
		$key = get_option( 'login_me_now_secret_key' );
		if ( ! $key ) {
			$key = Random::key();
			update_option( 'login_me_now_secret_key', $key );
		}

		/**
		 * Add the algorithm if not exist
		 */
		$algo = get_option( 'login_me_now_algorithm' );
		if ( ! $algo ) {
			$algo = 'HS256';
			update_option( 'login_me_now_algorithm', $algo );
		}
	}
}