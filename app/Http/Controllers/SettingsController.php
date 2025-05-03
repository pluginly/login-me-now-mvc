<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\Repositories\SettingsRepository;
use LoginMeNow\WpMVC\Routing\Response;

class SettingsController extends Controller {
	public function index() {
		return Response::send(
			[
				'settings' => ( new SettingsRepository() )->all(),
			]
		);
	}

	public function get_fields() {
		return Response::send(
			[
				'fields' => ( new SettingsRepository() )->get_fields(),
			]
		);
	}

	public function save( \WP_REST_Request $request ) {

		$params = $request->get_params();

		try {
			if ( ! is_array( $params ) ) {
				throw new \Exception( __( 'Invalid Params', 'login-me-now' ) );
			}

			foreach ( $params as $key => $value ) {
				SettingsRepository::save( $key, $value );
			}

			wp_send_json_success( [
				'message' => __( 'Successfully Settings Saved', 'login-me-now' ),
				'params'  => $params,
			] );

		} catch ( \Throwable $th ) {
			wp_send_json_error( [
				'message' => __( 'Settings Update Failed', 'login-me-now' ),
				'error'   => $th->getMessage(),
				'params'  => $params,
			] );
		}
	}
}