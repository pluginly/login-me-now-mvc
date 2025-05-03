<?php
/**
 * @author 	Pluginly
 * @since	1.6.2
 * @version 1.6.2
 */

namespace LoginMeNow\Admin;

use LoginMeNow\Controllers\SettingsController;
use LoginMeNow\Repositories\SettingsRepository;

class Route extends \LoginMeNow\Common\RouteBase {
	private $endpoint = 'admin';

	public function register_routes(): void {
		$this->get( $this->endpoint . '/settings', [SettingsRepository::class, 'all'] );
		$this->post( $this->endpoint . '/settings/fields', [SettingsRepository::class, 'get_fields'] );
		$this->post( $this->endpoint . '/settings/save', [SettingsController::class, 'save'] );
		$this->get( $this->endpoint . '/settings/update', [SettingsRepository::class, 'all'] );
	}
}