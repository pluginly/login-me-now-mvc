<?php
/**
 * @author  Pluginly
 * @since   1.8
 * @version 1.8
 */

namespace LoginMeNow\Logins\MagicLinkLogin;

use LoginMeNow\Common\ModuleBase;
use LoginMeNow\Controllers\MagicLinkController;
use LoginMeNow\Repositories\SettingsRepository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MagicLinkLogin extends ModuleBase {

	public function setup(): void {
		( new Settings() );

		if ( ! self::show() ) {
			return;
		}

		$this->action( 'init', [MagicLinkController::class, 'listen_magic_link'] );

		( new Route() );
		( new Button() );
		( new Route() );
	}

	public static function show(): bool {
		$enable = SettingsRepository::get( 'email_magic_link_enable', false );

		if ( $enable ) {
			return true;
		}

		return false;
	}

	public static function show_on_native_login() {
		return self::show() && SettingsRepository::get( 'email_magic_link_native_login', true );
	}
}