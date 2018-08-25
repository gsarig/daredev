<?php

define( 'DAREDEV_VERSION', '3.0' );
define( 'DD_GOOGLE_MAPS_API_KEY', get_theme_mod( 'dd_gmaps_api_key' ) );

// Localize it
load_plugin_textdomain( 'daredev', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Autoload classes
include_once dirname( __FILE__ ) . '/inc/autoloader.php';
include_once dirname( __FILE__ ) . '/inc/post-types.php';
include_once dirname( __FILE__ ) . '/inc/acf.php';
include_once dirname( __FILE__ ) . '/inc/customizer.php';
include_once dirname( __FILE__ ) . '/inc/inject-scripts.php';