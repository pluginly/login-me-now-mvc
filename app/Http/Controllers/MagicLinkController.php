<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\Repositories\AccountRepository;
use LoginMeNow\App\Repositories\MagicLinkRepository;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\DTO\UserDataDTO;

class MagicLinkController extends Controller {
	public function send_magic_link() {
		if ( ! isset( $_POST['email'] ) ) {
			return;
		}

		$nonce = ! empty( $_POST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'lmn-magic-link-nonce' ) ) {
			wp_send_json_error( ['message' => __( 'Invalid security nonce.', 'login-me-now' )] );
		}

		$email = sanitize_email( $_POST['email'] );
		if ( ! $email ) {
			wp_send_json_error( ['message' => __( "Please enter a valid email", 'login-me-now' )] );
		}

		$user = get_user_by( 'email', $email );
		if ( ! $user ) {
			wp_send_json_error( ['message' => __( "User not found", 'login-me-now' )] );
		}

		$status = ( new MagicLinkRepository() )->email_magic_link( $user->ID, $email );
		if ( ! $status ) {
			wp_send_json_error( ['message' => __( "Something went wrong", 'login-me-now' )] );
		}

		wp_send_json_success( [
			'message' => __( 'Magic link sent to your email', 'login-me-now' ),
		] );
	}

	public function listen_magic_link():void {
		if ( ! isset( $_GET['lmn-magic-link'] ) || empty( $_GET['lmn-magic-link'] ) ) {
			return;
		}

		$token   = sanitize_text_field( $_GET['lmn-magic-link'] );
		$user_id = ( new MagicLinkRepository )->verify_token( $token );

		if ( ! $user_id ) {
			$title   = __( 'Invalid Magic Link mm', 'login-me-now' );
			$message = __( 'Request a new access link in order to obtain dashboard access', 'login-me-now' );
			ob_start();
			include login_me_now_dir( 'resources/views/magic-link/error-message.php' );
			$html = ob_get_clean();
			echo $html;
			exit();
		}

		$redirect_uri = apply_filters( 'login_me_now_temporary_login_redirect_uri', admin_url() );
		$message      = __( "logged in using temporary login link", 'login-me-now' );
		// \LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, $message );
		$userDataDTO = new UserDataDTO();
		$dto = ( new LoginDTO )
			->set_user_id( $user_id )
			->set_redirect_uri( $redirect_uri )
			->set_redirect_return( false )
			->set_channel_name( 'link_login' );
		( new AccountRepository )->login( $dto,$userDataDTO);
	}
}