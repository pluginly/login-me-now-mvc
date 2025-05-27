<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\App\Helpers\Random;
use LoginMeNow\App\Helpers\Time;
use LoginMeNow\App\Helpers\Translator;
use LoginMeNow\App\Helpers\User;

class TemporaryLoginRepository {

	public $token_key = 'lmn_token';

	public function get_link( int $umeta_id ): string {
		$result = $this->get( $umeta_id );

		if ( ! $result ) {
			return __( 'No token found', 'login-me-now' );
		}

		$meta_value = $result[0]->meta_value ?? null;
		if ( ! $meta_value ) {
			return __( 'No token found', 'login-me-now' );
		}

		$meta_value = maybe_unserialize( $meta_value );

		$user_id = $meta_value['created_by'] ?? 0;
		$number  = $meta_value['number'] ?? 0;
		$expire  = $meta_value['expire'] ?? 0;

		$token = Translator::encode( $user_id, $number, $expire, '==' );

		//\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, "generated a temporary login link" );

		return sprintf( '%s%s', admin_url( '/?lmn-token=' ), $token );
	}

	public function create( int $user_id, int $expiration ) {
		if ( ! function_exists( 'get_userdata' ) ) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		$token = $this->generate_token( $user, $expiration );
		if ( ! $token ) {
			return false;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn-token=' ), $token );

		return [
			'link'    => $link,
			'message' => __( 'Login link generated successfully!', 'login-me-now' ),
		];
	}

	protected function generate_token( \WP_User $user, int $secs ): string {
		$issued_at = Time::now();
		$expire    = apply_filters( 'login_me_now_login_link_expire', $secs, $issued_at );

		$number = Random::number();
		$token  = Translator::encode( $user->data->ID, $number, $expire, '==' );

		$this->insert(
			$user->data->ID,
			[
				'number'     => $number,
				'created_at' => $issued_at,
				'created_by' => get_current_user_id(),
				'expire'     => $expire,
			]
		);

		//\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user->data->ID, "generated a temporary login link" );

		return $token;
	}

	public function insert( int $user_id, array $data ): bool {
		return add_user_meta( $user_id, $this->token_key, $data );
	}

	public function update( int $meta_id, array $value ): bool {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$value['last_updated'] = Time::now();
		$value['updated_by']   = get_current_user_id();

		$updated = $wpdb->update(
			$table,
			['meta_value' => serialize( $value )],
			['umeta_id' => $meta_id]
		);

		return $updated;
	}

	public function get( int $meta_id ) {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_value FROM $table WHERE umeta_id  = %s",
				$meta_id
			)
		);

		return $results;
	}

	public function drop( int $meta_id ): bool {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$deleted = $wpdb->delete(
			$table,
			['umeta_id' => $meta_id]
		);

		return $deleted;
	}

	public function update_status( int $meta_id, string $status ): bool {
		if ( ! $meta_id || ! $status ) {
			return false;
		}

		$meta_value = $this->get( $meta_id )[0]->meta_value ?? null;

		if ( ! $meta_value ) {
			return false;
		}

		$status               = 'pause' === $status ? 'pause' : 'active';
		$meta_value           = maybe_unserialize( $meta_value );
		$meta_value['status'] = $status;

		return $this->update( $meta_id, $meta_value );
	}

	public function extend_time( int $meta_id, int $timestamp ): bool {
		if ( ! $meta_id || ! $timestamp ) {
			return false;
		}

		$meta_value = $this->get( $meta_id )[0]->meta_value ?? null;

		if ( ! $meta_value ) {
			return false;
		}

		$meta_value           = maybe_unserialize( $meta_value );
		$meta_value['expire'] = $timestamp;

		return $this->update( $meta_id, $meta_value );
	}

	public function get_all( int $offset = 0, int $limit = 10 ): array {
		global $wpdb;

		$table = _get_meta_table( 'user' );

		$results = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM $table WHERE meta_key = %s LIMIT %d OFFSET %d",
				$this->token_key,
				$limit,
				$offset
			)
		);

		foreach ( $results as $key => $meta ) {
			$results[$key]->meta_value   = unserialize( $results[$key]->meta_value );
			$results[$key]->display_name = User::display_name( $meta->user_id );
			$results[$key]->user_login   = User::login( $meta->user_id );
		}

		return $results;
	}

	public function is_valid( int $user_id, int $number, int $expire ): bool {
		$user_meta = get_user_meta( $user_id, $this->token_key, false );

		if ( ! is_array( $user_meta ) ) {
			return false;
		}

		foreach ( $user_meta as $token ) {
			$_number = (int) $token['number'] ?? 0;
			$_expire = (int) $token['expire'] ?? 0;
			$status  = $token['status'] ?? '';

			if (
				$_number === $number
				&& $_expire === $expire
				&& 'pause' !== $status
				&& ! Time::expired( $token['expire'] )
			) {
				return true;
			}
		}

		return false;
	}

	public function verify( string $token ): int {
		if ( ! $token ) {
			return false;
		}

		$data = Translator::decode( $token );
		if ( empty( $data ) ) {
			return false;
		}

		error_log( '$data : ' . print_r( $data, true ) );
		$user_id = (int) isset( $data[0] ) ? $data[0] : 0;
		$number  = (int) isset( $data[1] ) ? $data[1] : 0;
		$expire  = (int) isset( $data[2] ) ? $data[2] : 0;

		if ( ! $user_id || ! $number || ! $expire ) {
			return false;
		}

		if ( ! self::is_valid( $user_id, $number, $expire ) ) {
			return false;
		}

		return (int) $user_id;
	}
}