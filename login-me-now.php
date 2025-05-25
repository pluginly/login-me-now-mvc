<?php

defined( 'ABSPATH' ) || exit;

use LoginMeNow\WpMVC\App;

/**
 * Plugin Name:       Login Me Now 2.0
 * Description:       This plugin is build with WpMVC framework
 * Version:           2.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Tested up to:      6.8
 * Author:            Pluginly
 * Author URI:        http://github.com/wpmvc
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       login-me-now
 * Domain Path:       /languages
 */
require_once __DIR__ . '/vendor/vendor-src/autoload.php';
require_once __DIR__ . '/app/Helpers/helper.php';

final class LoginMeNow {
	public static LoginMeNow $instance;

	public static function instance(): LoginMeNow {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function load() {
		$application = App::instance();

		$application->boot( __FILE__, __DIR__ );

		/**
		 * Fires once activated plugins have loaded.
		 */
		add_action(
			'init', function () use ( $application ): void {

				do_action( 'before_load_login_me_now' );

				$application->load();

				do_action( 'after_load_login_me_now' );
			}
		);
	}
}

LoginMeNow::instance()->load();