<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Helpers\Transient;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\Models\BrowserTokenModel;
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
			$this->rest_render_error(
				__( 'Token not provided', 'login-me-now' ),
				__( 'Request a new access link in order to obtain dashboard access', 'login-me-now' )
			);
		}

		$key     = sanitize_text_field( $_GET['lmn'] );
		$user_id = Transient::get( $key );

		if ( ! $user_id ) {
			$this->rest_render_error(
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

	private function rest_render_error( $title, $message ) {
		ob_start();
		include login_me_now_dir( 'resources/views/browser-token/error-message.php' );
		echo ob_get_clean();
		exit;
	}

	public function admin_generate_token() {

		try {

			$user_id = get_current_user_id();
			$user    = get_userdata( $user_id );

			$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
			$expiration  = Time::convert_timestamp( $date_string );
			$token       = ( new JWTAuthRepository() )->new_token( $user, $expiration );

			if ( ! $token ) {
				throw new \Exception( __( "Something went wrong", 'login-me-now' ) );
			}

			return Response::send(
				array_merge(
					[
						'success' => true,
						'message' => __( 'Successfully Generated', 'login-me-now' ),
					],
					$token,
				)
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

	public function admin_tokens() {

		try {
			$offset = (int) $_POST['offset'] ?? 0;
			$limit  = (int) $_POST['limit'] ?? 10;
			$tokens = ( new BrowserTokenModel )->get_all( $offset, $limit );

			if ( ! is_array( $tokens ) || ! $tokens ) {
				throw new \Exception( __( "Something went wrong", 'login-me-now' ) );
			}

			return Response::send( [
				'success' => true,
				'tokens'  => $tokens]
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

	public function admin_update_token_status() {

		try {
			$token_id = (int) $_POST['token_id'] ?? 0;
			$status   = (string) $_POST['status'] ?? 'pause';

			if ( ! $token_id ) {
				throw new \Exception( __( "No meta id provided", 'login-me-now' ) );
			}

			$updated = ( new BrowserTokenModel )->update( $token_id, $status );

			return Response::send(
				[
					'success' => true,
					'updated' => $updated,
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

	public function admin_drop_token() {

		try {
			$token_id = (int) $_POST['token_id'] ?? 0;
			if ( ! $token_id ) {
				throw new \Exception( __( "No meta id provided", 'login-me-now' ) );
			}

			$deleted = ( new BrowserTokenModel )->drop( $token_id );

			return Response::send(
				[
					'success' => true,
					'deleted' => $deleted,
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
}