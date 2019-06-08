<?php
/**
 * Plugin configuration.
 *
 * @package DareDev
 */

define( 'DAREDEV_VERSION', '3.0' );
define( 'DD_GOOGLE_MAPS_API_KEY', get_theme_mod( 'dd_gmaps_api_key' ) );

// Define additional MU-Plugin paths to save ACF JSON fields.
define(
	'DD_ACF_JSON_PATHS',
	[
		'gutenblocks',
	]
);

// Localize it.
load_plugin_textdomain( 'daredev', false, basename( dirname( __FILE__ ) ) . '/languages' );

// Autoload classes.
require_once dirname( __FILE__ ) . '/inc/autoloader.php';
require_once dirname( __FILE__ ) . '/inc/post-types.php';
require_once dirname( __FILE__ ) . '/inc/acf.php';
require_once dirname( __FILE__ ) . '/inc/customizer.php';
require_once dirname( __FILE__ ) . '/inc/inject-scripts.php';
