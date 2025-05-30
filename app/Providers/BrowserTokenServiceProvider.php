<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Http\Controllers\BrowserTokenController;

class BrowserTokenServiceProvider implements LoginProviderBase {

	public function boot() {

		( new BrowserTokenController )->listen_link();

		if ( is_admin() ) {
			add_action( 'admin_footer', [$this, 'lmn_save_popup'] );
		}
	}

	public static function get_key(): string {
		return 'browser_extension';
	}

	public static function get_name(): string {
		return 'Browser Extension';
	}

	public static function get_button(): string {
		return '';
	}

	public function errors() {
		return [
			'permission' => __( 'Sorry, you are not allowed to do this operation.', 'login-me-now' ),
			'nonce'      => __( 'Nonce validation failed', 'login-me-now' ),
			'default'    => __( 'Sorry, something went wrong.', 'login-me-now' ),
			'invalid'    => __( 'No post data found!', 'login-me-now' ),
		];
	}

	public function hide_save_to_browser_extension() {
		wp_send_json_success(
			update_user_meta(
				get_current_user_id(),
				'login_me_now_hide_save_to_browser_extension',
				true
			)
		);
	}

	public function get_error_msg( string $type ) {
		if ( ! isset( $this->errors()[$type] ) ) {
			$type = 'default';
		}

		return $this->errors()[$type];
	}

	/**
	 * Settings Fields to be displayed on the settings page
	 */
	public function get_settings(): ProviderSettingsFieldsDTO {
		$dto = new ProviderSettingsFieldsDTO();
		$dto->set_fields( [
			[
				'title'       => __( 'Enable Browser Extension', 'login-me-now' ),
				'description' => __( "If frequent logins to the dashboard are necessary throughout the day, the browser extension comes in handy.It just takes 1 click to login to dashboard.", 'login-me-now' ),
				'key'         => 'browser_extension',
				'default'     => true,
				'type'        => 'switch',
				'tab'         => 'delegate-access',
			],
			[
				'type' => 'separator',
				'tab'  => 'delegate-access',
			],
		] );

		return $dto;
	}

	public function lmn_save_popup() {
		include_once login_me_now_dir( 'resources/views/browser-token/extension-popup.php' );
	}
}