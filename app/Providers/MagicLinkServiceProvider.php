<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\DTO\ProviderUserDataDTO;
use LoginMeNow\App\Http\Controllers\MagicLinkController;
use LoginMeNow\App\Repositories\MagicLinkRepository;

class MagicLinkServiceProvider implements LoginProviderBase {

	/**
	 * Unique Key of the Login Provider, like: email-magic-link
	 */
	public function boot() {
		(new MagicLinkController())->listen_magic_link();
	}
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
	public static function get_button(): string {
		return MagicLinkRepository::get_button();
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
				'placeholder' => 'Email Magic Link Title',
				'default'     => 'Enter email magic link title',
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
				'placeholder' => 'Enter Email Magic Link Descriptions',
				'default'     => 'Enter Email Magic Link Descriptions',
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
				'default'     => 'Enter magic link button text',
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
				'placeholder' => '300',
				'default'     => 300,
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
				'default'     => admin_url(),
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

		return $dto;
	}

	/**
	 * Get user information from the provider
	 */
	public function user_data(): ProviderUserDataDTO {
		$dto = new ProviderUserDataDTO();
		
		return $dto;
	}
	
}