<?php

defined( 'ABSPATH' ) || exit;

use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\WpMVC\Routing\Ajax;

Ajax::post( 'hide_save_to_browser_extension', [BrowserTokenController::class, 'hide_save_to_browser_extension'] );
Ajax::post( 'browser_token_generate', [BrowserTokenController::class, 'browser_token_generate'] );
Ajax::post( 'browser_tokens', [BrowserTokenController::class, 'browser_tokens'] );
Ajax::post( 'browser_token_update_status', [BrowserTokenController::class, 'browser_token_update_status'] );
Ajax::post( 'browser_token_drop', [BrowserTokenController::class, 'browser_token_drop'] );