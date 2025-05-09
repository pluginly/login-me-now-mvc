<?php

defined( 'ABSPATH' ) || exit;

// include_once __DIR__ . '/register.php';

wp_register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
wp_register_script( 'login-me-now-main', login_me_now_url( 'public/main.js' ) );;
wp_register_style( 'login-me-now-main', login_me_now_url( 'public/main.css' ) );;

// Enqueue::register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
// Enqueue::register_script( 'login-me-now-main', 'public/main.js', ['login-me-now-google-api'] );
// Enqueue::register_style( 'login-me-now-main', 'public/main.css' );
