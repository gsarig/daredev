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
	echo get_theme_mod( 'dd_header_scripts' );
}

add_action( 'dd_body', 'dd_body_scripts' );
function dd_body_scripts() {
	echo get_theme_mod( 'dd_body_scripts' );
}

add_action( 'wp_footer', 'dd_footer_scripts' );
function dd_footer_scripts() {
	echo get_theme_mod( 'dd_footer_scripts' );
}