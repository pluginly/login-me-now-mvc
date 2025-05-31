<?php

defined( 'ABSPATH' ) || exit;

use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\WpMVC\Routing\Ajax;

Ajax::post( 'browser-token/generate', [BrowserTokenController::class, 'ajax_generate_token'] );