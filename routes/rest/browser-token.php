<?php

use LoginMeNow\App\Http\Controllers\BrowserTokenController;

use LoginMeNow\WpMVC\Routing\Route;

Route::post( 'login_me_now_hide_save_to_browser_extension', [BrowserTokenController::class, 'hide_save_to_browser_extension'], [] );
// Route::post( 'generate_token',[BrowserTokenController::class,'login_me_now_browser_token_generate'],[] );



// Route::post( 'generate', [JWTAuthRepository::class, 'generate_token'], [] );
// Route::post( 'validate', [JWTAuthRepository::class, 'validate_token'], [] );
// Route::post( 'generate-onetime-number', [LoginMeNow\App\LoginProviders\BrowserToken\Controller::class, 'generate_onetime_number'], [] );