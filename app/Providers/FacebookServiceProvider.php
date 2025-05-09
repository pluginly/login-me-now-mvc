<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\Repositories\SettingsRepository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FacebookServiceProvider implements LoginProviderBase {

	public function boot() {

	}

	public static function get_key():string {

	}
	
	public static function get_button() {

	}
	public function get_settings():ProviderSettingsFieldsDTO {
		$dto = new ProviderSettingsFieldsDTO();
		$dto->set_fields(
			[
				[
					'title'         => __( 'Enable Facebook Login', 'login-me-now' ),
					'key'            => 'facebook_login',
					'default' 		=> false,
					'type'          => 'switch',
					'tab'           => 'facebook',
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'         => __( 'App ID', 'login-me-now' ),
					'description'   => __( 'Enter your Facebook App ID, get <a target="__blank" href="https://developers.facebook.com/apps/">Facebook App ID</a>', 'login-me-now' ),
					'placeholder'   => '14343****34343',
					'key'            => 'facebook_app_id',
					'default' 		=> '',
					'type'          => 'text',
					'tab'           => 'facebook',
					'if_has'        => ['facebook_login'],
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'         => __( 'App Secret', 'login-me-now' ),
					'description'   => __( 'Enter your Facebook App Secret, get <a target="__blank" href="https://developers.facebook.com/apps/">Facebook App Secret</a>', 'login-me-now' ),
					'placeholder'   => '2ae0c**********e06',
					'key'            => 'facebook_app_secret',
					'default' 		=> '',
					'type'          => 'text',
					'tab'           => 'facebook',
					'if_has'        => ['facebook_login'],
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'         => __( 'User Role Permission Level', 'login-me-now' ),
					'description'   => __( 'Select the role that will be assigned to new users who sign up', 'login-me-now' ),
					'key'            => 'facebook_pro_default_user_role',
					'default' 		=> false,
					'type'          => 'select',
					'options'       => $roles_options,
					'tab'           => 'facebook',
					'if_has'        => ['facebook_login'],
					'is_pro'        => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'         => __( 'Update Existing User Name', 'login-me-now' ),
					'description'   => __( 'Automatically retrieve the existing user first, last, nick & display name from facebook account upon login using facebook', 'login-me-now' ),
					'key'            => 'facebook_update_existing_user_data',
					'default' 		=> false,
					'type'          => 'switch',
					'tab'           => 'facebook',
					'if_has'        => ['facebook_login'],
					'is_pro'        => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'         => __( 'Redirection URL', 'login-me-now' ),
					'description'   => "Redirect after successful login and registration",
					'placeholder'   => 'e.g., https://yourwebsite.com/dashboard',
					'key'            => 'facebook_pro_redirect_url',
					'default' 		=> '',
					'type'          => 'text',
					'tab'           => 'facebook',
					'if_has'        => ['facebook_login'],
					'is_pro'        => true,
				]
			]
		);

	}
	public function get_name() {

	}
	public funtion listener() {

	}

	public static function show_on_native_login() {
		return self::show() && SettingsRepository::get( 'facebook_native_login', true );
	}

	public static function create_auth_url() {
		$client_id    = SettingsRepository::get( 'facebook_app_id' );
		$redirect_uri = home_url( 'wp-login.php?lmn-facebook' );

		$args = [
			'client_id'     => urlencode( $client_id ),
			'response_type' => 'code',
			'redirect_uri'  => urlencode( $redirect_uri ),
			'scope'         => 'public_profile,email',
		];

		return add_query_arg( $args, self::endpoint() );
	}

	public static function endpoint() {
		$endpointAuthorization = 'https://www.facebook.com/';

		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			if ( preg_match( '/Android|iPhone|iP[ao]d|Mobile/', $_SERVER['HTTP_USER_AGENT'] ) ) {
				$endpointAuthorization = 'https://m.facebook.com/';
			}
		}

		$endpointAuthorization .= self::DEFAULT_GRAPH_VERSION . '/dialog/oauth';

		return $endpointAuthorization;
	}
}