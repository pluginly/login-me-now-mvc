<?php
use LoginMeNow\WpMVC\Enqueue\Enqueue;

defined( 'ABSPATH' ) || exit;

Enqueue::register_script( 'login-me-now-google-api', '//apis.google.com/js/api:client.js' );
Enqueue::register_script( 'login-me-now-main', 'public/main.js', ['login-me-now-google-api'] );
Enqueue::register_style( 'login-me-now-main', 'public/main.css' );