<?php
/**
 * Inject 3rd party scripts on Header, body and footer
 */

function dd_body() {
	do_action( 'dd_body' );
}

/**
 * Hook the fields
 */
add_action( 'wp_head', 'dd_head_scripts' );
function dd_head_scripts() {
	echo dd_cookie_check( get_theme_mod( 'dd_header_scripts' ) );

}

add_action( 'dd_body', 'dd_body_scripts' );
function dd_body_scripts() {
	echo dd_cookie_check( get_theme_mod( 'dd_body_scripts' ) );
}

add_action( 'wp_footer', 'dd_footer_scripts' );
function dd_footer_scripts() {
	echo dd_cookie_check( get_theme_mod( 'dd_footer_scripts' ) );
}

/**
 * Load scripts only if a specific cookie is set. Example:
 * <<cookie=eucookie>>YOUR SCRIPT HERE<</cookie>>
 * @param $scripts
 *
 * @return string
 */
function dd_cookie_check( $scripts ) {
	preg_match_all( '/<<cookie=(.*?)>>(.*?)<<\/cookie>>/s', $scripts, $match );
	$output = '';
	if ( $match[1] ) {
		foreach ( $match[1] as $num => $cookie ) {

			if ( isset( $_COOKIE[ $cookie ] ) || $cookie === '0' ) {
				$output .= $match[2][ $num ];
			}
		}
	} else {
		$output = $scripts;
	}

	return $output;
}