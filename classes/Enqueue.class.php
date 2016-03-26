<?php
/**
 * Batch enqueue scripts
 * Use it like this:
 * \DareDev\Enqueue::scripts(['scriptName1', 'scriptName2']);
 */

namespace DareDev;


class Enqueue {

	public static function scripts($scripts = []) {
		$path = plugin_dir_path(__DIR__) . 'js/helpers/';
		foreach($scripts as $script) :
			$ext = ( file_exists($path . $script . '.jquery.js') ) ? '.jquery' : '';
			$dep = ( file_exists($path . $script . '.jquery.js') ) ? ['jquery'] : '';

			if( file_exists($path . $script . '.jquery.js') || file_exists($path . $script . '.js') ) :
				wp_enqueue_script(
					'daredev-helper-' . $script,
					plugin_dir_url( __DIR__ ) . 'js/helpers/' . $script . $ext . '.js',
					$dep,
					PLUGIN_VERSION,
					true
				);
			endif;
		endforeach;
	}
}