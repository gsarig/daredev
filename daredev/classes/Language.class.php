<?php


namespace DareDev;

class Language {
	/**
	 * Show a language switcher
	 *
	 * @param string $type
	 * @param string $before
	 * @param string $after
	 * @param string $params
	 *
	 * @return string
	 */
	public static function switcher(
		$type = 'wpml',
		$before = '',
		$after = '',
		$params = 'skip_missing=0&orderby=custom'
	) {
		$output = '';
		if ( 'wpml' === $type && function_exists( 'icl_get_languages' ) ):
			$languages = icl_get_languages( $params ); // WPML language parameters: https://wpml.org/documentation/getting-started-guide/language-setup/custom-language-switcher/
			if ( count( $languages ) >= 1 ) {
				$entries = '';
				foreach ( (array) $languages as $language ) {
					$code    = $language['language_code'];
					$current = $language['active'] == 1 ? ' current' : '';
					$url     = esc_url( $language['url'] );
					$name    = $language['native_name'];
					$entries .= '<li class="lang-' . $code . $current . '">
									<a rel="alternate" hreflang="' . $code . '" href="' . $url . '">' . $name . '</a>
								</li>';
				}
				if ( $entries ) {
					$output = '<div class="dd-lang-container">' . $before . '<ul>' . $entries . '</ul>' . $after . '</div>';
				}
			}
		endif;

		return $output;
	}
}