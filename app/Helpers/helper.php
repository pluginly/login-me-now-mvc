<?php

defined( 'ABSPATH' ) || exit;

use LoginMeNow\WpMVC\App;
use LoginMeNow\DI\Container;

function login_me_now():App {
    return App::$instance;
}

function login_me_now_config( string $config_key ) {
    return login_me_now()::$config->get( $config_key );
}

function login_me_now_app_config( string $config_key ) {
    return login_me_now_config( "app.{$config_key}" );
}

function login_me_now_version() {
    return login_me_now_app_config( 'version' );
}

function login_me_now_container():Container {
    return login_me_now()::$container;
}

function login_me_now_singleton( string $class ) {
    return login_me_now_container()->get( $class );
}

function login_me_now_url( string $url = '' ) {
    return login_me_now()->get_url( $url );
}

function login_me_now_dir( string $dir = '' ) {
    return login_me_now()->get_dir( $dir );
}

function login_me_now_render( string $content ) {
    //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $content;
}