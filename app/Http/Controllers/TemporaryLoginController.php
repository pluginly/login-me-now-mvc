<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\App\Repositories\AccountRepository;
use LoginMeNow\App\Repositories\TemporaryLoginRepository;

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
}