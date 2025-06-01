<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\Repositories\AccountRepository;
use LoginMeNow\App\Repositories\TemporaryLoginRepository;
use LoginMeNow\WpMVC\Routing\Response;

class TemporaryLoginController extends Controller {

	public function listen_temporary_link(): void {
		if ( ! isset( $_GET['lmn-token'] ) ) {
			return;
		}

		if ( empty( $_GET['lmn-token'] ) ) {
			$title   = __( 'No Login Link Found', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );

			ob_start();
			include login_me_now_dir( 'resources/views/temporary-login/error-message.php' );
			$html = ob_get_clean();
			echo $html;
			exit();
		}

		$token   = sanitize_text_field( $_GET['lmn-token'] );
		$user_id = ( new TemporaryLoginRepository )->verify( $token );

		if ( ! $user_id ) {
			$title   = __( 'Invalid Login Link', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );

			ob_start();
			include login_me_now_dir( 'resources/views/temporary-login/error-message.php' );
			$html = ob_get_clean();
			echo $html;
			exit();
		}

		$redirect_uri = apply_filters( 'login_me_now_temporary_login_redirect_uri', admin_url() );
		$message      = __( "logged in using temporary login link", 'login-me-now' );
		// \LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, $message );

		$userDataDTO = new UserDataDTO();
		$dto         = ( new LoginDTO )
			->set_user_id( $user_id )
			->set_redirect_uri( $redirect_uri )
			->set_redirect_return( false )
			->set_channel_name( 'link_login' );

		( new AccountRepository )->login( $dto, $userDataDTO );
	}

	public function admin_generate_token() {

		try {
			$user_id     = (int) get_current_user_id();
			$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
			$expiration  = Time::convert_timestamp( $date_string );

			if ( ! $expiration ) {
				throw new \Exception( __( "Invalid expiration date", 'login-me-now' ) );
			}

			$link = ( new TemporaryLoginRepository )->create( $user_id, $expiration );
			if ( ! $link ) {
				throw new \Exception( __( "Something went wrong", 'login-me-now' ) );
			}

			return Response::send(
				array_merge(
					[
						'success' => true,
						'message' => __( 'Successfully Generated', 'login-me-now' ),
					],
					$link,
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
			$tokens = ( new TemporaryLoginRepository )->get_all( $offset, $limit );

			if ( ! is_array( $tokens ) || ! $tokens ) {
				throw new \Exception( __( "Something went wrong", 'login-me-now' ) );
			}

			return Response::send(
				[
					'success' => true,
					'message' => __( 'Successfully Fetched', 'login-me-now' ),
					'tokens'  => $tokens,
				],
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

	public function admin_update_status() {

		try {
			$umeta_id = (int) $_POST['umeta_id'] ?? 0;
			$status   = (string) $_POST['status'] ?? 'pause';

			if ( ! $umeta_id ) {
				throw new \Exception( __( "No meta id provided", 'login-me-now' ) );
			}

			$updated = ( new TemporaryLoginRepository )->update_status( $umeta_id, $status );

			return Response::send(
				[
					'success' => true,
					'message' => __( 'Successfully Updated', 'login-me-now' ),
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

	public function admin_extend_time() {

		try {
			$umeta_id    = (int) $_POST['umeta_id'] ?? 0;
			$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
			$expiration  = Time::convert_timestamp( $date_string );

			if ( ! $umeta_id || ! $expiration ) {
				throw new \Exception( __( "No meta id provided", 'login-me-now' ) );
			}

			$updated = ( new TemporaryLoginRepository )->extend_time( $umeta_id, $expiration );

			return Response::send(
				[
					'success' => true,
					'message' => __( 'Successfully Time Updated', 'login-me-now' ),
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

	public function admin_drop_link() {

		try {
			$umeta_id = (int) $_POST['umeta_id'] ?? 0;
			if ( ! $umeta_id ) {
				wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
			}

			$deleted = ( new TemporaryLoginRepository )->drop( $umeta_id );

			return Response::send(
				[
					'success' => true,
					'message' => __( 'Successfully Deleted', 'login-me-now' ),
					'deleted' => $deleted,
				],
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

	public function admin_get_link() {

		try {
			$umeta_id = (int) $_POST['umeta_id'] ?? 0;
			if ( ! $umeta_id ) {
				wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
			}

			$link = ( new TemporaryLoginRepository )->get_link( $umeta_id );

			return Response::send(
				[
					'success' => true,
					'message' => __( 'Successfully Fetched', 'login-me-now' ),
					'link'    => $link,
				],
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