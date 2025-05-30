<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Helpers\Transient;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\Repositories\AccountRepository;
use LoginMeNow\App\Repositories\BrowserTokenRepository;
use LoginMeNow\App\Repositories\JWTAuthRepository;
use LoginMeNow\WpMVC\RequestValidator\Validator;
use LoginMeNow\WpMVC\Routing\Response;
use WP_REST_Request;

class BrowserTokenController extends Controller {

	public function generate_token( Validator $validator, WP_REST_Request $request ) {
		$validator->validate(
			[
				'username'   => 'required|string',
				'password'   => 'required|string',
				'expiration' => 'required|string',
			]
		);

		$username   = $request->get_param( 'username' );
		$password   = $request->get_param( 'password' );
		$expiration = $request->get_param( 'expiration' );

		return Response::send(
			[
				'token' => ( new BrowserTokenRepository() )->generate_token( $username, $password, $expiration ),
			]
		);
	}

	public function validate_token( Validator $validator, WP_REST_Request $request ) {
		$validator->validate(
			[
				'token' => 'required|string',
			]
		);

		$token = $request->get_param( 'token' );

		return Response::send(
			( new BrowserTokenRepository() )->validate_token( $token, 'data' ),
		);
	}

	public function generate_link( Validator $validator, WP_REST_Request $request ) {
		$validator->validate(
			[
				'token' => 'required|string',
			]
		);

		$token = $request->get_param( 'token' );

		try {

			$data = ( new BrowserTokenRepository() )->generate_link( $token );

			return Response::send(
				[
					'success' => true,
					'data'    => $data,
				]
			);
		} catch ( \Throwable $th ) {
			return Response::send(
				[
					'success' => false,
					'message' => $th->getMessage(),
				]
			);
		}
	}

	public function listen_link() {
		if ( ! isset( $_GET['lmn'] ) ) {
			return;
		}

		if ( empty( $_GET['lmn'] ) ) {
			$this->render_error(
				__( 'Token not provided', 'login-me-now' ),
				__( 'Request a new access link in order to obtain dashboard access', 'login-me-now' )
			);
		}

		$key     = sanitize_text_field( $_GET['lmn'] );
		$user_id = Transient::get( $key );

		if ( ! $user_id ) {
			$this->render_error(
				__( 'Invalid token or user not found', 'login-me-now' ),
				__( 'Request a new access link in order to obtain dashboard access', 'login-me-now' )
			);
		}

		Transient::delete( $key );

		$redirect_uri = apply_filters( 'login_me_now_browser_token_login_redirect_uri', admin_url() );

		$dto = ( new LoginDTO() )
			->set_user_id( $user_id )
			->set_redirect_uri( $redirect_uri )
			->set_redirect_return( false )
			->set_channel_name( 'browser_token' );

		$userDataDTO = new UserDataDTO();

		( new AccountRepository() )->login( $dto, $userDataDTO );
	}

	private function render_error( $title, $message ) {
		ob_start();
		include login_me_now_dir( 'resources/views/browser-token/error-message.php' );
		echo ob_get_clean();
		exit;
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
				'message' => __( 'Successfully Generated', 'login-me-now' ),
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