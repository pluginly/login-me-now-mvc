<?php
/**
 * @author  Pluginly
 * @since   1.8
 * @version 1.8
 */

namespace LoginMeNow\Logins\MagicLinkLogin;

use LoginMeNow\Controllers\MagicLinkController;

defined( 'ABSPATH' ) || exit;

class Route extends \LoginMeNow\Common\RouteBase {

	public function register_routes(): void {
		$this->post( '/send-magic-link', [MagicLinkController::class, 'send_magic_link'] );
	}

	public function permission_check( \WP_REST_Request $request ) {
		return true;
	}
}