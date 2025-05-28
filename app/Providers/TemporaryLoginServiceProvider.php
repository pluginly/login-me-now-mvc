<?php

namespace LoginMeNow\App\Providers;

use LoginMeNow\App\Contracts\LoginProviderBase;
use LoginMeNow\App\DTO\ProviderSettingsFieldsDTO;
use LoginMeNow\App\Helpers\AjaxCheck;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Http\Controllers\TemporaryLoginController;
use LoginMeNow\App\Repositories\TemporaryLoginRepository;

class TemporaryLoginServiceProvider implements LoginProviderBase {

	use AjaxCheck;

	/**
	 * Unique Key of the Login Provider, like: email-magic-link
	 */
	public function boot() {
		( new TemporaryLoginController() )->listen_temporary_link();

		add_action( 'wp_ajax_login_me_now_login_link_generate', [$this, 'login_me_now_login_link_generate'] );
		add_action( 'wp_ajax_login_me_now_login_link_tokens', [$this, 'login_me_now_login_link_tokens'] );
		add_action( 'wp_ajax_login_me_now_login_link_update_status', [$this, 'login_me_now_login_link_update_status'] );
		add_action( 'wp_ajax_login_me_now_login_link_extend_time', [$this, 'login_me_now_login_link_extend_time'] );
		add_action( 'wp_ajax_login_me_now_login_link_drop', [$this, 'login_me_now_login_link_drop'] );
		add_action( 'wp_ajax_login_me_now_login_link_get_link', [$this, 'login_me_now_login_link_get_link'] );
	}

	public static function get_key(): string {
		return 'temporary_login';
	}

	/**
	 * Name of the Login Provider, like: Email Magic Link
	 */
	public static function get_name(): string {
		return 'Tempoarary Login';
	}

	/**
	 * Login Button to be displayed on the login page
	 */
	public static function get_button(): string {
		return '';
	}

	/**
	 * Settings Fields to be displayed on the settings page
	 */
	public function get_settings(): ProviderSettingsFieldsDTO {
		$dto = new ProviderSettingsFieldsDTO();
		$dto->set_fields( [
			[
				'title'       => __( 'Temporary Login', 'login-me-now' ),
				'description' => __( "Generate a tokenized link to create a temporary login. By opening the link, anyone can log in without requiring a password.", 'login-me-now' ),
				'key'         => 'temporary_login',
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

	public function login_me_now_login_link_generate() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$user_id     = (int) get_current_user_id();
		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		if ( ! $expiration ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		$link = ( new TemporaryLoginRepository )->create( $user_id, $expiration );
		if ( ! $link ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $link );
		wp_die();
	}

	public function login_me_now_login_link_tokens() {
		error_log( 'login_me_now_login_link_tokens' );
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$offset = (int) $_POST['offset'] ?? 0;
		$limit  = (int) $_POST['limit'] ?? 10;
		$tokens = ( new TemporaryLoginRepository )->get_all( $offset, $limit );

		if ( ! is_array( $tokens ) || ! $tokens ) {
			wp_send_json_error( __( "Something went wrong", 'login-me-now' ) );
		}

		wp_send_json_success( $tokens );
		wp_die();
	}

	public function login_me_now_login_link_update_status() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		$status   = (string) $_POST['status'] ?? 'pause';

		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = ( new TemporaryLoginRepository )->update_status( $umeta_id, $status );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function login_me_now_login_link_extend_time() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id    = (int) $_POST['umeta_id'] ?? 0;
		$date_string = (string) isset( $_POST['expiration'] ) ? sanitize_text_field( $_POST['expiration'] ) : null;
		$expiration  = Time::convert_timestamp( $date_string );

		if ( ! $umeta_id || ! $expiration ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$updated = ( new TemporaryLoginRepository )->extend_time( $umeta_id, $expiration );

		wp_send_json_success( $updated );
		wp_die();
	}

	public function login_me_now_login_link_drop() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$deleted = ( new TemporaryLoginRepository )->drop( $umeta_id );

		wp_send_json_success( $deleted );
		wp_die();
	}

	public function login_me_now_login_link_get_link() {
		$error = $this->check_permissions( 'login_me_now_generate_token_nonce', 'manage_options' );
		if ( $error ) {
			wp_send_json_error( $error );
		}

		$umeta_id = (int) $_POST['umeta_id'] ?? 0;
		if ( ! $umeta_id ) {
			wp_send_json_error( __( "No meta id provided", 'login-me-now' ) );
		}

		$link = ( new TemporaryLoginRepository )->get_link( $umeta_id );

		wp_send_json_success( $link );
		wp_die();
	}
}