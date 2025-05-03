<?php
/**
 * @author  Pluginly
 * @since   1.8
 * @version 1.9
 */

namespace LoginMeNow\Logins\MagicLinkLogin;

use LoginMeNow\Repositories\SettingsRepository;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ) {
		$fields[] = [
			'title'         => 'Enable Magic Link Login',
			'id'            => 'email_magic_link_enable',
			'previous_data' => SettingsRepository::get( 'email_magic_link_enable', true ),
			'type'          => 'switch',
			'tab'           => 'email-magic-link',
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'email-magic-link',
			'if_has' => ['email_magic_link_enable'],
		];

		$fields[] = [
			'title'         => 'Title',
			'tooltip'       => 'Enter the form title',
			'id'            => 'email_magic_link_title',
			'placeholder'   => 'e.g., Email Magic Link',
			'previous_data' => SettingsRepository::get( 'email_magic_link_title', 'Email Magic Link' ),
			'type'          => 'text',
			'tab'           => 'email-magic-link',
			'if_has'        => ['email_magic_link_enable'],
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'email-magic-link',
			'if_has' => ['email_magic_link_enable'],
		];

		$fields[] = [
			'title'         => 'Description',
			'description'   => 'Enter the form description',
			'id'            => 'email_magic_link_description',
			'placeholder'   => 'e.g., Email Magic Link',
			'previous_data' => SettingsRepository::get( 'email_magic_link_description', 'Enter your registered email address to receive a quick login link directly in your inbox.' ),
			'type'          => 'textarea',
			'tab'           => 'email-magic-link',
			'if_has'        => ['email_magic_link_enable'],
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'email-magic-link',
			'if_has' => ['email_magic_link_enable'],
		];

		$fields[] = [
			'title'         => 'Button Text',
			'description'   => 'Enter continue with magic link button text',
			'id'            => 'magic_link_login_button_text',
			'placeholder'   => 'ex. Continue with Magic Link',
			'previous_data' => SettingsRepository::get( 'magic_link_login_button_text', 'Continue with Magic Link' ),
			'type'          => 'text',
			'tab'           => 'email-magic-link',
			'if_has'        => ['email_magic_link_enable'],
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'email-magic-link',
			'if_has' => ['email_magic_link_enable'],
		];

		$fields[] = [
			'title'         => 'Expiration',
			'description'   => 'Enter the expiration of link in seconds',
			'id'            => 'email_magic_link_expiration',
			'placeholder'   => 'e.g., 300',
			'previous_data' => SettingsRepository::get( 'email_magic_link_expiration', 300 ),
			'type'          => 'number',
			'tab'           => 'email-magic-link',
			'if_has'        => ['email_magic_link_enable'],
		];
		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'email-magic-link',
			'if_has' => ['email_magic_link_enable'],
		];

		$fields[] = [
			'title'         => __( 'Redirection URL', 'login-me-now' ),
			'description'   => "Redirect after successful login",
			'id'            => 'email_magic_link_pro_redirect_url',
			'previous_data' => SettingsRepository::get( 'email_magic_link_pro_redirect_url', '' ),
			'type'          => 'text',
			'tab'           => 'email-magic-link',
			'if_has'        => ['email_magic_link_enable'],
			'is_pro'        => true,
		];

		return $fields;
	}
}