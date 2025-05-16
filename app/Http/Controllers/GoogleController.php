<?php

namespace LoginMeNow\App\Http\Controllers;

use LoginMeNow\App\Repositories\GoogleRepository;

class GoogleController {
	public function listen(): void {
		if ( ! array_key_exists( 'lmn-google', $_GET ) ) {
			return;
		}

		if ( array_key_exists( 'g_csrf_token', $_POST ) ) {
			( new GoogleRepository() )->process_onetap();
		}

		if ( array_key_exists( 'code', $_GET ) ) {
			( new GoogleRepository() )->process_button();
		}
	}
}