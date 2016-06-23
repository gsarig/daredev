<?php
/**
 * Various Effects
 * Use them like this:
 * $effect = new \DareDev\Effects('scriptName');
 * echo $effect->scriptName($params);
 *
 */

namespace DareDev;


class Effects {
	public $effect;

	public function __construct( $effect ) {
		$this->effect = $effect;
		self::enqueueScripts( $this->effect );
		add_action( 'wp_footer', array( $this, 'enqueueScripts' ) );
	}


	/**
	 * Image Parallax
	 * Use it like this:
	 *
	 * $myimage = new \DareDev\Effects('imageParallax');
	 * echo $myimage->imageParallax('image_url', 'Some title', 'Some excerpt', '1.5');
	 *
	 * @param string $imageurl
	 * @param string $title
	 * @param string $excerpt
	 * @param string $speed
	 *
	 * @return string
	 */
	public function imageParallax( $imageurl = '', $title = '', $excerpt = '', $speed = '0.5' ) {
		$show_title   = ( $title ) ? '<h1 class="daredev-page-title">' . $title . '</h1>' : '';
		$show_excerpt = ( $excerpt ) ? '<span class="daredev-page-caption">' . $excerpt . '</span>' : '';
		$oc           = ( $title || $excerpt ) ? '<div class="daredev-page-title-container container">' : '';
		$cc           = ( $title || $excerpt ) ? '</div>' : '';

		$output = '<div class="daredev-parallax-wrapper" style="background-image: url(' . $imageurl . '); background-size: cover; background-position: center 0px;" data-bgspeed="' . $speed . '">
			<div class="daredev-page-title-overlay"></div>
				' . $oc . $show_title . $show_excerpt . $cc . '
			</div>
		</div>';

		return ( $imageurl ) ? $output : '';
	}

	/*
	 * Enqueue script
	 */
	public function enqueueScripts( $script, $jquery = true ) {
		$jq  = ( $jquery === true ) ? 'jquery' : '';
		$dot = ( $jquery === true ) ? '.' : '';
		wp_enqueue_script( 'daredev-' . $script, plugin_dir_url( __DIR__ ) . 'js/effects/' . $script . $dot . $jq . '.js', array( $jq ), PLUGIN_VERSION, true );
	}

}