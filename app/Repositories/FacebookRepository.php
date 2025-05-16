<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\App\DTO\LoginDTO;
use LoginMeNow\App\DTO\UserDataDTO;
use LoginMeNow\App\Helpers\User;
use LoginMeNow\App\Repositories\AccountRepository;

class FacebookRepository {

	const DEFAULT_GRAPH_VERSION = 'v20.0';

	public function auth( UserDataDTO $userDataDTO ) {
		$wp_user      = get_user_by( 'email', sanitize_email( $userDataDTO->get_user_email() ) );
		$redirect_uri = $this->redirect_uri();

		$userDataDTO->set_redirect_uri( $redirect_uri );
		$userDataDTO->set_channel_name( 'facebook' );

		if ( $wp_user ) {
			$loginDTO = ( new LoginDTO )
				->set_user_id( $wp_user->ID )
				->set_redirect_uri( $redirect_uri )
				->set_redirect_return( false )
				->set_channel_name( 'facebook' );

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

		return apply_filters( "login_me_now_facebook_login_redirect_url", $redirect_uri );
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
		include login_me_now_dir( 'resources/views/facebook/button.php' );
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$html = ob_get_clean();

		return $html;
	}

	public static function is_enabled(): bool {
		$enable     = SettingsRepository::get( 'facebook_login', false );
		$app_id     = SettingsRepository::get( 'facebook_app_id', '' );
		$app_secret = SettingsRepository::get( 'facebook_app_secret', '' );

		if ( $enable && $app_id && $app_secret ) {
			return true;
		}

		return false;
	}

	public static function create_auth_url(): string {
		$client_id    = SettingsRepository::get( 'facebook_app_id' );
		$redirect_uri = home_url( 'wp-login.php?lmn-facebook' );

		$args = [
			'client_id'     => urlencode( $client_id ),
			'response_type' => 'code',
			'redirect_uri'  => urlencode( $redirect_uri ),
			'scope'         => 'public_profile,email',
		];

		$endpointAuthorization = 'https://www.facebook.com/';

		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			if ( preg_match( '/Android|iPhone|iP[ao]d|Mobile/', $_SERVER['HTTP_USER_AGENT'] ) ) {
				$endpointAuthorization = 'https://m.facebook.com/';
			}
		}

		$endpointAuthorization .= self::DEFAULT_GRAPH_VERSION . '/dialog/oauth';

		return add_query_arg( $args, $endpointAuthorization );
	}

	public function remote( $code ) {
		$access_token = $this->generate_access_token( $code );
		if ( ! $access_token ) {
			wp_send_json_error( __( "Token not matching", 'login-me-now' ) );
		}

		$user_data = $this->get_remote_user_graph( $access_token );
		if ( ! $user_data ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		} elseif ( ! isset( $user_data['email'] ) ) {
			wp_send_json_error( __( "Please give the email permission", 'login-me-now' ) );
		}

		$userDataDTO = ( new UserDataDTO )
			->set_user_email( $user_data['email'] )
			->set_display_name( $user_data['name'] ?? '' )
			->set_first_name( $user_data['first_name'] ?? '' )
			->set_last_name( $user_data['last_name'] ?? '' )
			->set_user_avatar_url( $user_data['picture']['data']['url'] ?? '' );

		$this->auth( $userDataDTO );
	}

	private function generate_access_token( string $code ) {
		$args = [
			'timeout'    => 15,
			'user-agent' => 'WordPress',
			'body'       => [
				'client_id'     => SettingsRepository::get( 'facebook_app_id' ),
				'client_secret' => SettingsRepository::get( 'facebook_app_secret' ),
				'redirect_uri'  => home_url( 'wp-login.php?lmn-facebook' ),
				'code'          => $code,
			],
		];

		$request = wp_remote_get(
			'https://graph.facebook.com/v20.0/oauth/access_token',
			$args
		);

		if ( wp_remote_retrieve_response_code( $request ) !== 200 ) {
			wp_send_json_error( __( "Unexpected response", 'login-me-now' ) );
		} else {
			$body = json_decode( wp_remote_retrieve_body( $request ), true );
		}

		return $body['access_token'] ?? '';
	}

	private function get_remote_user_graph( string $access_token ): array {
		$fbApiUrl = 'https://graph.facebook.com/v20.0/me?fields=id,name,email,first_name,last_name,picture.type(large)&access_token=' . $access_token;

		$response            = file_get_contents( $fbApiUrl );
		$data                = json_decode( $response, true );
		$data['accessToken'] = $access_token;

		if ( ! isset( $data['id'] ) ) {
			$data['message'] = 'Something went wrong';
		}

		return $data;
	}
}