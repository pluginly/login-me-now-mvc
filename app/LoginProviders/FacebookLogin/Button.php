<?php
/**
 * @author  Pluginly
 * @since   1.4.0
 * @version 1.6.2
 */

namespace LoginMeNow\Logins\FacebookLogin;

use LoginMeNow\Common\LoginProviderButtonBase;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\User;

class Button extends LoginProviderButtonBase {

	public function shortcodes(): void {
		add_shortcode( 'login_me_now_facebook_button', [$this, 'get_button'] );
	}

	public function get_button():string {
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

	public static function is_enabled(): bool {
		$enable     = SettingsRepository::get( 'facebook_login', false );
		$app_id     = SettingsRepository::get( 'facebook_app_id', '' );
		$app_secret = SettingsRepository::get( 'facebook_app_secret', '' );

		if ( $enable && $app_id && $app_secret ) {
			return true;
		}

		return false;
	}
}