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
	public $cluster;
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
		$cluster = false,
		$directions = false,
		$zoom = 14,
		$api = DD_GOOGLE_MAPS_API_KEY
	) {
		$this->postType   = $postType;
		$this->dataField  = $dataField;
		$this->mapId      = $mapId;
		$this->moreTxt    = $moreTxt;
		$this->mapIcon    = $mapIcon;
		$this->colors     = $colors;
		$this->single     = $single;
		$this->cluster    = $cluster;
		$this->directions = $directions;
		$this->zoom       = $zoom;
		$this->api        = isset( $api ) ? 'key=' . $api : '';

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
	public
	function show(
		$mapClass = 'map',
		$width = '100%',
		$height = '500px'
	) {
		add_action( 'wp_footer', array( $this, 'enqueueScripts' ) );

		return '<div class="' . $mapClass . '" id="' . $this->mapId . '" style="width:' . $width . '; height: ' . $height . ';"></div>';
	}

	/*
	 * Enqueue scripts and pass the php variables
	 */
	public
	function enqueueScripts() {
		if ( $this->cluster ) {
			wp_register_script( 'daredev-gmaps-clusters-api',
				'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js',
				'',
				DAREDEV_VERSION,
				true );
			wp_register_script( 'daredev-infobox',
				WPMU_PLUGIN_URL . '/daredev/js/infobox.js',
				array( 'daredev-locations-api' ),
				DAREDEV_VERSION,
				true );
			wp_register_script( 'daredev-locations',
				WPMU_PLUGIN_URL . '/daredev/js/cluster.js',
				array( 'daredev-locations-api', 'daredev-gmaps-clusters-api', 'daredev-infobox' ),
				DAREDEV_VERSION,
				true );
		} else {
			wp_register_script( 'daredev-locations',
				WPMU_PLUGIN_URL . '/daredev/js/locations.js',
				array( 'daredev-locations-api' ),
				DAREDEV_VERSION,
				true );
		}
		if ( $this->directions ) {
			wp_register_script( 'daredev-autocomplete',
				WPMU_PLUGIN_URL . '/daredev/js/autocomplete.js',
				array( 'daredev-locations-api' ),
				DAREDEV_VERSION,
				true );
		}
		wp_register_script( 'daredev-locations-api',
			'https://maps.googleapis.com/maps/api/js?' . $this->api . '&libraries=places',
			'',
			DAREDEV_VERSION,
			true );
		wp_enqueue_script( [
			'daredev-locations-api',
			'daredev-locations',
			'daredev-cluster',
			'daredev-autocomplete',
			'daredev-gmaps-clusters-api',
		] );
		wp_localize_script( 'daredev-locations',
			'mapData',
			[
				'mapId'    => $this->mapId,
				'title'    => self::getMapData( 'title' ),
				'desc'     => self::getMapData( 'excerpt' ),
				'lat'      => self::getMapData( 'lat' ),
				'lng'      => self::getMapData( 'lng' ),
				'zoom'     => (int) $this->zoom,
				'img'      => self::getMapData( 'thumb' ),
				'more'     => self::getMapData( 'more' ),
				'icon'     => isset( $this->mapIcon ) ? $this->mapIcon : self::getMapData( 'icon' ),
				'colors'   => $this->colors,
				'themeUrl' => get_stylesheet_directory_uri(),
			] );
	}

	/**
	 * Get directions search field and link.
	 *
	 * @param array $origin
	 * @param string $anchor
	 * @param string $link_params
	 *
	 * @return string
	 */
	public function directions(
		$origin = '',
		$anchor = 'Get directions',
		$placeholder = 'Search location',
		$link_params = 'target="_blank"'
	) {
		$output = '';
		if ( $origin ) {
			$output = '<div class="ddDirectionsContainer">
					<input id="dd-places-autocomplete" class="controls" type="text" placeholder="' . $placeholder . '">
					<a id="ddDirectionsLink" data-origin="' . $origin . '" href="#" ' . $link_params . '>' . $anchor . '</a>
				</div>';
		}

		return $output;
	}

	/*
	 * Get Map Data
	 */
	public
	function getMapData(
		$val = null
	) {
		$output = [];
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


	/**
	 * A shorthand function to display a single ACF map.
	 *
	 * @param $field
	 * @param string $map_id
	 * @param string $class
	 * @param string $width
	 * @param string $height
	 * @param string $more_txt
	 * @param null $icon
	 * @param null $colors
	 * @param int $zoom
	 *
	 * @return string
	 */
	public static function acf_map_single(
		$field,
		$map_id = 'map',
		$class = 'map',
		$width = '100%',
		$height = '500px',
		$more_txt = '',
		$icon = null,
		$colors = null,
		$zoom = 14
	) {
		$data[] = [
			'lat'         => $field ? $field['lat'] : '',
			'lng'         => $field ? $field['lng'] : '',
			'title'       => $field ? $field['address'] : '',
			'description' => '',
			'image'       => '',
		];
		$map    = new Map(
			$data,
			null,
			$map_id,
			$more_txt,
			$icon,
			$colors,
			true,
			false,
			false,
			$zoom,
			DD_GOOGLE_MAPS_API_KEY
		);

		return $map->show( $class, $width, $height );
	}

	/**
	 * A shorthand function to display a multi-marker ACF map from a repeater field.
	 *
	 * @param array $field
	 * @param string $subfield
	 * @param string $map_id
	 * @param string $class
	 * @param string $width
	 * @param string $height
	 * @param string $more_txt
	 * @param null $icon
	 * @param null $colors
	 * @param int $zoom
	 *
	 * @return string
	 */
	public static function acf_map_repeater(
		$field,
		$subfield,
		$map_id = 'map',
		$class = 'map',
		$width = '100%',
		$height = '500px',
		$more_txt = '',
		$icon = null,
		$colors = null,
		$zoom = 14
	) {

		$data = [];
		foreach ( $field as $location ) {
			$data[] = [
				'lat'         => $location[ $subfield ] ? $location[ $subfield ]['lat'] : '',
				'lng'         => $location[ $subfield ] ? $location[ $subfield ]['lng'] : '',
				'title'       => $location[ $subfield ] ? $location[ $subfield ]['address'] : '',
				'description' => '',
				'image'       => '',
			];
		}
		$map = new Map(
			$data,
			null,
			$map_id,
			$more_txt,
			$icon,
			$colors,
			true,
			false,
			false,
			$zoom,
			DD_GOOGLE_MAPS_API_KEY
		);

		return $map->show( $class, $width, $height );
	}

	/*
	 * Set Map Data
	 */
	public
	function setMapData(
		$val,
		$itemId = null
	) {

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