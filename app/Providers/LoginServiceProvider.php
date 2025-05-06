<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\LoginProviders\BrowserToken\BrowserToken;
use LoginMeNow\WpMVC\Contracts\Provider;

final class LoginServiceProvider implements Provider {

	private array $settings  = [];
	private array $listeners = [];

	public function register( LoginProviderBase $provider ) {
		try {
			$this->register_settings( $provider->get_settings() );
			//	$this->register_listeners( $provider->get_settings() );

		} catch ( \Throwable $th ) {
			throw $th;
		}
	}

	public function register_settings( ProviderSettingsFieldsDTO $settings ) {
		$this->settings = array_merge( $this->settings, $settings->get_fields() );
	}

	public function register_listeners( ProviderListenersDTO $listeners ) {
		$this->listeners[] = $listeners;

		// $get / rest or else;
	}

	public function boot() {
		foreach ( $this->get() as $_l ) {
			$provider = new $_l();

			if ( $provider instanceof LoginProviderBase ) {
				$this->register( $provider );

				$provider->boot();
			}
		}

		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
	}

	public function get(): array {
		return apply_filters(
			'login_me_now_login_providers',
			[
				BrowserToken::class,
			]
		);
	}

	public function register_fields( array $fields ): array {
		return array_merge( $this->settings, $fields );
	}
}