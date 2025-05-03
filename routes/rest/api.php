<?php

use LoginMeNow\App\Http\Controllers\UserController;
use LoginMeNow\WpMVC\Routing\Route;

Route::get( 'user', [UserController::class, 'index'], ['admin'] );