<?php

namespace DareDev;

class Slideshow {

	public $script;
	public $src;
	public $deps = [ ];
	public $min;

	/**
	 * Slideshow constructor.
	 *
	 * @param $script
	 * @param array $deps
	 * @param string $src
	 * @param bool $min
	 */
	public function __construct( $script, $deps = [ 'jquery' ], $src = 'js/slideshows/', $min = true ) {
		$this->script   = $script;
		$this->src      = trailingslashit( $src );
		$this->deps     = $deps;
		$this->minified = $min;
	}

	public function show( $slides = '', $params = '', $custom_selectors = '', $custom_order = false ) {
		add_action( 'wp_footer', array( $this, 'enqueueScripts' ) );
		if ( $this->script === 'custom' ) {
			$output = self::customSlides( $params, $slides, $custom_selectors, $custom_order );
		} else {
			if ( $this->script === 'slider-pro' ) {
				$class   = 'slider-pro';
				$attr    = '';
				$dparams = '';
			} elseif ( $this->script === 'tcycle' ) {
				$class   = 'tcycle';
				$attr    = 'data-';
				$dparams = '';
			} else {
				$class   = 'cycle-slideshow';
				$attr    = 'data-cycle-';
				$dparams = [ 'slides' => '> div', 'log' => 'false', 'pause-on-hover' => 'true' ];
			}
			$params = ( $params ) ? $params : $dparams;

			$output = '<div class="' . $class . '"' . self::params( $params, $attr ) . '>' . $slides . '</div>';
		}

		return $output;
	}

	public function customSlides( $controls = [ ], $slides = [ ], $selectors, $reverse = false ) {
		$html = '';
		$selectors = ($selectors) ? $selectors : ' class="slideshow" id="slideshow"';
		if ( is_array( $controls ) && is_array( $slides ) ) :
			$ic             = 0;
			$is             = 0;
			$control_output = '';
			$slide_output   = '';
			foreach ( $controls as $control ) {
				$ic     = ++ $ic;
				$active = ( $ic === 1 ) ? ' active' : '';
				$control_output .= '<li data-slide="slide-' . $ic . '" class="control' . $active . '">
							' . $control . '
						</li>';
			}
			foreach ( $slides as $slide ) {
				$is     = ++ $is;
				$active = ( $is === 1 ) ? ' active' : '';
				$slide_output .= '<li id="slide-' . $is . '" class="slide' . $active . '">' . $slide . '</li>';
			}
			$controls_output = '<ul class="controls container">' . $control_output . '</ul>';
			$slides_output = '<ul class="slides">' . $slide_output . '</ul>';
			$order = ($reverse) ? $slides_output . $controls_output : $controls_output . $slides_output;

			$html = '<div' . $selectors . '>' . $order . '</div>';

		endif;

		return $html;
	}

	public function params( $params, $attr ) {
		$output = '';
		if ( $params ) :
			foreach ( $params as $key => $value ) :
				$output .= ' ' . $attr . $key . '="' . $value . '" ';
			endforeach;
		endif;

		return $output;
	}

	/*
	 * Enqueue scripts and pass the php variables
	 */
	public function enqueueScripts() {
		$handle   = 'dd-' . $this->script;
		$minified = ( $this->minified ) ? '.min' : '';
		$file     = $this->src . $this->script . $minified . '.js';
		$path     = plugin_dir_url( __DIR__ ) . $file;

		if ( file_exists( plugin_dir_path( __DIR__ ) . $file ) ) {
			wp_register_script( $handle, $path, $this->deps, PLUGIN_VERSION, true );
			wp_enqueue_script( $handle );
		}
	}
}

/* 
    In your theme, create the output for your slides.
    Then, call the slideshow like that (bare minimum):
    $slides = 'The HTML output of your slides (built with a simple foreach loop)';
    
    $slideshow = new \DareDev\Slideshow('cycle2');
    echo $slideshow->show($slides);
*/