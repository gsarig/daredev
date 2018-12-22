<?php

namespace DareDev;


class Enqueue {

	/**
	 * Enqueue Google Fonts.
	 * Use it like that:
	 *    DareDev\Enqueue::googleFonts( [ 'Jura:300,400,500,700:greek','Roboto:300,400,900:greek', ] );
	 *
	 * @param array $fonts
	 */
	public static function googleFonts( $fonts = [] ) {
		wp_enqueue_script(
			'daredev-google-fonts', WPMU_PLUGIN_URL . '/daredev/js/google-fonts-async.js', [], DAREDEV_VERSION, true
		);
		wp_localize_script(
			'daredev-google-fonts', 'data', [
				'fonts' => $fonts
			]
		);
	}

	/**
	 * Batch enqueue scripts
	 * Use it like this:
	 * \DareDev\Enqueue::scripts(['scriptName1', 'scriptName2']);
	 */
	public static function scripts( $scripts = [] ) {
		$path = plugin_dir_path( __DIR__ ) . 'js/helpers/';
		foreach ( $scripts as $script ) :
			$ext = ( file_exists( $path . $script . '.jquery.js' ) ) ? '.jquery' : '';
			$dep = ( file_exists( $path . $script . '.jquery.js' ) ) ? [ 'jquery' ] : '';

			if ( file_exists( $path . $script . '.jquery.js' ) || file_exists( $path . $script . '.js' ) ) :
				wp_enqueue_script(
					'daredev-helper-' . $script,
					plugin_dir_url( __DIR__ ) . 'js/helpers/' . $script . $ext . '.js',
					$dep,
					DAREDEV_VERSION,
					true
				);
			endif;
		endforeach;
	}
}