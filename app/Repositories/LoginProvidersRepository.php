<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\Logins\FacebookLogin\Button as FacebookButton;
use LoginMeNow\Logins\GoogleLogin\Button as GoogleButton;
use LoginMeNow\Logins\MagicLinkLogin\Button as MagicLinkLoginButton;

class LoginProvidersRepository {

	/**
	 * Return the providers array as-is.
	 */
	public function get_providers( array $providers ): array {
		return $providers;
	}

	/**
	 * Return only valid and active login provider buttons.
	 */
	public function get_provider_buttons( array $login_providers ): array {
		$available_providers = [
			'google'           => GoogleButton::class,
			'facebook'         => FacebookButton::class,
			'email_magic_link' => MagicLinkLoginButton::class,
		];

		$buttons = [];

		foreach ( $login_providers as $provider_key ) {
			if ( isset( $available_providers[$provider_key] ) ) {
				$provider_instance = new $available_providers[$provider_key]();

				if ( method_exists( $provider_instance, 'get_button' ) && $provider_instance->get_button() ) {
					$buttons[$provider_key] = $provider_instance;
				}
			}
		}

		return $buttons;
	}

	/**
	 * Render or return the HTML for login provider buttons.
	 */
	public function get_provider_buttons_html( bool $return = false, array $login_providers, string $display_position = 'after' ) {
		$buttons = $this->get_provider_buttons( $login_providers );

		ob_start();
		include LOGIN_ME_NOW_TEMPLATE_PATH . '/login-form.php';
		$html = ob_get_clean();

		return $return ? $html : print( $html );
	}
}