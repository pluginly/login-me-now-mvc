<?php

defined( 'ABSPATH' ) || exit;

use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\WpMVC\Routing\Ajax;

Ajax::get( 'login_me_now_browser_token_generate', [BrowserTokenController::class, 'save_token'] );
