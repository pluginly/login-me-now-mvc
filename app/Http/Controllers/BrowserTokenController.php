<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\Helpers\MagicLink\Time;
use LoginMeNow\App\Repositories\JWTAuthRepository;


class BrowserTokenController extends Controller {

	public function hide_save_to_browser_extension() {
			
		if( isset($_POST['action'])){
					wp_send_json_success(
					update_user_meta(
					get_current_user_id(),
					'login_me_now_hide_save_to_browser_extension',
					true
				)
			);
		}
	}

	public function browser_token_generate() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		$additional_data = false;
		if ( ! empty( $_POST['additional_data'] ) ) {
			$additional_data = true;
		}

		$token = ( new JWTAuthRepository() )->new_token( $user, $expiration, $additional_data );

		if ( ! $token ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Browser Token Successfully Generated', 'login-me-now' ),
				'link'    => $token,
			]
		);

		wp_die();
	}

	public function browser_tokens() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$offset = (int) $_POST['offset'] ?? 0;
		$limit  = (int) $_POST['limit'] ?? 10;
		$tokens = BrowserTokenModel::init()->get_all( $offset, $limit );

		if ( ! is_array( $tokens ) || ! $tokens ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $tokens );
		wp_die();
	}

	public function browser_token_update_status() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		$status   = (string) $_POST['status'] ?? 'pause';

		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = BrowserTokenModel::init()->update( $token_id, $status );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function browser_token_drop() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$deleted = BrowserTokenModel::init()->drop( $token_id );

		wp_send_json_success( $deleted );
		wp_die();
	}

	public function errors() {
		return [
			'permission' => __( 'Sorry, you are not allowed to do this operation.', 'login-me-now' ),
			'nonce'      => __( 'Nonce validation failed', 'login-me-now' ),
			'default'    => __( 'Sorry, something went wrong.', 'login-me-now' ),
			'invalid'    => __( 'No post data found!', 'login-me-now' ),
		];
	}

	/**
	 * Get ajax error message.
	 */
	public function get_error_msg( string $type ) {
		if ( ! isset( $this->errors()[$type] ) ) {
			$type = 'default';
		}

		return $this->errors()[$type];
	}

	private function check_permissions( string $ref, string $cap = null ) {
		$response_data = ['message' => $this->get_error_msg( 'permission' )];

		if ( $cap && ! current_user_can( $cap ) ) {
			return $response_data;
		}

		if ( empty( $_POST ) ) {
			$response_data = ['message' => $this->get_error_msg( 'invalid' )];

			return $response_data;
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( $ref, 'security', false ) ) {
			$response_data = ['message' => $this->get_error_msg( 'nonce' )];

			return $response_data;
		}

		return false;
	}
}