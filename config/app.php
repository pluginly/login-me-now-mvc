<?php

use LoginMeNow\App\Http\Middleware\EnsureIsUserAdmin;

defined( 'ABSPATH' ) || exit;

use LoginMeNow\App\Providers\IntegrationServiceProvider;
use LoginMeNow\App\Providers\LoginFormServiceProvider;
use LoginMeNow\App\Providers\LoginServiceProvider;
use LoginMeNow\App\Providers\MenuServiceProvider;
// use LoginMeNow\Database\Migrations\TestMigration;
use LoginMeNow\WpMVC\Helpers\Helpers;

return [
	/**
	 * The version of the plugin.
	 */
	'version'                     => Helpers::get_plugin_version( 'login-me-now' ),

	/**
	 * Configuration for the REST API.
	 */
	'rest_api'                    => [
		/**
		 * The namespace for the REST API.
		 */
		'namespace' => 'login-me-now',

		/**
		 * The versions of the REST API.
		 */
		'versions'  => [],
	],

	/**
	 * Configuration for the AJAX API.
	 */
	'ajax_api'                    => [
		/**
		 * The namespace for the AJAX API.
		 */
		'namespace' => 'login-me-now',

		/**
		 * The versions of the AJAX API.
		 */
		'versions'  => [],
	],

	/**
	 * Service providers for the plugin.
	 */
	'providers'                   => [
		LoginFormServiceProvider::class,
		IntegrationServiceProvider::class,
		LoginServiceProvider::class,
	],

	/**
	 * Service providers for the admin area of the plugin.
	 */
	'admin_providers'             => [
		MenuServiceProvider::class,
	],

	/**
	 * Middleware configuration for the plugin.
	 */
	'middleware'                  => [
		/**
		 * Middleware for admin routes.
		 */
		'admin' => EnsureIsUserAdmin::class,
	],

	/**
	 * The database option key for storing migration information.
	 */
	'migration_db_option_key'     => 'login_me_now_migrations',

	/**
	 * List of migrations for the plugin.
	 */
	'migrations'                  => [
		// 'test-migration' => TestMigration::class,
	],

	/**
	 * This configuration option defines a hook that will fire before executing the route callback,
	 * such as before a controller action. It provides two parameters:
	 *
	 * @param WP_REST_Request $wp_rest_request The current REST request object.
	 * @param string $full_route The full route being accessed.
	 */
	'rest_response_action_hook'   => 'login-me-now_rest_response_action',

	/**
	 * Configuration for the REST API response filter hook.
	 *
	 * This filter hook allows overriding the entire REST API response.
	 *
	 * @param $response The response object from the controller.
	 * @param WP_REST_Request  $wp_rest_request The request object.
	 * @param string           $full_route The full route of the request.
	 */
	'rest_response_filter_hook'   => 'login-me-now_rest_response_filter',

	/**
	 * This filter hook that can override all REST API permissions.
	 *
	 * @param mixed $permission The current permission setting.
	 * @param mixed $middleware The middleware being applied.
	 * @param string $full_route The full route of the API endpoint.
	 */
	'rest_permission_filter_hook' => 'login-me-now_rest_permission_filter',
];