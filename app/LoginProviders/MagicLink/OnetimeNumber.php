<?php

namespace LoginMeNow\App\LoginProviders\BrowserToken;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Utils\Random;
use LoginMeNow\Utils\Time;
use LoginMeNow\Utils\Transient;
use WP_Error;
use WP_User;

/**
 * The Onetime Number Handling Class
 */
class OnetimeNumber {
	use Singleton;

	public function get_shareable_link( int $user_id, int $expiration = 8 ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		$key = $this->get_new( $user, $expiration );
		if ( ! $key ) {
			return false;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn=' ), $key );

		return (array) ['link' => $link, 'number' => $key];
	}

	private function get_new( WP_User $user, int $hours ) {
		/** Valid credentials, the user exists create the according Token */
		$issuedAt = Time::now();
		$expire   = apply_filters( 'login_me_now_onetime_number_expire', $issuedAt + ( HOUR_IN_SECONDS * $hours ), $issuedAt );

		$key = Random::key();

		/** Store the generated token in transient*/
		$saved = Transient::set( $key, $user->data->ID, $expire );
		if ( ! $saved ) {
			return new WP_Error(
				__( "Something wen't wrong, please try again.", 'login-me-now' ),
			);
		}

		return (string) $key;
	}

	/**
	 * Verify the key
	 */
	public function verify( string $key ) {
		$len = strlen( $key );

		/**
		 * if the key is not valid return an error.
		 */
		if ( ! $key || 40 !== $len ) {
			return new WP_Error(
				'Invalid key'
			);
		}

		/** Get the user_id from transient */
		$user_id = (string) Transient::get( $key );
		$user    = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		return (int) $user->data->ID;
	}
}