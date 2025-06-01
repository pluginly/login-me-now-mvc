<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Helpers\AjaxCheck;
use LoginMeNow\App\Http\Controllers\TemporaryLoginController;

class TemporaryLoginServiceProvider implements LoginProviderBase {

	use AjaxCheck;

	/**
	 * Unique Key of the Login Provider, like: email-magic-link
	 */
	public function boot() {
		( new TemporaryLoginController() )->listen_temporary_link();
	}

	public static function get_key(): string {
		return 'temporary_login';
	}

	/**
	 * Name of the Login Provider, like: Email Magic Link
	 */
	public static function get_name(): string {
		return 'Tempoarary Login';
	}

	/**
	 * Login Button to be displayed on the login page
	 */
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
				'title'       => __( 'Temporary Login', 'login-me-now' ),
				'description' => __( "Generate a tokenized link to create a temporary login. By opening the link, anyone can log in without requiring a password.", 'login-me-now' ),
				'key'         => 'temporary_login',
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