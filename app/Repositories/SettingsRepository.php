<?php

namespace LoginMeNow\App\Repositories;

class SettingsRepository {
	private static string $option_name = 'login_me_now_admin_settings';
	private static array $settings;
	private static $instance;

	public function __construct() {
		self::$settings = get_option( self::$option_name, [] );
	}

	public static function init(): object {
		$class = get_called_class();
		if ( ! isset( self::$instance ) ) {
			self::$instance = new $class();
		}

		return self::$instance;
	}

	public function all(): array {
		$db_option = get_option( self::$option_name, [] );

		$defaults = apply_filters(
			'login_me_now_dashboard_rest_options',
			[
				'logs'              => true,

				'activity_logs'     => false,
				'social_login'      => true,
				'temporary_login'   => true,
				'browser_extension' => true,

				'user_switching'    => true,

				'get_user_roles'    => login_me_now_get_user_roles(),
				'get_pages'         => login_me_now_get_pages(),
			]
		);

		$updated_option = wp_parse_args( $db_option, $defaults );

		return $updated_option;
	}

	public static function get( string $key, $default = null ) {
		self::$settings = get_option( self::$option_name, [] );

		return self::$settings[$key] ?? $default;
	}

	public static function update( string $key, $value ): void {
		self::$settings       = get_option( self::$option_name, [] );
		self::$settings[$key] = $value;
		update_option( self::$option_name, self::$settings );
	}

	public function get_fields(): array {
		$fields = apply_filters( 'login_me_now_settings_fields', [] );
		$values = self::all();

		foreach ( $fields as $index => $field ) {
			$key = $field['key'] ?? '';
			if ( isset( $values[$key] ) ) {
				$fields[$index]['previous_data'] = $values[$key];
			} else {
				$fields[$index]['previous_data'] = $fields[$index]['default'] ?? '';
			}
		}

		return $fields;
	}

	public static function save( string $key, $value ) {
		self::$settings       = get_option( self::$option_name, [] );
		self::$settings[$key] = $value;
		update_option( self::$option_name, self::$settings );
	}
}