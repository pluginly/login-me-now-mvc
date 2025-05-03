<?php
/**
 * @author  Pluginly
 * @since   1.9
 * @version 1.9
 */

namespace LoginMeNow\Logins\MagicLinkLogin;

use LoginMeNow\Common\LoginProviderButtonBase;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\Utils\User;

class Button extends LoginProviderButtonBase {

	public function shortcodes(): void {
		add_shortcode( 'login_me_now_email_magic_link_button', [$this, 'get_button'] );
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
		return SettingsRepository::get( 'email_magic_link_enable', true );
	}
}