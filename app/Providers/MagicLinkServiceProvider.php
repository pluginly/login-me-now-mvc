<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\LoginButtonDTO;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\DTO\ProviderUserDataDTO;

class MagicLinkServiceProvider implements LoginProviderBase {

	/**
	 * Unique Key of the Login Provider, like: email-magic-link
	 */
	public static function get_key(): string {
		return 'email_magic_link_enable';
	}

	/**
	 * Name of the Login Provider, like: Email Magic Link
	 */
	public static function get_name(): string {
		return 'Magic Link';
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
				'title'   => __( 'Enable Magic Link Login', 'login-me-now' ),
				'key'     => 'email_magic_link_enable',
				'default' => false,
				'type'    => 'switch',
				'tab'     => 'email-magic-link',
			],
			[
				'type'   => 'separator',
				'tab'    => 'email-magic-link',
				'if_has' => ['email_magic_link_enable'],
			],
			[
				'title'       => 'Title',
				'tooltip'     => 'Enter the form title',
				'key'         => 'email_magic_link_title',
				'placeholder' => 'e.g., Email Magic Link',
				'default'     => false,
				'type'        => 'text',
				'tab'         => 'email-magic-link',
				'if_has'      => ['email_magic_link_enable'],
			],
			[
				'type'   => 'separator',
				'tab'    => 'email-magic-link',
				'if_has' => ['email_magic_link_enable'],
			],
			[
				'title'       => 'Description',
				'tooltip'     => 'Enter the form description',
				'key'         => 'email_magic_link_description',
				'placeholder' => 'e.g., Email Magic Link',
				'default'     => false,
				'type'        => 'textarea',
				'tab'         => 'email-magic-link',
				'if_has'      => ['email_magic_link_enable'],
			],
			[
				'type'   => 'separator',
				'tab'    => 'email-magic-link',
				'if_has' => ['email_magic_link_enable'],
			],
			[
				'title'       => 'Button Text',
				'description' => 'Enter continue with magic link button text',
				'key'         => 'magic_link_login_button_text',
				'placeholder' => 'ex. Continue with Magic Link',
				'default'     => false,
				'type'        => 'text',
				'tab'         => 'email-magic-link',
				'if_has'      => ['email_magic_link_enable'],
			],
			[
				'type'   => 'separator',
				'tab'    => 'email-magic-link',
				'if_has' => ['email_magic_link_enable'],
			],
			[
				'title'       => 'Expiration',
				'description' => 'Enter the expiration of link in seconds',
				'key'         => 'email_magic_link_expiration',
				'placeholder' => 'e.g., 300',
				'default'     => false,
				'type'        => 'number',
				'tab'         => 'email-magic-link',
				'if_has'      => ['email_magic_link_enable'],
			],
			[
				'type'   => 'separator',
				'tab'    => 'email-magic-link',
				'if_has' => ['email_magic_link_enable'],
			],
			[
				'title'       => 'Redirection URL',
				'description' => 'Redirect after successful login',
				'key'         => 'Redirect after successful login',
				'placeholder' => 'e.g., 300',
				'default'     => false,
				'type'        => 'text',
				'tab'         => 'email-magic-link',
				'if_has'      => ['email_magic_link_enable'],
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