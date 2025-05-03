<?php

namespace LoginMeNow\Logins\LinkLogin;

use LoginMeNow\Common\Singleton;
use LoginMeNow\Repositories\SettingsRepository;

class Settings {
	use Singleton;

	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ) {

		$fields[] = [
			'title'         => __( 'Enable Temporary Login', 'login-me-now' ),
			'description'   => __( "Generate a tokenized link to create a temporary login. By opening the link, anyone can log in without requiring a password.", 'login-me-now' ),
			'id'            => 'temporary_login',
			'previous_data' => SettingsRepository::get( 'temporary_login', true ),
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