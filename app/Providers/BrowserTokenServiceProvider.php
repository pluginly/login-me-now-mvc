<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\App\Models\BrowserTokenModel;
use LoginMeNow\App\Repositories\JWTAuthRepository;

class BrowserTokenServiceProvider implements LoginProviderBase {

	public function boot() {

		( new BrowserTokenController )->listen_link();

		if ( is_admin() ) {
			add_action( 'admin_footer', [$this, 'lmn_save_popup'] );
		}

		add_action( 'wp_ajax_login_me_now_hide_save_to_browser_extension', [$this, 'hide_save_to_browser_extension'] );
		
		add_action( 'wp_ajax_login_me_now_browser_token_generate', [$this, 'browser_token_generate'] );
		add_action( 'wp_ajax_login_me_now_browser_tokens', [$this, 'browser_tokens'] );
		add_action( 'wp_ajax_login_me_now_browser_token_update_status', [$this, 'browser_token_update_status'] );
		add_action( 'wp_ajax_login_me_now_browser_token_drop', [$this, 'browser_token_drop'] );
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

	public function browser_token_generate() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$user_id = get_current_user_id();
		$user    = get_userdata( $user_id );

		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		$additional_data = false;
		if ( ! empty( $_POST['additional_data'] ) ) {
			$additional_data = true;
		}
		$token = ( new JWTAuthRepository )->new_token( $user, $expiration, $additional_data );
		if ( ! $token ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success(
			[
				'success' => true,
				'message' => __( 'Successfully Generated', 'login-me-now' ),
				'link'    => $token,
			]
		);

		wp_die();
	}

	public function browser_tokens() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$offset = (int) $_POST['offset'] ?? 0;
		$limit  = (int) $_POST['limit'] ?? 10;
		$tokens = ( new BrowserTokenModel )->get_all( $offset, $limit );

		if ( ! is_array( $tokens ) || ! $tokens ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $tokens );
		wp_die();
	}

	public function browser_token_update_status() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		$status   = (string) $_POST['status'] ?? 'pause';

		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = ( new BrowserTokenModel )->update( $token_id, $status );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function browser_token_drop() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$token_id = (int) $_POST['token_id'] ?? 0;
		if ( ! $token_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$deleted = ( new BrowserTokenModel )->drop( $token_id );

		wp_send_json_success( $deleted );
		wp_die();
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

	private function check_permissions( string $ref, string $cap = null ) {
		$response_data = ['message' => $this->get_error_msg( 'permission' )];

		if ( $cap && ! current_user_can( $cap ) ) {
			return $response_data;
		}

		if ( empty( $_POST ) ) {
			$response_data = ['message' => $this->get_error_msg( 'invalid' )];

			return $response_data;
		}

		/**
		 * Nonce verification.
		 */
		if ( ! check_ajax_referer( $ref, 'security', false ) ) {
			$response_data = ['message' => $this->get_error_msg( 'nonce' )];

			return $response_data;
		}

		return false;
	}
}