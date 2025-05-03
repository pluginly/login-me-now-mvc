<?php

namespace LoginMeNow\App\Contracts;

interface IntegrationProviderBase {

	/**
	 * Unique Key of the Integration Provider, like: woocommerce-integration
	 */
	public static function get_key(): string;

	/**
	 * Name of the Login Provider, like: WooCommerce Integration
	 */
	public static function get_name(): string;

	/**
	 * Settings Fields to be displayed on the settings page
	 */
	public function get_settings(): IntegrationProviderBase;
}
