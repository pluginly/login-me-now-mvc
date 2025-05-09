<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Repositories\LoginProvidersRepository;
use LoginMeNow\App\Repositories\SettingsRepository;
use LoginMeNow\WpMVC\Contracts\Provider;

class LoginFormServiceProvider implements Provider {

	public function boot() {
		// Load scripts and styles
		add_action( 'login_footer', [$this, 'enqueue_login_script'], 50 );
		add_action( 'login_enqueue_scripts', [$this, 'login_enqueue_scripts'] );

		// Display login buttons on login/register forms
		add_action( 'login_form', [$this, 'render_login_buttons'] );
		add_action( 'register_form', [$this, 'render_login_buttons'] );

		// Optional placement at top
		// add_filter( 'login_form_top', [$this, 'render_login_buttons_filtered'] );

		// Popup redirect after auth
		add_action( 'login_me_now_popup_authenticate_redirection', [$this, 'handle_popup_redirect'] );

		add_filter( 'login_me_now_settings_fields', [$this, 'add_settings_fields'] );
	}

	public function enqueue_login_script() {
		?>
		<script type="text/javascript">
			jQuery(function($) {
				$("#wp-login-login-me-now-buttons").prependTo("#loginform");
			});
		</script>
	<?php }

	public function render_login_buttons() {
		if ( ! $this->is_enabled() ) {
			return;
		}

		$position  = 'after';
		$providers = SettingsRepository::get( 'wp_native_login_providers', [] );

		$repository = new LoginProvidersRepository();
		$repository->get_provider_buttons_html( false, $providers, $position );
	}

	public function render_login_buttons_filtered() {
		$repository = new LoginProvidersRepository();

		return $repository->get_provider_buttons_html( true, SettingsRepository::get( 'wp_native_login_providers', [] ) );
	}

	public function handle_popup_redirect( string $redirect_uri ) {
		$safe_redirect = esc_url_raw( $redirect_uri );
		?>
		<!doctype html>
		<html lang="en">
		<head>
			<meta charset="utf-8">
			<title><?php esc_html_e( 'Authentication successful', 'login-me-now' ); ?></title>
			<script type="text/javascript">
				(function() {
					try {
						let opener = window.opener;
						let sameOrigin = false;

						try {
							const origin = location.protocol + '//' + location.hostname;
							sameOrigin = opener && opener.location.href.startsWith(origin);
						} catch (e) {
							sameOrigin = false;
						}

						const redirect = <?php echo wp_json_encode( $safe_redirect ); ?>;

						if (sameOrigin && opener) {
							if (typeof opener.lmnRedirect === 'function') {
								opener.lmnRedirect(redirect);
							} else {
								opener.location = redirect;
							}
							window.close();
						} else if (opener === null && typeof BroadcastChannel === "function") {
							const channel = new BroadcastChannel('lmn_login_broadcast_channel');
							channel.postMessage({ action: 'redirect', href: redirect });
							channel.close();
							window.close();
						} else {
							location.reload(true);
						}
					} catch (e) {
						location.reload(true);
					}
				})();
			</script>
		</head>
		<body>
			<a href="<?php echo esc_url( $safe_redirect ); ?>"><?php esc_html_e( 'Continue...', 'login-me-now' ); ?></a>
		</body>
		</html>
		<?php exit;
	}

	public function is_enabled(): bool {
		return (bool) SettingsRepository::get( 'wp_native_login_enable', true );
	}

	public function add_settings_fields( $fields ): array {
		$fields[] = [
			'title'         => 'Enable Login Me Now',
			'description'   => 'Use login features for WordPress native login page.',
			'key'           => 'wp_native_login_enable',
			'previous_data' => SettingsRepository::get( 'wp_native_login_enable', true ),
			'type'          => 'switch',
			'tab'           => 'wp-native-login',
		];

		$fields[] = [
			'type' => 'separator',
			'tab'  => 'wp-native-login',
		];

		$fields[] = [
			'title'         => __( 'Select Login Providers', 'login-me-now' ),
			'description'   => __( "Choose what login methods you would like to show.", 'login-me-now' ),
			'key'           => 'wp_native_login_providers',
			'previous_data' => SettingsRepository::get( 'wp_native_login_providers', 'email_magic_link' ),
			'type'          => 'multi-select',
			'options'       => LoginProvidersRepository::get_available_providers_list(),
			'tab'           => 'wp-native-login',
			'if_has'        => ['wp_native_login_enable'],
		];

		$fields[] = [
			'title'         => 'Enter your license key',
			'description'   => "An active license key is needed to unlock all the pro features and receive automatic plugin updates. Don't have a license key? <a href='https://pluginly.com/login-me-now-pro/' target='_blank'>Get it here</a>",
			'key'           => 'lmn_pro_lic',
			'previous_data' => SettingsRepository::get( 'lmn_pro_lic', '' ),
			'type'          => 'text',
			'tab'           => 'license',
		];

		return $fields;
	}

	public function login_enqueue_scripts() {
		wp_register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
		wp_register_script( 'login-me-now-main', login_me_now_url( 'assets/public/main.js' ) );
		wp_register_style( 'login-me-now-main', login_me_now_url( 'assets/public/main.css' ) );
	}
}