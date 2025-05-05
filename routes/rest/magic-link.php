<?php

use LoginMeNow\App\Http\MagicLinkController\MagicLinkController;
use LoginMeNow\WpMVC\Routing\Route;

include __DIR__ . '/admin-settings.php';

Route::post( '/send-magic-link', [MagicLinkController::class, 'send_magic_link'] );