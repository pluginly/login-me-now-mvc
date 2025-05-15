<?php

use LoginMeNow\App\Http\Controllers\MagicLinkController;
use LoginMeNow\WpMVC\Routing\Route;



Route::post( '/send-magic-link', [MagicLinkController::class, 'send_magic_link'] );