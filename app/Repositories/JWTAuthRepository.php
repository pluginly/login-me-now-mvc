<?php

namespace LoginMeNow\App\Repositories;

use Exception;
use LoginMeNow\App\Helpers\Random;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Helpers\User;
use LoginMeNow\App\Models\BrowserTokenModel;
use LoginMeNow\Firebase\JWT\JWT;
use LoginMeNow\Firebase\JWT\Key;
use WP_Error;
use WP_User;

class JWTAuthRepository {

	/**
	 * Supported algorithms to sign the token
	 */
	private array $supported_algorithms = ['HS256', 'HS384', 'HS512', 'RS256', 'RS384', 'RS512', 'ES256', 'ES384', 'ES512', 'PS256', 'PS384', 'PS512'];

	public function new_token( WP_User $user, int $expiration, bool $additional_data = true ) {
		$secret_key = $this->get_secret_key();

		/** First thing, check the secret key if not exist return an error*/
		if ( ! $secret_key ) {
			return new WP_Error(
				'login_me_now_bad_config',
				__( 'JWT is not configured properly, please contact the admin', 'login-me-now' ),
				[
					'status' => 403,
				]
			);
		}

		/** Valid credentials, the user exists create the according Token */
		$issuedAt  = time();
		$notBefore = apply_filters( 'login_me_now_browser_token_not_before', $issuedAt, $issuedAt );
		$expire    = apply_filters( 'login_me_now_browser_token_expire', $expiration );

		$rand_number = Random::number();

		$token = [
			'iss'  => get_bloginfo( 'url' ),
			'iat'  => $issuedAt,
			'nbf'  => $notBefore,
			'exp'  => $expire,
			'data' => [
				'user' => [
					'id' => $user->data->ID,
				],
				'tid'  => $rand_number,
			],
		];

		/** Let the user modify the token data before the sign. */
		$algorithm = $this->get_algorithm();

		if ( false === $algorithm ) {
			return new WP_Error(
				'login_me_now_unsupported_algorithm',
				__( 'Algorithm not supported, see https://www.rfc-editor.org/rfc/rfc7518#section-3', 'login-me-now' ),
				[
					'status' => 403,
				]
			);
		}

		$token = JWT::encode(
			apply_filters( 'login_me_now_token_before_sign', $token, $user ),
			$secret_key,
			$algorithm
		);

		/** Store the token ref in user meta using the $issuedAt, so we can block the token if needed */
		$Btm = new BrowserTokenModel();
		$Btm->insert( $user->data->ID, $rand_number, $expire, 'active' );

		// \LoginMeNow\Integrations\SimpleHistory\Logs::add( $user->data->ID, "generated a token for browser extension (Token ID: {$rand_number})" );

		if ( ! $additional_data ) {
			return $token;
		}

		/** The token is signed, now create the object with no sensible user data to the client*/
		$data = [
			'token'             => $token,
			'site_url'          => get_bloginfo( 'url' ),
			'site_icon_url'     => get_site_icon_url(),
			'user_email'        => $user->data->user_email,
			'user_nicename'     => $user->data->user_nicename,
			'user_display_name' => $user->data->display_name,
		];

		return apply_filters( 'login_me_now_token_before_dispatch', $data, $user );
	}

	public function validate_token( string $req_token, $return_type = 'data' ) {
		/** Get the Secret Key */
		$secret_key = $this->get_secret_key();
		if ( ! $secret_key ) {
			return new WP_Error(
				'login_me_now_bad_config',
				'JWT is not configured properly, please contact the admin',
				[
					'status' => 403,
				]
			);
		}

		/** Try to decode the token */
		try {
			$algorithm = $this->get_algorithm();
			if ( false === $algorithm ) {
				return new WP_Error(
					'login_me_now_unsupported_algorithm',
					__( 'Algorithm not supported, see https://www.rfc-editor.org/rfc/rfc7518#section-3', 'login-me-now' ),
					[
						'status' => 403,
					]
				);
			}

			$token = JWT::decode( $req_token, new Key( $secret_key, $algorithm ) );

			/** The Token is decoded now validate the iss */
			if ( get_bloginfo( 'url' ) !== $token->iss ) {
				/** The iss do not match, return error */
				return new WP_Error(
					'login_me_now_bad_iss',
					'The iss do not match with this server',
					[
						'status' => 403,
					]
				);
			}

			/** So far so good, validate the user id in the token */
			if ( ! isset( $token->data->user->id ) ) {
				/** No user id in the token, abort!! */
				return new WP_Error(
					'login_me_now_bad_request',
					'User ID not found in the token',
					[
						'status' => 403,
					]
				);
			}

			$token_id     = ! empty( $token->data->tid ) ? $token->data->tid : false;
			$token_status = ( new BrowserTokenModel )->status( $token_id );
			if ( ! $token_status || 'active' !== $token_status ) {
				return $token_status;
			}

			/** Everything looks good return the decoded token if we are using the token */
			if ( 'token' === $return_type ) {
				return $req_token;
			}

			$user = User::data( $token->data->user->id );

			if ( 'user_id' === $return_type ) {
				$message = __( "logged in using browser extension (ID: {$token_id})", 'login-me-now' );
				//\LoginMeNow\Integrations\SimpleHistory\Logs::add( $token->data->user->id, $message );

				return (int) $token->data->user->id;
			}

			/** The token already signed, now create the object with no sensible user data to the client*/
			$data = [
				'token'             => $req_token,
				'site_url'          => get_bloginfo( 'url' ),
				'site_icon_url'     => get_site_icon_url( 'url' ),
				'user_email'        => $user->data->user_email,
				'user_nicename'     => $user->data->user_nicename,
				'user_display_name' => $user->data->display_name,
			];

			/** This is for the /validate endpoint*/

			return $data;
		} catch ( Exception $e ) {
			/** Something were wrong trying to decode the token, send back the error */
			return new WP_Error(
				'login_me_now_invalid_token',
				$e->getMessage(),
				[
					'status' => 403,
				]
			);
		}
	}

	/**
	 * Get the algorithm used to sign the token via the filter login_me_now_algorithm.
	 * and validate that the algorithm is in the supported list. if not exist then add new
	 *
	 * @return false|mixed|null
	 */
	public function get_algorithm() {
		$algo = get_option( 'login_me_now_algorithm' );
		if ( ! $algo ) {
			$algo = 'HS256';
			update_option( 'login_me_now_algorithm', $algo );
		}

		$algorithm = apply_filters( 'login_me_now_algorithm', $algo );
		if ( ! in_array( $algorithm, $this->supported_algorithms, true ) ) {
			return false;
		}

		return $algorithm;
	}

	/**
	 * Get the secret key,
	 * if not exists then generate and save to option
	 */
	public function get_secret_key(): string {
		$key = get_option( 'login_me_now_secret_key' );

		if ( ! $key ) {
			$key = Random::key();
			update_option( 'login_me_now_secret_key', $key );
		}

		return $key;
	}
}