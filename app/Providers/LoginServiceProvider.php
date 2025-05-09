<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Repositories\LoginProvidersRepository;
use LoginMeNow\App\Repositories\SettingsRepository;
use LoginMeNow\WpMVC\Contracts\Provider;

final class LoginServiceProvider implements Provider {

	private array $settings  = [];
	private array $listeners = [];

	public function boot() {
		$providers = LoginProvidersRepository::get_available_providers();

		foreach ( $providers as $_l ) {
			$provider = new $_l();

			if ( $provider instanceof LoginProviderBase ) {
				$this->register( $provider );
				$provider->boot();
			}
		}

		add_filter( 'login_me_now_settings_fields', [$this, 'register_fields'] );
		add_action( 'wp_enqueue_scripts', [$this, 'register_scripts'], 15 );
	}

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

	public function register_fields( array $fields ): array {
		return array_merge( $this->settings, $fields );
	}

	public function register_scripts() {
		wp_register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
		wp_register_script( 'login-me-now-main', login_me_now_url( 'assets/public/main.js' ) );
		wp_register_style( 'login-me-now-main', login_me_now_url( 'assets/public/main.css' ) );

		$redirect = admin_url();

		$data = [
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'facebook_app_id'           => SettingsRepository::get( 'facebook_app_id', '' ),
			'facebook_pro_redirect_url' => $redirect,
			'google_client_id'          => SettingsRepository::get( 'google_client_id', '' ),
			'google_pro_redirect_url'   => $redirect,
		];

		if ( defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			$data['facebook_pro_redirect_url'] = SettingsRepository::get( 'facebook_pro_redirect_url', $redirect );
			$data['google_pro_redirect_url']   = SettingsRepository::get( 'google_pro_redirect_url', $redirect );
		}

		wp_localize_script( 'login-me-now-main', 'login_me_now_social_login_main_obj', $data );
	}
}