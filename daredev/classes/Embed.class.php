<?php

namespace DareDev;

class Embed {

	public $type;

	public function __construct( $type ) {
		$this->type = $type;
	}

	public function show( $field, $attr = [], $play_btn = '' ) {
		add_action( 'wp_footer', array( $this, 'enqueueScripts' ) );
		$output = '';
		if ( $field ) {
			$attributes = $attr ? $attr : [
				'background' => 0,
				'autoplay'   => 0,
				'loop'       => 0,
				'muted'      => 0,
				'byline'     => 0,
				'title'      => 0,
				'controls'   => 0,
				'dnt'        => 0,
			];
			$playbtn    = $play_btn ? $play_btn : '<span class="video-play">&nbsp;</span>';
			$output     = '<div class="video-wrapper">' . $playbtn . \DareDev\Field::oembed(
					$field,
					$attributes,
					'frameborder="0"  webkitallowfullscreen mozallowfullscreen allowfullscreen'
				) . '</div>';
		}

		return $output;
	}

	/*
	 * Enqueue scripts and pass the php variables
	 */
	public function enqueueScripts() {
		if ( 'vimeo' === $this->type ) {
			wp_register_script( 'vimeo-api',
				'https://player.vimeo.com/api/player.js',
				'',
				DAREDEV_VERSION,
				true );
			wp_register_script( 'daredev-vimeo',
				WPMU_PLUGIN_URL . '/daredev/js/vimeo.js',
				[ 'vimeo-api' ],
				DAREDEV_VERSION,
				true );
			wp_enqueue_script( 'vimeo-api' );
			wp_enqueue_script( 'daredev-vimeo' );
		}
	}

	public static function video( $field, $attrs = [], $play_btn = '' ) {
		preg_match( '/src="(.+?)"/', $field, $matches );
		$name = strtolower( Helper::name_from_url( $matches[1] ) );
		if ( method_exists( 'Embed', $name ) ) {
			$video = Embed::$name( $field );
		} else {
			$video = '<div class="video-wrapper">' . $field . '</div>';
		}

		return $video;
	}

	public static function vimeo( $field, $attrs = [], $play_btn = '' ) {
		$embed = new Embed( 'vimeo' );

		return $embed->show( $field, $attrs, $play_btn );
	}


}