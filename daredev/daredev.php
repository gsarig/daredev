<?php
/**
 * Plugin configuration.
 *
 * @package DareDev
 */

define( 'DAREDEV_VERSION', '3.0' );
define( 'DD_GOOGLE_MAPS_API_KEY', get_theme_mod( 'dd_gmaps_api_key' ) );

function daredev_settings() {
	$options = [
		'custom_scripts' => false,
		'acf_paths'      => [],
		'acf_hide'       => false,
	];

	if ( has_filter( 'daredev_settings' ) ) {
		$options = apply_filters( 'daredev_settings', $options );
	}

	return $options;
}

function daredev_setting( $setting ) {
	$output   = null;
	$settings = daredev_settings();
	if ( $setting && isset( $settings[ $setting ] ) ) {
		$output = $settings[ $setting ];
	}

	return $output;
}

// Localize it.
load_plugin_textdomain( 'daredev', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Autoload classes.
require_once dirname( __FILE__ ) . '/inc/autoloader.php';
require_once dirname( __FILE__ ) . '/inc/post-types.php';
require_once dirname( __FILE__ ) . '/inc/acf.php';
require_once dirname( __FILE__ ) . '/inc/customizer.php';
require_once dirname( __FILE__ ) . '/inc/inject-scripts.php';
