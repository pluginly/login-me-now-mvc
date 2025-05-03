<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Repositories\LoginProvidersRepository;
use LoginMeNow\Repositories\LoginProviderRepository;
use LoginMeNow\Repositories\SettingsRepository;
use LoginMeNow\WpMVC\Contracts\Provider;

class LoginFormServiceProvider implements Provider {

	public function boot() {
		// Load scripts and styles
		add_action( 'login_footer', [$this, 'enqueue_login_script'], 50 );

		// Display login buttons on login/register forms
		add_action( 'login_form', [$this, 'render_login_buttons'] );
		add_action( 'register_form', [$this, 'render_login_buttons'] );

		// Optional placement at top
		// add_filter( 'login_form_top', [$this, 'render_login_buttons_filtered'] );

		// Popup redirect after auth
		add_action( 'login_me_now_popup_authenticate_redirection', [$this, 'handle_popup_redirect'] );
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
}