<?php

namespace LoginMeNow\App\Admin;
namespace LoginMeNow\Admin;

class Admin {
	public function __construct() {
		( new Ajax() );
		( new Enqueuer() );
		( new Menu() );
		( new Route() );
		( new Settings() );

		// add_filter( 'admin_footer_text', [$this, 'admin_footer_link'], 99 );

		$this->init_appsero();
	}


	/**
	 * Initialize appsero tracking.
	 *
	 * Removed custom plugins meta data field in 7.0.5.4
	 * since Appsero made this builtin.
	 *
	 * @see https://github.com/Appsero/client
	 *
	 * @return void
	 */
	public function init_appsero() {
		$client = new \LoginMeNow\Admin\Appsero\Client(
			'2392dfad-bb20-4342-bab1-2b2cf6734e97',
			'Login Me Now',
			LOGIN_ME_NOW_FILE
		);

		// Active insights
		$client->set_textdomain( 'login-me-now' );
		$client->insights()->init();
	}
}