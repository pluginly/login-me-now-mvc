<?php

use LoginMeNow\App\LoginProviders\BrowserToken\JWTAuth;
use LoginMeNow\WpMVC\Routing\Route;

include __DIR__ . '/admin-settings.php';

Route::post( 'generate', [JWTAuth::class, 'generate_token'], [] );
Route::post( 'validate', [JWTAuth::class, 'validate_token'], [] );
Route::post( 'generate-onetime-number', [LoginMeNow\App\LoginProviders\BrowserToken\Controller::class, 'generate_onetime_number'], [] );