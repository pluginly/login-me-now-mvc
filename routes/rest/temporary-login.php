<?php

use LoginMeNow\App\Http\Controllers\TemporaryLoginController;
use LoginMeNow\WpMVC\Routing\Route;

Route::group( 'temporary-login', function () {
	Route::post( 'generate', [TemporaryLoginController::class, 'admin_generate_token'], [] );
	Route::post( 'tokens', [TemporaryLoginController::class, 'admin_tokens'], [] );
	Route::post( 'get-link', [TemporaryLoginController::class, 'admin_get_link'], [] );
	Route::post( 'drop', [TemporaryLoginController::class, 'admin_drop_link'], [] );
	Route::post( 'extend-time', [TemporaryLoginController::class, 'admin_extend_time'], [] );
	Route::post( 'update-status', [TemporaryLoginController::class, 'admin_update_status'], [] );
}, ['admin'] );