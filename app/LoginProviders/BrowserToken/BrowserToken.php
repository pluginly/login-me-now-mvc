<?php

namespace LoginMeNow\App\LoginProviders\BrowserToken;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\LoginButtonDTO;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\DTO\ProviderUserDataDTO;

class BrowserToken implements LoginProviderBase {

	/**
	 * Unique Key of the Login Provider, like: email-magic-link
	 */
	public static function get_key(): string {
		return 'browser_extension';
	}

	/**
	 * Name of the Login Provider, like: Email Magic Link
	 */
	public static function get_name(): string {
		return 'Browser Extension';
	}

	/**
	 * Login Button to be displayed on the login page
	 */
	public static function get_button(): LoginButtonDTO {
		$dto = new LoginButtonDTO();
		$dto->set_class( 'lmn-browser-extension' );
		$dto->set_icon( 'fa-solid fa-browser' );
		$dto->set_label( 'Login with Browser Extension' );
		$dto->set_modal_behavior( 'window' );

		return $dto;
	}

	/**
	 * Settings Fields to be displayed on the settings page
	 */
	public function get_settings(): ProviderSettingsFieldsDTO {
		$dto = new ProviderSettingsFieldsDTO();
		$dto->set_fields( [
			[
				'title'       => __( 'Enable Browser Extension', 'login-me-now' ),
				'description' => __( "If frequent logins to the dashboard are necessary throughout the day, the browser extension comes in handy.It just takes 1 click to login to dashboard.", 'login-me-now' ),
				'id'          => 'browser_extension',
				'default'     => false,
				'type'        => 'switch',
				'tab'         => 'delegate-access',
			],
			[
				'type' => 'separator',
				'tab'  => 'delegate-access',
			],
		] );

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

	public function boot() {
		include_once login_me_now_dir( 'resources/views/browser-token/extension-popup.php' );
	}
}