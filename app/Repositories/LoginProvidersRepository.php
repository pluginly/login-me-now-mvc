<?php

namespace LoginMeNow\App\Repositories;

class LoginProvidersRepository {

	/**
	 * Return the providers list.
	 */
	public static function get_available_providers_list(): array {
		$_providers = self::get_available_providers();
		$providers  = [];

		foreach ( $_providers as $provider ) {
			$providers[] = [
				'value' => $provider::get_key(),
				'label' => $provider::get_name(),
			];
		}

		return $providers;
	}

	/**
	 * Return the providers class list as an associative array: [ key => class ]
	 */
	public static function get_available_providers(): array {
		$providers = apply_filters( 'login_me_now_available_providers', [
			\LoginMeNow\App\Providers\GoogleServiceProvider::class,//string (GoogleServiceProviders)
			// \LoginMeNow\App\Providers\FacebookServiceProvider::class,
			// \LoginMeNow\App\Providers\MagicLinkServiceProvider::class,
		] );

		$mapped = [];
		foreach ( $providers as $provider_class ) {
			if ( method_exists( $provider_class, 'get_key' ) ) {
				$mapped[$provider_class::get_key()] = $provider_class;
			}
		}

		return $mapped;
	}

	/**
	 * Return only valid and active login provider buttons.
	 */
	public function get_provider_buttons( array $login_providers ): array {
		$available = self::get_available_providers();
		$providers = [];

		foreach ( $login_providers as $key ) {
			if ( isset( $available[$key] ) ) {
				$provider_class = $available[$key];

				if ( method_exists( $provider_class, 'get_button' ) && $provider_class::get_button() ) {
					$providers[$key] = $provider_class;
				}
			}
		}

		return $providers;
	}

	/**
	 * Render or return the HTML for login provider buttons.
	 */
	public function get_provider_buttons_html( bool $return = false, array $login_providers, string $display_position = 'after' ) {
		$providers = $this->get_provider_buttons( $login_providers );

		ob_start();
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include_once login_me_now_dir( 'resources/views/login-form.php' );
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		$html = ob_get_clean();

		return $return ? $html : print( $html );
	}
}