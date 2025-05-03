<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\WpMVC\App;
use LoginMeNow\WpMVC\Contracts\Provider;

class MenuServiceProvider implements Provider {
	public function boot() {
		add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_filter( 'admin_footer_text', [$this, 'admin_footer_link'], 99 );
		add_action( 'admin_head', [$this, 'admin_submenu_css'] );
		// add_filter( 'admin_head', [$this, 'add_active_class_to_admin_menu'], 10 );
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

	public function admin_footer_link() {
		if ( isset( $_GET['page'] ) && 'login-me-now' === $_GET['page'] ) {
			return '<span id="footer-thankyou"> Thank you for using <span class="focus:text-astra-hover active:text-astra-hover hover:text-lmn-hover"> ' . esc_attr( __( 'Login Me Now', 'login-me-now' ) ) . '.</span></span>';
		}
	}

	/**
	 * Add custom CSS for admin area sub menu icons.
	 */
	public function admin_submenu_css(): void {
		echo '<style class="astra-menu-appearance-style">
				.toplevel_page_login-me-now > div.wp-menu-image:before {
					line-height: 27px !important;
					content: "";
					background: url("' . login_me_now_url( '/assets/images/sidebar.svg' ) . '") no-repeat center center;
					speak: none !important;
					font-style: normal !important;
					font-weight: normal !important;
					font-variant: normal !important;
					text-transform: none !important;
					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased !important;
					-moz-osx-font-smoothing: grayscale !important;
					box-sizing: content-box;
				}
			</style>';
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
		/** @psalm-suppress MissingFile */// phpcs:ignore Generic.Commenting.DocComment.MissingShort
	}

	public function add_active_class_to_admin_menu() {
		$screen     = get_current_screen();
		$active_nth = '';
		$style      = '';

		if ( 'toplevel_page_login-me-now' === $screen->id ) {
			$nth = [
				'temporary-login'    => 3,
				'browser-extensions' => 4,
				'social-login'       => 5,
			];

			$path       = $_GET['path'] ?? '';
			$active_nth = $nth[$path] ?? '';
		}

		if ( $active_nth ) {
			$style = '#toplevel_page_login-me-now .wp-submenu li:nth-child(' . $active_nth . ') a {
					color: #fff;
					font-weight: 600;
				}';

			$style .= '#toplevel_page_login-me-now .wp-submenu li:nth-child(2) a {
					color: rgba(240,246,252,.7) !important;
					font-weight: normal;

				}';
		}
		if ( ! defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ) {
			echo '<style>
				#toplevel_page_login-me-now .wp-submenu li:nth-child(6) a {
					font-weight: 600;
					background-color: #93003f;
					color: #fff;
					margin: 3px 10px 0;
					display: block;
					text-align: center;
					border-radius: 3px;
					transition: all .3s;
				}

				#toplevel_page_login-me-now .wp-submenu li:nth-child(6) a:hover {
					background-color: #c60055;
    				box-shadow: none;
				}

				' . $style . '
			</style>';
		}
	}
}