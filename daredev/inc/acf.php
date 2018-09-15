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
				'redirect'   => false
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
 * Construct Link HTML from ACF Link field
 *
 * @param        $link_obj
 * @param string $class
 *
 * @return string
 */
function acf_link( $link_obj, $class = '' ) {
	$url         = $link_obj ? $link_obj['url'] : '';
	$title       = $link_obj ? $link_obj['title'] : '';
	$target      = $link_obj ? $link_obj['target'] : '';
	$show_target = $target ? ' target="' . $target . '"' : '';
	$show_class  = $class ? ' class="' . $class . '"' : '';
	$protocol    = ( ! preg_match( '~^(?:f|ht)tps?://~i', $url ) ) ? 'http://' : '';

	return ( $url && $title ) ? '<a href="' . $protocol . $url . '" ' . $show_target . $show_class . '>' . $title .
	                            '</a>' : '';
}

/**
 * Take a textarea field and give each line a different markup
 *
 * @param       $data
 * @param array $elements
 *
 * @return string
 */
function acf_textarea( $data, $elements = [] ) {
	$output = '';
	$text   = explode( PHP_EOL, $data );
	if ( $text ) {
		$count = count( $elements );
		foreach ( $text as $key => $value ) {
			// If element tags are less than the text lines, format the remaining lines with the last element tag
			$element = isset( $elements[ $key ] ) ? $elements[ $key ] : $elements[ $count - 1 ];
			// If an element tag is blank, return the value
			$markup = $element ? str_replace( '><', '>' . $value . '<', $element ) : $value;
			$output .= $value ? $markup : '';
		}
	}

	return $output;
}

/**
 * Get event date
 *
 * @param        $from
 * @param string $to
 * @param string $separator
 * @param string $markup
 *
 * @return mixed|string
 */
function acf_date_range( $from, $to = '', $separator = '-', $markup = '' ) {
	$get_from       = $from ? DateTime::createFromFormat( 'd/m/Y g:i a', $from ) : '';
	$get_to         = $to ? DateTime::createFromFormat( 'd/m/Y g:i a', $to ) : '';
	$timestamp_from = $from ? $get_from->getTimestamp() : '';
	$timestamp_to   = $to ? $get_to->getTimestamp() : '';
	$fd             = date( 'j', $timestamp_from );
	$fm             = date( 'F', $timestamp_from );
	$fy             = date( 'Y', $timestamp_from );
	$td             = $to ? date( 'j', $timestamp_to ) : '';
	$tm             = $to ? date( 'F', $timestamp_to ) : '';
	$ty             = $to ? date( 'Y', $timestamp_to ) : '';

	$m1  = isset( $tm ) && ( $fm === $tm ) ? '' : $fm . ' ';
	$y1  = isset( $ty ) && ( $fy === $ty ) ? '' : $fy . ' ';
	$d2  = isset( $td ) && ( $fd === $td && $fm === $tm && $fy === $ty ) ? '' : $td . ' ';
	$m2  = isset( $tm ) && ( $fm !== $tm ) ? $tm : $fm;
	$y2  = isset( $ty ) && ( $fy !== $ty ) ? $ty : $fy . ' ';
	$sep = ( $d2 || ( $fm !== $tm ) || ( $fy !== $ty ) ) ? $separator : '';

	$output = $fd . ' ' . $m1 . $y1 . $sep . $d2 . $m2 . ' ' . $y2;

	return $markup ? str_replace( '><', '>' . $output . '<', $markup ) : $output;
}

/**
 * Link Wrapper
 * If URL exists, returns a link, else returns only the anchor
 *
 * @param        $url
 * @param        $anchor
 * @param string $extras
 *
 * @return string
 */
function acf_link_wrapper( $url, $anchor, $extras = '' ) {
	return $url ? '<a href="' . esc_url( $url ) . '" ' . $extras . '>' . $anchor . '</a>' : $anchor;
}

/**
 * Remove the final s if the number is one.
 *
 * @param        $text
 * @param        $number
 * @param string $lang
 *
 * @return string
 */
function acf_pluralize( $text, $number, $lang = 'en' ) {
	$number = intval( $number );

	return ( $number === 1 && $lang === 'en' ) ? rtrim( $text, 's' ) : $text;
}

// Hide ACF
if ( WP_DEBUG !== true ) {
	add_filter( 'acf/settings/show_admin', '__return_false' );
}
