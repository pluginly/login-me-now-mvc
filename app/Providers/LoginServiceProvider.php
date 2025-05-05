<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderListenersDTO;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\LoginProviders\BrowserToken\BrowserToken;
use LoginMeNow\App\LoginProviders\MagicLink\MagicLink;
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
				MagicLink::class,
			]
		);
	}

	public function register_fields( array $fields ): array {
		$fields[] = [
			'title'         => __( 'Enable Browser Extension', 'login-me-now' ),
			'description'   => __( "If frequent logins to the dashboard are necessary throughout the day, the browser extension comes in handy.It just takes 1 click to login to dashboard.", 'login-me-now' ),
			'id'            => 'browser_extension',
			'previous_data' => false,
			'type'          => 'switch',
			'tab'           => 'delegate-access',
		];

		$fields[] = [
			'type' => 'separator',
			'tab'  => 'delegate-access',
		];

		return $fields;
	}
}