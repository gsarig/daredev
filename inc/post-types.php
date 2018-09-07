<?php
/**
 * Custom Post Types
 *
 */


// Rooms
$room = new \DareDev\PostType(
	'room',
	[
		'has_archive' => true,
		'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ]
	],
	[
		'singular_name' => __( 'Δωμάτιο', BOOKING_TEXTDOMAIN ),
		'plural_name'   => __( 'Δωμάτια', BOOKING_TEXTDOMAIN ),
		'singular_case' => __( 'Δωματίου', BOOKING_TEXTDOMAIN ),
		'plural_case'   => __( 'Δωματίων', BOOKING_TEXTDOMAIN )
	],
	'rooms',
	'greek'
);