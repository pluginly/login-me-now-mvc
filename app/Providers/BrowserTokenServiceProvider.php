<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;

class BrowserTokenServiceProvider implements LoginProviderBase {

	public function boot() {
		if ( ! is_admin() ) {
			return;
		}
		ob_start();
		include_once login_me_now_dir( 'resources/views/browser-token/extension-popup.php' );
		return ob_get_clean();
	}

	public static function get_key(): string {
		return 'browser_extension';
	}

	public static function get_name(): string {
		return 'Browser Extension';
	}

	public static function get_button(): string {
		return '';
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
				'key'         => 'browser_extension',
				'default'     => true,
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
}