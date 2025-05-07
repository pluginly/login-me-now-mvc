<?php

use LoginMeNow\App\Http\Controllers\SettingsController;
use LoginMeNow\App\Http\MagicLinkController\MagicLinkController;
use LoginMeNow\App\Http\Controllers\Controller;
use LoginMeNow\WpMVC\Routing\Route;

Route::get( 'admin/settings', [SettingsController::class, 'index'], ['admin'] );
Route::post( 'admin/settings/fields', [SettingsController::class, 'get_fields'], ['admin'] );
Route::post( 'admin/settings/save', [LoginMeNow\App\http\Controllers\Controller::class, 'save'], ['admin'] );
Route::post( '/send-magic-link', [MagicLinkController::class, 'send_magic_link'] );