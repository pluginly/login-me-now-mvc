<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\WpMVC\App;
use LoginMeNow\WpMVC\Contracts\Provider;

class MenuServiceProvider implements Provider {
	public function boot() {
		add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_filter( 'admin_head', [$this, 'add_active_class_to_admin_menu'], 10 );
	}

	public function admin_menu(): void {
		$config = App::$config->get( 'base-config' );
		$icon   = apply_filters( 'menu_icon', 'dashicons-admin-generic' );
		$cap    = $config['menu_cap'];
		$slug   = $config['menu_slug'];

		if ( ! current_user_can( $cap ) ) {
			return;
		}

		add_menu_page(
			__( 'Login Me Now', 'login-me-now' ),
			apply_filters( 'login_me_now_title', 'Login Me Now' ),
			$cap,
			$slug,
			[$this, 'render_admin_dashboard'],
			$icon,
		);

		add_submenu_page(
			$slug,
			__( 'Settings', 'login-me-now' ),
			__( 'Settings', 'login-me-now' ),
			$cap,
			$slug,
			[$this, 'render_admin_dashboard'],
		);

		add_submenu_page(
			$slug,
			__( 'Temporary Login', 'login-me-now' ),
			__( 'Temporary Login', 'login-me-now' ),
			$cap,
			$slug . '&path=temporary-login',
			[$this, 'render_admin_dashboard'],
		);

		add_submenu_page(
			$slug,
			__( 'Browser Extension', 'login-me-now' ),
			__( 'Browser Extension', 'login-me-now' ),
			$cap,
			$slug . '&path=browser-extensions',
			[$this, 'render_admin_dashboard'],
		);
	}

	/**
	 * Renders the admin settings.
	 */
	public function render_admin_dashboard(): void {
		$page_action = '';

		if ( isset( $_GET['action'] ) ) { //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); //phpcs:ignore
			/** @psalm-suppress PossiblyInvalidArgument */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$page_action = str_replace( '_', '-', $page_action );
		}

		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
		include_once login_me_now_dir( 'resources/views/admin/base.php' );
		// include_once LOGIN_ME_NOW_TEMPLATE_PATH . '/admin/base.php';
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
	}
}