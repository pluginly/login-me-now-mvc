<?php
/**
 * @author  Pluginly
 * @since   1.6.2
 * @version 1.9
 */

namespace LoginMeNow\Logins\BrowserTokenLogin;

use LoginMeNow\Repositories\SettingsRepository;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ): array {
		$fields[] = [
			'title'         => __( 'Enable Browser Extension', 'login-me-now' ),
			'description'   => __( "If frequent logins to the dashboard are necessary throughout the day, the browser extension comes in handy.It just takes 1 click to login to dashboard.", 'login-me-now' ),
			'id'            => 'browser_extension',
			'previous_data' => SettingsRepository::get( 'browser_extension', false ),
			'type'          => 'switch',
			'tab'           => 'delegate-access',
		];

		$fields[] = [
			'type'   => 'separator',
			'tab'    => 'delegate-access',
		];

		return $fields;
	}
}