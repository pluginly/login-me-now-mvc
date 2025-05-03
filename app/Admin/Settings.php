<?php
/**
 * @author  Pluginly
 * @since   1.0
 * @version 1.9
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Repositories\SettingsRepository;

class Settings {
	public function __construct() {
		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function register_fields( array $fields ) {

		$fields[] = [
			'title'         => 'Enable Login Me Now',
			'description'   => 'Use login features for WordPress native login page.',
			'id'            => 'wp_native_login_enable',
			'previous_data' => SettingsRepository::get( 'wp_native_login_enable', true ),
			'type'          => 'switch',
			'tab'           => 'wp-native-login',
		];

		$fields[] = [
			'type' => 'separator',
			'tab'  => 'wp-native-login',
		];

		$fields[] = [
			'title'         => __( 'Select Login Providers', 'login-me-now' ),
			'description'   => __( "Choose what login methods you would like to show.", 'login-me-now' ),
			'id'            => 'wp_native_login_providers',
			'previous_data' => SettingsRepository::get( 'wp_native_login_providers', 'email_magic_link' ),
			'type'          => 'multi-select',
			'options'       => [
				[
					'value' => 'google',
					'label' => 'Google',
				],
				[
					'value' => 'facebook',
					'label' => 'Facebook',
				],
				[
					'value'  => 'email_magic_link',
					'label'  => 'Email Magic Link',
					'is_pro' => true,
				],
			],
			'tab'           => 'wp-native-login',
			'if_has'        => ['wp_native_login_enable'],
		];

		$fields[] = [
			'title'         => 'Enter your license key',
			'description'   => "An active license key is needed to unlock all the pro features and receive automatic plugin updates. Don't have a license key? <a href='https://pluginly.com/login-me-now-pro/' target='_blank'>Get it here</a>",
			'id'            => 'lmn_pro_lic',
			'previous_data' => SettingsRepository::get( 'lmn_pro_lic', '' ),
			'type'          => 'text',
			'tab'           => 'license',
		];

		return $fields;
	}
}