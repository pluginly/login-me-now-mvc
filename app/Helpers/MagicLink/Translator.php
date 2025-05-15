<?php

namespace LoginMeNow\App\Helpers\MagicLink;

class Translator {
	public static function encode( string...$items ): string{
		$single = "";
		foreach ( $items as $item ) {
			$single .= $item . " ";
		}

		return trim( base64_encode( $single ) );
	}

	public static function decode( string $data ): array{
		$decodedData = base64_decode( $data, true );
		$items       = explode( " ", $decodedData );

		return $items;
	}
}