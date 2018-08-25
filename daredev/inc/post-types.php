<?php
/**
 * Custom Post Types
 */


$hotel = new \DareDev\PostType(
	'hotel', [
	'has_archive' => true,
	'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ]
], [
	'singular_name' => __( 'Hotel', 'daredev' ),
	'plural_name'   => __( 'Hotels', 'daredev' ),
], 'hotels'
);

$event = new \DareDev\PostType(
	'event', [
	'has_archive' => true,
	'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ]
], [
	'singular_name' => __( 'Event', 'daredev' ),
	'plural_name'   => __( 'Events', 'daredev' ),
], 'events'
);
$offer = new \DareDev\PostType(
	'offer', [
	'has_archive' => true,
	'supports'    => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ]
], [
	'singular_name' => __( 'Offer', 'daredev' ),
	'plural_name'   => __( 'Offers', 'daredev' ),
], 'offers'
);

// Taxonomies
$location = new \DareDev\Taxonomy(
	'location', [
	'hotel',
	'event',
	'offer'
], [
	'singular_name' => 'Location',
	'plural_name'   => 'Locations'
], 'destinations', false, true
);
$style    = new \DareDev\Taxonomy(
	'style', [
	'hotel'
], [
	'singular_name' => 'Style',
	'plural_name'   => 'Styles'
], 'style', false, true
);
$loc_type = new \DareDev\Taxonomy(
	'loc_type', [
	'hotel'
], [
	'singular_name' => 'Location type',
	'plural_name'   => 'Location types'
], 'loc_type', false, true
);
$amenity  = new \DareDev\Taxonomy(
	'amenity', [
	'hotel'
], [
	'singular_name' => 'Amenity',
	'plural_name'   => 'Amenities'
], 'amenity', false, true
);
