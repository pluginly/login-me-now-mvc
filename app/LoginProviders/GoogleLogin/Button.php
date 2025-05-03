<?php
/**
 * @author  Pluginly
 * @since   1.5
 * @version 1.9
 */

namespace LoginMeNow\Logins\GoogleLogin;

use LoginMeNow\Common\LoginProviderButtonBase;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\User;

class Button extends LoginProviderButtonBase {

	public function shortcodes(): void {
		add_shortcode( 'login_me_now_google_button', [$this, 'get_button'] );
	}

	public function get_button(): string {
		if ( ! $this->is_enabled() ) {
			return '';
		}

		if ( User::is_logged_in() ) {
			return '';
		}

		wp_enqueue_style( 'login-me-now-social-login-main' );
		wp_enqueue_script( 'login-me-now-social-login-main' );

		return $this->html();
	}

	public function html(): string {
		ob_start();
		include __DIR__ . '/Views/Button.php';
		$html = ob_get_clean();

		return $html;
	}

	public function is_enabled(): bool {
		$enable        = SettingsRepository::get( 'google_login', false );
		$client_id     = SettingsRepository::get( 'google_client_id', '' );
		$client_secret = SettingsRepository::get( 'google_client_secret', '' );

		if ( $enable && $client_id && $client_secret ) {
			return true;
		}

		return false;
	}
}