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
	public $colors = [];
	public $single;
	public $zoom;
	public $api;

	public function __construct(
		$postType = 'post',
		$dataField = null,
		$mapId = 'map',
		$moreTxt = 'Read more',
		$mapIcon = null,
		$colors = null,
		$single = false,
		$zoom = 14,
		$api = 'AIzaSyBJ5fUsd6a6pRHFNgNeA0Xue_Qny8HUZyM'
	) {
		$this->postType  = $postType;
		$this->dataField = $dataField;
		$this->mapId     = $mapId;
		$this->moreTxt   = $moreTxt;
		$this->mapIcon   = $mapIcon;
		$this->colors    = $colors;
		$this->single    = $single;
		$this->zoom      = $zoom;
		$this->api       = isset( $api ) ? 'key=' . $api : '';

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
		wp_register_script( 'daredev-locations-api', 'https://maps.googleapis.com/maps/api/js?' . $this->api . '&libraries=places', true );
		wp_register_script( 'daredev-locations', plugin_dir_url( __DIR__ ) . 'js/locations.js', array( 'daredev-locations-api' ), DAREDEV_VERSION, true );
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
			'icon'   => isset( $this->mapIcon ) ? $this->mapIcon : self::getMapData( 'icon' ),
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
			$lat   = isset( $itemId['lat'] ) ? $itemId['lat'] : '';
			$lng   = isset( $itemId['lng'] ) ? $itemId['lng'] : '';
			$title = isset( $itemId['title'] ) ? $itemId['title'] : '';
			$desc  = isset( $itemId['description'] ) ? $itemId['description'] : '';
			$img   = isset( $itemId['image'] ) ? $itemId['image'] : '';
			$icon  = isset( $itemId['icon'] ) ? $itemId['icon'] : '';
			$more  = '';
		} else {
			$location = get_field( $this->dataField, $itemId );
			$lat      = $location['lat'];
			$lng      = $location['lng'];
			$title    = ( $itemId ) ? get_the_title( $itemId ) : get_the_title();
			$desc     = ( $itemId && has_excerpt( $itemId ) ) ? get_the_excerpt( $itemId ) : '';
			$link     = get_the_permalink( $itemId );
			$getImg   = get_the_post_thumbnail( $itemId );
			$img      = ( $itemId ) ? '<a href="' . $link . '">' . $getImg . '</a>' : $getImg;
			$icon     = null;
			$more     = ( $itemId ) ? '<p><a href="' . $link . '">' . $this->moreTxt . '</a></p>' : '';
		}

		switch ( $val ) {
			case 'title' :
				$output[] = '<h3>' . $title . '</h3>';
				break;
			case 'icon' :
				$output[] = $icon;
				break;
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