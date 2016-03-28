<?php

namespace DareDev;

class Map {

	/*
	 * Get a Google Map with all the locations of a specific Post Type.
	 * Params: post type, acf map field, id, custom icon url, colors array.
	 *
	 * Use it like this (bare minimum):
	 *
	 * $mymap = new \DareDev\Map('post_type', 'map_field' );
	 * echo $mymap->show();
	 *
	 * Advanced usage (to customize icons, colors, dimensions):
	 *
	 * $mymap = new \DareDev\Map('post_type', 'map_field', 'id', 'Read more', 'custom_icon_url', ['000000', 'ffffff', 'cccccc'], false );
	 * echo $mymap->show('class', 'map_width', 'map_height');
	 *
	 */

	public $postType;
	public $dataField;
	public $mapId;
	public $moreTxt;
	public $mapIcon;
	public $colors = [ ];
	public $single;
	public $zoom;

	public function __construct( $postType = 'post', $dataField = null, $mapId = 'map', $moreTxt = 'Read more', $mapIcon = null, $colors = null, $single = false, $zoom = 14 ) {
		$this->postType  = $postType;
		$this->dataField = $dataField;
		$this->mapId     = $mapId;
		$this->moreTxt   = $moreTxt;
		$this->mapIcon   = $mapIcon;
		$this->colors    = $colors;
		$this->single    = $single;
		$this->zoom      = $zoom;

		if ( is_array( $this->postType ) ) {
			$this->items = $this->postType;
		} else {
			$args = [
				'post_type'      => $this->postType,
				'posts_per_page' => - 1,
			];

			$this->items = Loop::getData( $args );
		}
	}

	/*
	 * Show the map.
	 */
	public function show( $mapClass = 'map', $width = '100%', $height = '500px' ) {
		add_action( 'wp_footer', array( $this, 'enqueueScripts' ) );

		return '<div class="' . $mapClass . '" id="' . $this->mapId . '" style="width:' . $width . '; height: ' . $height . ';"></div>';
	}

	/*
	 * Enqueue scripts and pass the php variables
	 */
	public function enqueueScripts() {
		wp_register_script( 'daredev-locations-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places', true );
		wp_register_script( 'daredev-locations', plugin_dir_url( __DIR__ ) . 'js/locations.js', array( 'daredev-locations-api' ), PLUGIN_VERSION, true );
		wp_enqueue_script( [
			'daredev-locations-api',
			'daredev-locations'
		] );
		wp_localize_script( 'daredev-locations', 'mapData', [
			'mapId'  => $this->mapId,
			'title'  => self::getMapData( 'title' ),
			'desc'   => self::getMapData( 'excerpt' ),
			'lat'    => self::getMapData( 'lat' ),
			'lng'    => self::getMapData( 'lng' ),
			'zoom'   => intval( $this->zoom ),
			'img'    => self::getMapData( 'thumb' ),
			'more'   => self::getMapData( 'more' ),
			'icon'   => $this->mapIcon,
			'colors' => $this->colors
		] );
	}

	/*
	 * Get Map Data
	 */
	public function getMapData( $val = null ) {
		$output = '';
		if ( $this->single === true && is_singular( $this->postType ) ) {
			$output = self::setMapData( $val );
		} elseif ( is_array( $this->postType ) ) {
			foreach ( $this->items as $item ) :
				$output[] = self::setMapData( $val, $item );
			endforeach;
		} elseif ( $this->items ) {
			foreach ( $this->items as $item ) :
				$output[] = self::setMapData( $val, $item->ID );
			endforeach;
		}

		return $output;
	}

	/*
	 * Set Map Data
	 */
	public function setMapData( $val, $itemId = null ) {

		if ( is_array( $this->postType ) ) {
			$lat     = $itemId['lat'];
			$lng     = $itemId['lng'];
			$title   = $itemId['title'];
			$excerpt = $itemId['description'];
			$desc    = ( $excerpt ) ? $excerpt : '';
			$img     = $itemId['image'];
			$more    = '';
		} else {
			$location = get_field( $this->dataField, $itemId );
			$lat      = $location['lat'];
			$lng      = $location['lng'];
			$title    = ( $itemId ) ? $this->item->post_title : get_the_title();
			$desc     = ( $itemId && $this->item->post_excerpt ) ? $this->item->post_excerpt : '';
			$link     = get_the_permalink( $itemId );
			$getImg   = get_the_post_thumbnail( $itemId, 'icon' );
			$img      = ( $itemId ) ? '<a href="' . $link . '">' . $getImg . '</a>' : $getImg;
			$more     = ( $itemId ) ? '<p><a href="' . $link . '">' . $this->moreTxt . '</a></p>' : '';
		}

		switch ( $val ) {
			case 'title' :
				$output[] = '<h3>' . $title . '</h3>';
				break;
			// case 'icon' :
			// 	$output[] = get_template_directory_uri() . '/img/location.png';
			// 	break;
			case 'excerpt' :
				$output[] = $desc;
				break;
			case 'thumb' :
				$output[] = $img;
				break;
			case 'more' :
				$output[] = $more;
				break;
			case 'lat' :
				$output[] = $lat;
				break;
			case 'lng' :
				$output[] = $lng;
				break;
			default :
				$output[] = null;
		}

		return $output;
	}
}
