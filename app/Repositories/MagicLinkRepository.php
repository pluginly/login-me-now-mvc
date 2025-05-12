<?php

namespace LoginMeNow\App\Repositories;

use LoginMeNow\Utils\Random;
use LoginMeNow\Utils\Time;
use LoginMeNow\Utils\Translator;

class MagicLinkRepository {

	public string $token_meta_key = 'lmn_magic_link_token';
	public string $token;
	public bool $disposable;
	public int $expiration;

	public function __construct( bool $disposable = true ) {
		$this->disposable = $disposable;
		$this->expiration = (int) SettingsRepository::get( 'email_magic_link_expiration', 300 );
	}
	public static function get_button() {
		if( ! self::is_enable() ) {
			return '';
		}
		include login_me_now_dir( 'resources/views/magiclink/button.php' );
	}
	
	public static function is_enable() {

		return (bool)  SettingsRepository::get('email_magic_link_enable', false );

	}

	/**
	 * Send email the Magic Link
	 *
	 * @param int $user_id
	 * @param string $email
	 *
	 * @return bool
	 */
	public function email_magic_link( int $user_id, string $email ): bool {
		$magic_link = $this->create_magic_link( $user_id );

		if ( ! $magic_link ) {
			return false;
		}

		// Get site title
		$site_title = get_bloginfo( 'name' );

		// Subject of the email
		$subject = sprintf( __( 'Your Magic Link to %s', 'login-me-now' ), $site_title );

		// Convert expiration time from seconds to a human-readable format
		$readable_expiration = $this->expiration < 3600
			? sprintf( _n( '%d minute', '%d minutes', $this->expiration / 60, 'login-me-now' ), $this->expiration / 60 )
			: ( $this->expiration < 86400
				? sprintf( _n( '%d hour', '%d hours', $this->expiration / 3600, 'login-me-now' ), $this->expiration / 3600 )
				: sprintf( _n( '%d day', '%d days', $this->expiration / 86400, 'login-me-now' ), $this->expiration / 86400 ) );

		// Improved email message
		$message = sprintf(
			__(
				"Hi there,<br><br>
				We’re thrilled to have you back at <strong>%s</strong>!<br><br>
				To access your account securely, click the magic link below. For your protection, this link will expire in <strong>%s</strong>:<br><br>
				<div style='text-align: center; margin: 20px 0;'>
					<a href='%s' style='background-color: #0073aa; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;'>Login Me Now</a>
				</div><br>
				If you did not request this email, you can safely ignore it.<br><br>
				Best regards,<br>
				<strong>The %s Team</strong><br><br>
				<hr style='border: 0; border-top: 1px solid #ddd; margin: 20px 0;'>
				<small style='color: #555;'>%s</small>",
				'login-me-now'
			),
			$site_title,
			$readable_expiration,
			$magic_link['link'],
			$site_title,
			$site_title
		);

		// Headers with HTML content type
		$headers = ['Content-Type: text/html; charset=UTF-8'];

		$mail_sent = wp_mail( $email, $subject, $message, $headers );

		if ( ! $mail_sent ) {
			wp_send_json_error( [ 'message' => __( 'There is a critical error. Please contact site administrator.', 'login-me-now' ) ] );
		}

		return $mail_sent;
	}

	/**
	 * Create Magic Link based on User ID and Expiration
	 *
	 * @param int $user_id
	 * @return array|bool
	 */
	public function create_magic_link( int $user_id ) {
		if ( ! function_exists( 'get_userdata' ) ) {
			require_once ABSPATH . WPINC . '/pluggable.php';
		}

		$user = get_userdata( $user_id );
		if ( ! $user ) {
			return false;
		}

		$token = $this->generate_token(
			$user,
			apply_filters( 'login_me_now_magic_link_expire', ( Time::now() + 5 + $this->expiration ) )
		);

		if ( ! $token ) {
			return false;
		}

		$link = sprintf( '%s%s', admin_url( '/?lmn-magic-link=' ), $token );

		return [
			'link'    => $link,
			'message' => __( 'Magic link generated successfully!', 'login-me-now' ),
		];
	}

	/**
	 * Verify the user token and return user ID or 0
	 *
	 * @return int
	 */
	public function verify_token( string $token ): int {
		if ( ! $token ) {
			return false;
		}

		$data    = Translator::decode( $token );
		$user_id = (int) $data[0] ?? 0;
		$key     = (string) $data[1] ?? '';
		$expire  = (int) $data[2] ?? 0;

		if ( ! $user_id || ! $key || ! $expire ) {
			return false;
		}

		if ( ! self::is_valid_token( $user_id, $key, $expire ) ) {
			return false;
		}

		return (int) $user_id;
	}

	/**
	 * Validate the token and return true or false
	 *
	 * @param int $user_id
	 * @param string $key
	 * @param int $expire
	 * @return bool
	 */
	private function is_valid_token( int $user_id, string $key, int $expire ): bool {
		$user_meta = get_user_meta( $user_id, $this->token_meta_key, false );

		/**
		 * Early exit, If no meta found
		 */
		if ( ! $user_meta ) {
			return false;
		}

		/**
		 * Check whether the user has the valid token in usermeta or not
		 */
		foreach ( $user_meta as $token ) {
			$_key    = (string) $token['key'] ?? '';
			$_expire = (int) $token['expire'] ?? 0;

			if ( $this->disposable ) {
				delete_user_meta( $user_id, $this->token_meta_key );
			}

			if (
				$_key === $key
				&& $_expire === $expire
				&& ! Time::expired( $token['expire'] )
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Generate token based on User & Expiration
	 *
	 * @param \WP_User $user
	 * @param int $secs
	 * @return string
	 */
	private function generate_token( \WP_User $user, int $secs ): string {
		$issued_at = Time::now();
		$expire    = apply_filters( 'login_me_now_magic_link_expire', $secs, $issued_at );

		$key     = Random::key();
		$user_id = $user->data->ID;
		$token   = Translator::encode( $user_id, $key, $expire, '==' );

		add_user_meta(
			$user_id,
			$this->token_meta_key,
			[
				'key'        => $key,
				'created_at' => $issued_at,
				'expire'     => $expire,
			]
		);

		\LoginMeNow\Integrations\SimpleHistory\Logs::add( $user_id, "generated an email magic link" );

		return $token;
	}
}