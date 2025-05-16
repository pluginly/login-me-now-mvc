<?php

namespace LoginMeNow\App\Repositories;

use Google_Client;
use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\User;
use LoginMeNow\App\Repositories\AccountRepository;
use LoginMeNow\App\Repositories\SettingsRepository;

class GoogleRepository {

	public bool $redirect_return;

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

	public function process_button(): void {
		$client_id     = SettingsRepository::init()->get( 'google_client_id' );
		$client_secret = SettingsRepository::init()->get( 'google_client_secret' );
		$redirect_uri  = home_url( 'wp-login.php?lmn-google' );

		$client = new Google_Client(
			[
				'client_id'     => esc_html( $client_id ),
				'client_secret' => esc_html( $client_secret ),
				'redirect_uri'  => $redirect_uri,
			]
		);

		$tokens   = $client->fetchAccessTokenWithAuthCode( $_GET['code'] );
		$id_token = $tokens['id_token'] ?? '';

		if ( ! $tokens || is_wp_error( $tokens ) || ! $id_token || is_wp_error( $id_token ) ) {
			error_log( 'Login Me Now (! $tokens || is_wp_error( $tokens )- ' . print_r( $tokens, true ) );

			return;
		}

		$data = $client->verifyIdToken( $id_token );
		if ( ! $data || is_wp_error( $data ) ) {
			error_log( 'Login Me Now ( ! $data || is_wp_error( $data ) )- ' . print_r( $data, true ) );

			return;
		}

		$userDataDTO = ( new UserDataDTO )
			->set_id( $data['ID'] ?? 0 )
			->set_user_email( $data['email'] ?? '' )
			->set_first_name( $data['given_name'] ?? '' )
			->set_last_name( $data['family_name'] ?? '' )
			->set_display_name( $data['name'] ?? '' )
			->set_user_avatar_url( $data['picture'] ?? '' );

		$this->auth( $userDataDTO );
	}

	public function process_onetap(): void {
		$nonce = ! empty( $_POST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'lmn-google-nonce' ) ) {
			error_log( 'Login Me Now - WP Nonce Verify Failed' );

			return;
		}

		if ( ! isset( $_POST['g_csrf_token'] ) && ! empty( $_POST['g_csrf_token'] ) ) {
			error_log( 'Login Me Now - Post g_csrf_token not available' );

			return;
		}

		if ( ! isset( $_COOKIE['g_csrf_token'] ) && ! empty( $_COOKIE['g_csrf_token'] ) ) {
			error_log( 'Login Me Now - Cookie g_csrf_token not available' );

			return;
		}

		if ( $_POST['g_csrf_token'] !== $_COOKIE['g_csrf_token'] ) {
			error_log( 'Login Me Now - g_csrf_token is not same in post and cookie' );

			return;
		}

		if ( ! isset( $_POST['credential'] ) && ! empty( $_POST['credential'] ) ) {
			error_log( 'Login Me Now - Credential is not available' );

			return;
		}

		$id_token  = sanitize_text_field( wp_unslash( $_POST['credential'] ) );
		$client_id = SettingsRepository::init()->get( 'google_client_id' );
		$client    = new Google_Client( ['client_id' => esc_html( $client_id )] );
		$data      = $client->verifyIdToken( $id_token );

		if ( ! $data || is_wp_error( $data ) ) {
			error_log( 'Login Me Now - ' . print_r( $data ) );

			return;
		}

		$this->redirect_return = false;

		$userDataDTO = ( new UserDataDTO )
			->set_id( $data['ID'] ?? 0 )
			->set_user_email( $data['email'] ?? '' )
			->set_name( $data['name'] ?? '' )
			->set_first_name( $data['given_name'] ?? '' )
			->set_last_name( $data['family_name'] ?? '' )
			->set_display_name( $data['name'] ?? '' )
			->set_user_avatar_url( $data['picture'] ?? '' );

		$this->auth( $userDataDTO );
	}
}