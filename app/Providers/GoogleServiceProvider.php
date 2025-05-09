<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\LoginButtonDTO;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\DTO\ProviderUserDataDTO;
use LoginMeNow\App\Helpers\User;
use LoginMeNow\App\Http\Controllers\GoogleController;
use LoginMeNow\App\Repositories\SettingsRepository;

class GoogleServiceProvider implements LoginProviderBase {

	public function boot() {
		add_filter( 'login_me_now_google_login_show_onetap', [$this, 'onetap'] );
		add_filter( 'get_avatar_url', [$this, 'avatar'], 10, 3 );
		add_action( "login_me_now_after_login", [$this, 'verified'], 10, 2 );
		add_action( 'init', [GoogleController::class, 'listen'] );
	}

	/**
	 * Unique Key of the Login Provider, like: email_magic_link
	 */
	public static function get_key(): string {
		return 'google_login';
	}

	/**
	 * Name of the Login Provider, like: Email Magic Link
	 */
	public static function get_name(): string {
		return 'Google';
	}

	/**
	 * Login Button to be displayed on the login page
	 */
	public static function get_button(): LoginButtonDTO {
		$dto = new LoginButtonDTO();
		$dto->set_class( 'lmn-google' );
		$dto->set_icon( 'fa-solid fa-google' );
		$dto->set_label( 'Login with Google' );
		$dto->set_modal_behavior( 'window' );

		return $dto;
	}

	/**
	 * Settings Fields to be displayed on the settings page
	 */
	public function get_settings(): ProviderSettingsFieldsDTO {
		$page_options  = [];
		$roles_options = [];

		$pages = login_me_now_get_pages();
		$roles = login_me_now_get_pages();
		$dto   = new ProviderSettingsFieldsDTO();

		foreach ( $pages as $page ) {
			$page_options[] = [
				'value' => $page['id'],
				'label' => $page['name'],
			];
		}

		foreach ( $roles as $key => $role ) {

			$roles_options[] = [
				'value' => $key,
				'label' => $role,
			];
		}

		$dto->set_fields(
			[
				[
					'title'   => __( 'Enable Google Login', 'login-me-now' ),
					'key'     => 'google_login',
					'default' => false,
					'type'    => 'switch',
					'tab'     => 'google',
				],
				[
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				],
				[
					'title'       => __( 'Client ID', 'login-me-now' ),
					'description' => __( 'Enter your google Client ID here, <a target="__blank" href="https://developers.google.com/identity/gsi/web/guides/get-google-api-clientid">get Client ID</a>', 'login-me-now' ),
					'key'         => 'google_client_id',
					'placeholder' => 'ex: **********--**********.apps.googleusercontent.com',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
				],
				[
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				], [
					'title'       => __( 'Client Secret', 'login-me-now' ),
					'description' => __( "Enter your Client Secret key here.", 'login-me-now' ),
					'key'         => 'google_client_secret',
					'placeholder' => 'e.g., ******-****-******',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
				], [
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				], [
					'title'       => __( 'OneTap', 'login-me-now' ),
					'description' => __( "Enable google onetap login", 'login-me-now' ),
					'key'         => 'google_onetap',
					'default'     => false,
					'type'        => 'switch',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
				], [
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login', 'google_onetap'],
				], [
					'title'       => __( 'Select Location', 'login-me-now' ),
					'description' => __( "Choose a location where you want to show the onetap", 'login-me-now' ),
					'key'         => 'google_onetap_display_location',
					'default'     => [],
					'type'        => 'select',
					'options'     => [
						[
							'value' => 'login_screen',
							'label' => 'Only on login screen',
						],
						[
							'value' => 'side_wide',
							'label' => 'Site wide',
						],
						[
							'label'  => 'Specific page (PRO)',
							'value'  => 'selected_pages',
							'is_pro' => true,
						],
					],
					'tab'         => 'google',
					'if_has'      => ['google_login', 'google_onetap'],
				],
				[
					'title'       => __( 'Select Page', 'login-me-now' ),
					'description' => __( "Select specific pages where you want to show the onetap", 'login-me-now' ),
					'key'         => 'google_pro_selected_pages',
					'default'     => [],
					'type'        => 'multi-select',
					'options'     => $page_options,
					'tab'         => 'google',
					'if_has'      => ['google_login', 'google_onetap'],
					'if_selected' => [
						'google_onetap_display_location' => 'selected_pages',
					],
					'is_pro'      => true,
				], [
					'title'       => __( 'One Tap Prompt Behavior', 'login-me-now' ),
					'description' => __( 'Enable automatic closing on outside clicks.', 'login-me-now' ),
					'key'         => 'google_cancel_on_tap_outside',
					'default'     => false,
					'tab'         => 'google',
					'type'        => 'switch',
					'if_has'      => ['google_login', 'google_onetap'],
				], [
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login', 'google_onetap'],
				], [
					'title'       => __( 'User Role Permission Level', 'login-me-now' ),
					'description' => __( "Select a permission option for users.", 'login-me-now' ),
					'key'         => 'google_pro_default_user_role',
					'default'     => [],
					'type'        => 'select',
					'options'     => $roles_options,
					'tab'         => 'google',
					'if_has'      => ['google_login'],
					'is_pro'      => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				], [
					'title'       => __( 'Update Existing User Name', 'login-me-now' ),
					'description' => __( "Automatically retrieve the existing user first, last, nick & display name from google account upon login using gmail ", 'login-me-now' ),
					'key'         => 'google_update_existing_user_data',
					'default'     => false,
					'type'        => 'switch',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
					'is_pro'      => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				], [
					'title'       => __( 'Add User Profile Picture', 'login-me-now' ),
					'description' => __( "Automatically retrieve the profile picture as avatar from users' google account upon login or register using gmail", 'login-me-now' ),
					'key'         => 'google_pro_user_avatar',
					'default'     => false,
					'type'        => 'switch',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
					'is_pro'      => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'google',
					'if_has' => ['google_login'],
				], [
					'title'       => __( 'Redirection URL', 'login-me-now' ),
					'description' => "Redirect after successful login and registration",
					'placeholder' => 'e.g., https://yourwebsite.com/dashboard',
					'key'         => 'google_pro_redirect_url',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'google',
					'if_has'      => ['google_login'],
					'is_pro'      => true,
				],
			]
		);

		return $dto;
	}

