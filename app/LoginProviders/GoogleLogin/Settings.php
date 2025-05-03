<?php
/**
 * @author  Pluginly
 * @since   1.7
 * @version 1.9
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\Helper;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ) {

		$page_options = [];
		foreach ( Helper::get_pages() as $page ) {

			$page_options[] = [
				'value' => $page['id'],
				'label' => $page['name'],
			];
		}

		$roles_options = [];

		foreach ( Helper::get_user_roles() as $key => $role ) {

			$roles_options[] = [
				'value' => $key,
				'label' => $role,
			];
		}

		$fields[] = [
			'title'         => __( 'Enable Google Login', 'login-me-now' ),
			'id'            => 'google_login',
			'previous_data' => SettingsRepository::get( 'google_login', false ),
			'type'          => 'switch',
			'tab'           => 'google',
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'Client ID', 'login-me-now' ),
			'description'   => __( 'Enter your google Client ID here, <a target="__blank" href="https://developers.google.com/identity/gsi/web/guides/get-google-api-clientid">get Client ID</a>', 'login-me-now' ),
			'id'            => 'google_client_id',
			'placeholder'   => 'ex: **********--**********.apps.googleusercontent.com',
			'previous_data' => SettingsRepository::get( 'google_client_id', '' ),
			'type'          => 'text',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'Client Secret', 'login-me-now' ),
			'description'   => __( "Enter your Client Secret key here.", 'login-me-now' ),
			'id'            => 'google_client_secret',
			'placeholder'   => 'e.g., ******-****-******',
			'previous_data' => SettingsRepository::get( 'google_client_secret', '' ),
			'type'          => 'text',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'OneTap', 'login-me-now' ),
			'description'   => __( "Enable google onetap login", 'login-me-now' ),
			'id'            => 'google_onetap',
			'previous_data' => SettingsRepository::get( 'google_onetap', true ),
			'type'          => 'switch',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login', 'google_onetap'],
		];

		$fields[] = [
			'title'         => __( 'Select Location', 'login-me-now' ),
			'description'   => __( "Choose a location where you want to show the onetap", 'login-me-now' ),
			'id'            => 'google_onetap_display_location',
			'previous_data' => SettingsRepository::get( 'google_onetap_display_location', 'side_wide' ),
			'type'          => 'select',
			'options'       => [
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
			'tab'           => 'google',
			'if_has'        => ['google_login', 'google_onetap'],
		];
		$fields[] = [
			'title'         => __( 'Select Page', 'login-me-now' ),
			'description'   => __( "Select specific pages where you want to show the onetap", 'login-me-now' ),
			'id'            => 'google_pro_selected_pages',
			'previous_data' => SettingsRepository::get( 'google_pro_selected_pages', [] ),
			'type'          => 'multi-select',
			'options'       => $page_options,
			'tab'           => 'google',
			'if_has'        => ['google_login', 'google_onetap'],
			'if_selected'   => [
				'google_onetap_display_location' => 'selected_pages',
			],
			'is_pro'        => true,
		];

		$fields[] = [
			'title'         => __( 'One Tap Prompt Behavior', 'login-me-now' ),
			'description'   => __( 'Enable automatic closing on outside clicks.', 'login-me-now' ),
			'id'            => 'google_cancel_on_tap_outside',
			'previous_data' => SettingsRepository::get( 'google_cancel_on_tap_outside', false ),
			'tab'           => 'google',
			'type'          => 'switch',
			'if_has'        => ['google_login', 'google_onetap'],
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login', 'google_onetap'],
		];

		$fields[] = [
			'title'         => __( 'User Role Permission Level', 'login-me-now' ),
			'description'   => __( "Select a permission option for users.", 'login-me-now' ),
			'id'            => 'google_pro_default_user_role',
			'previous_data' => SettingsRepository::get( 'google_pro_default_user_role', '' ),
			'type'          => 'select',
			'options'       => $roles_options,
			'tab'           => 'google',
			'if_has'        => ['google_login'],
			'is_pro'        => true,
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'Update Existing User Name', 'login-me-now' ),
			'description'   => __( "Automatically retrieve the existing user first, last, nick & display name from google account upon login using gmail ", 'login-me-now' ),
			'id'            => 'google_update_existing_user_data',
			'previous_data' => SettingsRepository::get( 'google_update_existing_user_data', false ),
			'type'          => 'switch',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
			'is_pro'        => true,
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'Add User Profile Picture', 'login-me-now' ),
			'description'   => __( "Automatically retrieve the profile picture as avatar from users' google account upon login or register using gmail", 'login-me-now' ),
			'id'            => 'google_pro_user_avatar',
			'previous_data' => SettingsRepository::get( 'google_pro_user_avatar', false ),
			'type'          => 'switch',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
			'is_pro'        => true,
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'google',
			'if_has' => ['google_login'],
		];

		$fields[] = [
			'title'         => __( 'Redirection URL', 'login-me-now' ),
			'description'   => "Redirect after successful login and registration",
			'placeholder'   => 'e.g., https://yourwebsite.com/dashboard',
			'id'            => 'google_pro_redirect_url',
			'previous_data' => SettingsRepository::get( 'google_pro_redirect_url', '' ),
			'type'          => 'text',
			'tab'           => 'google',
			'if_has'        => ['google_login'],
			'is_pro'        => true,
		];

		return $fields;
	}
}