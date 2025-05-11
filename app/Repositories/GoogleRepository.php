<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\User;
use LoginMeNow\App\Repositories\AccountRepository;

class GoogleRepository {

	public function auth( UserDataDTO $userDataDTO ) {
		$wp_user      = get_user_by( 'email', sanitize_email( $userDataDTO->get_user_email() ) );
		$redirect_uri = $this->redirect_uri();

		$userDataDTO->set_redirect_uri( $redirect_uri );
		$userDataDTO->set_channel_name( 'google' );

		if ( $wp_user ) {
			$loginDTO = ( new LoginDTO )
				->set_user_id( $wp_user->ID )
				->set_redirect_uri( $redirect_uri )
				->set_redirect_return( false )
				->set_channel_name( 'google' );

			$action = ( new AccountRepository )->login( $loginDTO, $userDataDTO );

		} else {
			$action = ( new AccountRepository )->register( $userDataDTO );
		}

		if ( is_wp_error( $action ) ) {
			error_log( 'Login Me Now - ' . print_r( $action ) );

			return ['error message goes here'];
		}

		return $redirect_uri;
	}

	public static function redirect_uri(): string {
		$redirect_uri = ! empty( $_POST['redirect_uri'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_uri'] ) ) : admin_url();

		return apply_filters( "login_me_now_google_login_redirect_url", $redirect_uri );
	}

	/**
	 * Login Button HTML
	 */
	public static function get_button(): string {
		if ( ! self::is_enabled() ) {
			return '';
		}

		if ( User::is_logged_in() ) {
			return '';
		}

		ob_start();
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include login_me_now_dir( 'resources/views/google/button.php' );
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$html = ob_get_clean();

		return $html;
	}

	public static function is_enabled(): bool {
		$enable        = SettingsRepository::get( 'google_login', false );
		$client_id     = SettingsRepository::get( 'google_client_id', '' );
		$client_secret = SettingsRepository::get( 'google_client_secret', '' );

		if ( $enable && $client_id && $client_secret ) {
			return true;
		}

		return false;
	}

	public static function create_auth_url() {
		$client_id    = SettingsRepository::get( 'google_client_id' );
		$redirect_uri = home_url( 'wp-login.php?lmn-google' );
		$auth         = 'https://accounts.google.com/o/oauth2/v2/auth';
		$scopes       = [
			'email',
			'profile',
		];

		$args = [
			'response_type' => 'code',
			'client_id'     => urlencode( $client_id ),
			'redirect_uri'  => urlencode( $redirect_uri ),
			'wpnonce'       => wp_create_nonce( 'lmn-google-nonce' ),
		];

		if ( count( $scopes ) ) {
			$args['scope'] = urlencode( implode( ' ', array_unique( $scopes ) ) );
		}

		return add_query_arg( $args, $auth );
	}
}