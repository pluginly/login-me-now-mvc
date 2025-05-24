<?php

use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\App\LoginProviders\BrowserToken\JWTAuth;
use LoginMeNow\WpMVC\Routing\Route;

include __DIR__ . '/admin-settings.php';



Route::post( 'login_me_now_hide_save_to_browser_extension', [BrowserTokenController::class, 'hide_save_to_browser_extension'], [] );


// Route::post( 'generate', [JWTAuth::class, 'generate_token'], [] );
// Route::post( 'validate', [JWTAuth::class, 'validate_token'], [] );
// Route::post( 'generate-onetime-number', [LoginMeNow\App\LoginProviders\BrowserToken\Controller::class, 'generate_onetime_number'], [] );