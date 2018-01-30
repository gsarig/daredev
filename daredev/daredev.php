<?php

define( 'DAREDEV_VERSION', '2.0' );
define( 'DD_GOOGLE_MAPS_API_KEY', 'AIzaSyBJ5fUsd6a6pRHFNgNeA0Xue_Qny8HUZyM' );

// Localize it
load_plugin_textdomain( 'daredev', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Autoload classes
include_once dirname( __FILE__ ) . '/inc/autoloader.php';
include_once dirname( __FILE__ ) . '/inc/post-types.php';
include_once dirname( __FILE__ ) . '/inc/acf.php';