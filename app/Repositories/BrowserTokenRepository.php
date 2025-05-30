<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\App\Helpers\Random;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Helpers\Transient;
use WP_Error;

class BrowserTokenRepository {

	public function generate_token( string $username, string $password, string $expiration ) {
		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();

			return new WP_Error(
				'[login_me_now] ' . $error_code,
				$user->get_error_message( $error_code ),
				[
					'status' => 403,
				]
			);
		}

		$legacy = ['1', '7', '31', '365', '1000'];
		// #important Remove this legacy code in v1.2.0
		if ( in_array( $expiration, $legacy, true ) ) {
			$expiration = "{$expiration} days";
		}

		$expiration = Time::convert_timestamp( $expiration );

		return ( new JWTAuthRepository )->new_token( $user, $expiration );
	}

	public function validate_token( string $token, $return_type = 'data' ) {
		try {
			return ( new JWTAuthRepository )->validate_token( $token, $return_type );
		} catch ( \Throwable $th ) {
			throw new \Exception( $th->getMessage() );
		}
	}

	public function generate_link( string $token ): array {

		$user_id = $this->validate_token( $token, 'user_id' );
		if ( ! is_numeric( $user_id ) ) {
			throw new \Exception( __( 'Invaild user id', 'login-me-now' ) );
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			throw new \Exception( __( 'No user found', 'login-me-now' ) );
		}

		try {
			$key = $this->get_new_token( $user_id, 8 );
			if ( ! $key ) {
				throw new \Exception( __( 'Unable generate login link', 'login-me-now' ) );
			}

			$link = sprintf( '%s%s', admin_url( '/?lmn=' ), $key );

			return ['link' => $link, 'number' => $key];

		} catch ( \Throwable $th ) {
			throw new \Exception( $th->getMessage() );
		}
	}

	private function get_new_token( int $user_id, int $hours ): string {
		$issuedAt = Time::now();
		$expire   = apply_filters( 'login_me_now_browser_token_link_expire', $issuedAt + ( HOUR_IN_SECONDS * $hours ), $issuedAt );

		$key   = Random::key();
		$saved = Transient::set( $key, $user_id, $expire );
		if ( ! $saved ) {
			throw new \Exception( __( "Something wen't wrong, please try again.", 'login-me-now' ) );
		}

		return $key;
	}
}