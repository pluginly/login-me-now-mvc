<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\Common\IntegrationBase;
use LoginMeNow\WpMVC\Contracts\Provider;

class IntegrationServiceProvider implements Provider {

	public function boot() {
		foreach ( $this->get() as $_i ) {
			$i = new $_i();
			if ( $i instanceof IntegrationBase ) {
				$i->boot();
			}
		}
	}

	public function get(): array {
		return [
			// \LoginMeNow\Integrations\Directorist\Directorist::class,
			// \LoginMeNow\Integrations\EasyDigitalDownloads\EasyDigitalDownloads::class,
			// \LoginMeNow\Integrations\FluentSupport\FluentSupport::class,
			// \LoginMeNow\Integrations\WooCommerce\WooCommerce::class,
			// \LoginMeNow\Integrations\SimpleHistory\SimpleHistory::class,
		];
	}
}