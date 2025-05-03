<?php
/**
 * @author  Pluginly
 * @since   1.9
 * @version 1.9
 */

namespace LoginMeNow\Logins\UserSwitchingLogin;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Repositories\SettingsRepository;

class Settings {
	use Singleton;

	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ) {
		$fields[] = [
			'title'         => 'Enable User Switching',
			'description'   => 'Easily switch between user accounts. Instant & in one-click!.',
			'id'            => 'user_switching',
			'previous_data' => SettingsRepository::get( 'user_switching', true ),
			'type'          => 'switch',
			'tab'           => 'delegate-access',
		];

		$fields[] = [
			'type' => 'separator',
			'tab'  => 'delegate-access',
		];

		return $fields;
	}
}