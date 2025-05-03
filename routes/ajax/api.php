<?php

use LoginMeNow\App\Http\Controllers\UserController;
use LoginMeNow\WpMVC\Routing\Ajax;

Ajax::get( 'user/{id}', [UserController::class, 'index'], ['admin'] );