	/**
	 * Listener to authenticate the user
	 */
	public function listener(): ProviderListenersDTO {
		$dto = new ProviderListenersDTO();
		// $dto->is( 'lmn-browser-extension' );

		return $dto;
	}

	/**
	 * Get user information from the provider
	 */
	public function user_data(): ProviderUserDataDTO {
		$dto = new ProviderUserDataDTO();

		return $dto;
	}

	public function onetap() {
		if ( ! $this->show() ) {
			return false;
		}

		if ( is_user_logged_in() ) {
			return false;
		}

		return true;
	}

	private function show(): bool {
		$show_on = SettingsRepository::get( 'google_onetap_display_location', 'side_wide' );

		$return = false;

		switch ( $show_on ) {
			case 'side_wide':
			case 'selected_pages':
				$return = true;
				break;

			case 'login_screen':
				$return = is_login();
				break;
		}

		return $return;
	}

	public function verified( $user_id, $channel_name ) {
		if ( 'google' !== $channel_name ) {
			return;
		}

		add_user_meta( $user_id, 'login_me_now_google_verified', true );
	}

	public function avatar( $url, $id_or_email = '' ) {
		if ( empty( $id_or_email ) ) {
			return $url;
		}

		$user_avatar = SettingsRepository::get( 'google_pro_user_avatar', false );
		if ( ! $user_avatar ) {
			return $url;
		}

		if ( null === $id_or_email || is_object( $id_or_email ) ) {
			return $url;
		}

		$user_id = 0;
		if ( is_email( $id_or_email ) && email_exists( $id_or_email ) ) {
			$user    = get_user_by( 'email', $id_or_email );
			$user_id = (int) $user->ID;
		} elseif ( is_int( $id_or_email ) ) {
			$user_id = (int) $id_or_email;
		} else {
			return $url;
		}

		if ( $user_id ) {
			$_url = User::avatar_url( $user_id, 'google' );
			$url  = $_url ? str_replace( '=s96-c', '', $_url ) : $url;
		}

		return $url;
	}
}