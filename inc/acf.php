<?php
/**
 * ACF Settings
 *
 * @package Madena_Apartments
 */

/**
 * ACF Options Page
 * @link https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => __( 'Ρυθμίσεις Site', 'madena' ),
		'menu_title' => __( 'Ρυθμίσεις Site', 'madena' ),
		'menu_slug'  => 'site-options',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
}

/**
 * ACF - Register Google Maps API
 * Get your API Key here: https://developers.google.com/maps/documentation/javascript/get-api-key
 */
function my_acf_init() {

	acf_update_setting( 'google_api_key', 'AIzaSyBJ5fUsd6a6pRHFNgNeA0Xue_Qny8HUZyM' );
}

add_action( 'acf/init', 'my_acf_init' );