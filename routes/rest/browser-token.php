<?php

use LoginMeNow\App\Http\Controllers\BrowserTokenController;
use LoginMeNow\WpMVC\Routing\Route;

Route::post( 'generate', [BrowserTokenController::class, 'generate_token'], [] );
Route::post( 'validate', [BrowserTokenController::class, 'validate_token'], [] );
Route::post( 'generate-onetime-number', [BrowserTokenController::class, 'generate_link'], [] );