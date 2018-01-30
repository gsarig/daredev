<?php
/**
 * ACF Settings
 *
 */

/**
 * ACF Options Page
 * @link https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => __( 'Ρυθμίσεις Site', 'daredev' ),
		'menu_title' => __( 'Ρυθμίσεις Site', 'daredev' ),
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

	acf_update_setting( 'google_api_key', DD_GOOGLE_MAPS_API_KEY );
}

add_action( 'acf/init', 'my_acf_init' );


/**
 * Construct Link HTML from ACF Link field
 *
 * @param $link_obj
 * @param string $class
 *
 * @return string
 */
function acf_link( $link_obj, $class = '' ) {
	$url         = $link_obj['url'];
	$title       = $link_obj['title'];
	$target      = $link_obj['target'];
	$show_target = $target ? ' target="' . $target . '"' : '';
	$show_class  = $class ? ' class="' . $class . '"' : '';

	return ( $url && $title ) ? '<a href="' . $url . '" ' . $show_target . $show_class . '>' . $title . '</a>' : '';
}

// Hide ACF
//add_filter( 'acf/settings/show_admin', '__return_false' );