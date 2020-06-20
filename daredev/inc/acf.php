<?php
/**
 * ACF Settings
 */

/**
 * ACF Options Page
 *
 * @link https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

function acf_add_options_page_init() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			[
				'page_title' => __( 'Site Settings', 'daredev' ),
				'menu_title' => __( 'Site Settings', 'daredev' ),
				'menu_slug'  => 'site-options',
				'capability' => 'edit_posts',
				'redirect'   => false,
			]
		);
	}
}

add_action( 'init', 'acf_add_options_page_init' );
/**
 * ACF - Register Google Maps API
 * Get your API Key here: https://developers.google.com/maps/documentation/javascript/get-api-key
 */
function my_acf_init() {

	acf_update_setting( 'google_api_key', DD_GOOGLE_MAPS_API_KEY );
}

add_action( 'acf/init', 'my_acf_init' );


/**
 * Handle acf-json inside the mu-plugin
 */
add_filter( 'acf/settings/save_json', 'dd_acf_json_save_point' );
add_filter( 'acf/settings/load_json', 'dd_acf_json_load_point' );

function dd_acf_json_save_point( $path ) {
	$path = WPMU_PLUGIN_DIR . '/daredev/acf-json';

	return $path;
}

function dd_acf_json_load_point( $paths ) {
	$additional_paths = daredev_setting( 'acf_json_paths' );
	unset( $paths[0] );
	$paths[] = WPMU_PLUGIN_DIR . '/daredev/acf-json';
	if ( $additional_paths ) {
		foreach ( $additional_paths as $path ) {
			$paths[] = $path;
		}
	}

	return $paths;
}


// Hide ACF when debug is set to false.
function dd_hide_acf() {
	$output = 'true';
	if ( daredev_setting( 'acf_hide' ) ) {
		$output = 'false';
	}
	if ( WP_DEBUG !== true ) {
		add_filter( 'acf/settings/show_admin', '__return_' . $output );
	}
}

add_action( 'init', 'dd_hide_acf' );

// ACF Lite
// require_once dirname( __FILE__ ) . '/acf-lite.php';
