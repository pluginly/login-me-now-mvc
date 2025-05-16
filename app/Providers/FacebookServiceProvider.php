<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Http\Controllers\FacebookController;
use LoginMeNow\App\Repositories\FacebookRepository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FacebookServiceProvider implements LoginProviderBase {

	public function boot() {
		( new FacebookController() )->listen();
	}

	public static function get_key(): string {
		return 'facebook_login';
	}

	public static function get_name(): string {
		return 'Facebook';
	}

	public static function get_button(): string {
		return FacebookRepository::get_button();
	}

	public function get_settings(): ProviderSettingsFieldsDTO {

		$roles_options = [];
		$roles         = login_me_now_get_pages();
		$dto           = new ProviderSettingsFieldsDTO();

		foreach ( $roles as $key => $role ) {
			$roles_options[] = [
				'value' => $key,
				'label' => $role,
			];
		}

		$dto = new ProviderSettingsFieldsDTO();
		$dto->set_fields(
			[
				[
					'title'   => __( 'Enable Facebook Login', 'login-me-now' ),
					'key'     => 'facebook_login',
					'default' => false,
					'type'    => 'switch',
					'tab'     => 'facebook',
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'       => __( 'App ID', 'login-me-now' ),
					'description' => __( 'Enter your Facebook App ID, get <a target="__blank" href="https://developers.facebook.com/apps/">Facebook App ID</a>', 'login-me-now' ),
					'placeholder' => '14343****34343',
					'key'         => 'facebook_app_id',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'facebook',
					'if_has'      => ['facebook_login'],
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'       => __( 'App Secret', 'login-me-now' ),
					'description' => __( 'Enter your Facebook App Secret, get <a target="__blank" href="https://developers.facebook.com/apps/">Facebook App Secret</a>', 'login-me-now' ),
					'placeholder' => '2ae0c**********e06',
					'key'         => 'facebook_app_secret',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'facebook',
					'if_has'      => ['facebook_login'],
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'       => __( 'User Role Permission Level', 'login-me-now' ),
					'description' => __( 'Select the role that will be assigned to new users who sign up', 'login-me-now' ),
					'key'         => 'facebook_pro_default_user_role',
					'default'     => false,
					'type'        => 'select',
					'options'     => $roles_options,
					'tab'         => 'facebook',
					'if_has'      => ['facebook_login'],
					'is_pro'      => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'       => __( 'Update Existing User Name', 'login-me-now' ),
					'description' => __( 'Automatically retrieve the existing user first, last, nick & display name from facebook account upon login using facebook', 'login-me-now' ),
					'key'         => 'facebook_update_existing_user_data',
					'default'     => false,
					'type'        => 'switch',
					'tab'         => 'facebook',
					'if_has'      => ['facebook_login'],
					'is_pro'      => true,
				],
				[
					'type'   => 'separator',
					'tab'    => 'facebook',
					'if_has' => ['facebook_login'],
				],
				[
					'title'       => __( 'Redirection URL', 'login-me-now' ),
					'description' => "Redirect after successful login and registration",
					'placeholder' => 'e.g., https://yourwebsite.com/dashboard',
					'key'         => 'facebook_pro_redirect_url',
					'default'     => '',
					'type'        => 'text',
					'tab'         => 'facebook',
					'if_has'      => ['facebook_login'],
					'is_pro'      => true,
				],
			]
		);

		return $dto;
	}
}