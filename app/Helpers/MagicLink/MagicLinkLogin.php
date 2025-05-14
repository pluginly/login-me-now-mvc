<?php

namespace LoginMeNow\App\Helpers\MagicLink;

use LoginMeNow\App\Helpers\MagicLink\ModuleBase;
use LoginMeNow\App\Http\Controllers\MagicLinkController;
use LoginMeNow\App\Repositories\SettingsRepository;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MagicLinkLogin extends ModuleBase {

	public function setup(): void {
        error_log('12222');
		$this->action( 'init', [MagicLinkController::class, 'listen_magic_link'] );

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