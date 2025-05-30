<?php

namespace LoginMeNow\App\Helpers;

class Key {
	public static function get( string $key ): string {
		return sprintf( '%s%s', self::prefix(), trim( $key ) );
	}

	public static function prefix(): string {
		return sprintf( '%s%s%s', '-', 'login_me_now', '-' );
	}
}