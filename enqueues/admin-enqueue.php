<?php

use LoginMeNow\WpMVC\App;
use LoginMeNow\WpMVC\Enqueue\Enqueue;

defined( 'ABSPATH' ) || exit;

$config  = App::$config->get( 'base-config' );
$icon    = apply_filters( 'menu_icon', 'dashicons-admin-generic' );
$pro_url = $config['pro_upgrade_url'];
$slug    = $config['menu_slug'];

Enqueue::script( 'login-me-now-admin-main', 'build/js/dashboard-app', ['react', 'react-dom', 'wp-api-fetch', 'wp-i18n'] );
Enqueue::style( 'login-me-now-admin-main', 'build/js/dashboard-app' );

$localize = [
	'current_user'           => ! empty( wp_get_current_user()->user_firstname ) ? ucfirst( wp_get_current_user()->user_firstname ) : ucfirst( wp_get_current_user()->display_name ),
	'admin_base_url'         => admin_url(),
	'plugin_dir'             => 'https://loginmenow.com',
	'plugin_ver'             => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? LOGIN_ME_NOW_PRO_VERSION : '',
	'version'                => login_me_now_version(),
	'pro_available'          => defined( 'LOGIN_ME_NOW_PRO_VERSION' ) ? true : false,
	'pro_installed_status'   => 'installed' === login_me_now_plugin_status( 'login-me-now-pro/login-me-now-pro.php' ) ? true : false,
	'simple_history_status'  => 'activated' === login_me_now_plugin_status( 'simple-history/index.php' ) ? true : false,
	'product_name'           => __( 'Login Me Now', 'login-me-now' ),
	'plugin_name'            => __( 'Login Me Now PRO', 'login-me-now' ),
	'ajax_url'               => admin_url( 'admin-ajax.php' ),
	'google_redirect_url'    => home_url( 'wp-login.php?lmn-google' ),
	'facebook_redirect_url'  => home_url( 'wp-login.php?lmn-facebook' ),
	'show_self_branding'     => true,
	'admin_url'              => admin_url( 'admin.php' ),
	'home_slug'              => $slug,
	'upgrade_url'            => $pro_url,
	'extension_url'          => 'https://chrome.google.com/webstore/detail/login-me-now/kkkofomlfhbepmpiplggmfpomdnkljoh/?source=wp-dashboard',
	'login_me_now_base_url'  => admin_url( 'admin.php?page=' . $slug ),
	'logo_url'               => login_me_now_url( '/assets/images/icon.svg' ),
	'update_nonce'           => wp_create_nonce( 'login_me_now_update_admin_setting' ),
	'plugin_manager_nonce'   => wp_create_nonce( 'login_me_now_plugin_manager_nonce' ),
	'generate_token_nonce'   => wp_create_nonce( 'login_me_now_generate_token_nonce' ),
	'plugin_installer_nonce' => wp_create_nonce( 'updates' ),
	'free_vs_pro_link'       => admin_url( 'admin.php?page=' . $slug . '&path=free-vs-pro' ),
	'plugin_installed_text'  => __( 'Installed', 'login-me-now' ),
	'plugin_activating_text' => __( 'Activating', 'login-me-now' ),
	'plugin_activated_text'  => __( 'Activated', 'login-me-now' ),
	'plugin_activate_text'   => __( 'Activate', 'login-me-now' ),
	'upgrade_notice'         => true,
	'time_zone'              => wp_timezone_string(),
	'rest_args'              => [
		'root'  => esc_url_raw( rest_url( 'login-me-now' ) ),
		'nonce' => wp_create_nonce( 'wp_rest' ),
	],
];

wp_localize_script( 'login-me-now-admin-main', 'lmn_admin', $localize );