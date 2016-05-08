<?php

namespace DareDev;

class Excerpt {

	public static function limit( $new_length = null, $more = true, $string = null, $id = null, $dots = '...' ) {
		/**
		 * Custom excerpt which allows you to set the length.
		 * Usage: echo \DareDev\Excerpt::limit(40, 'more...');
		 *
		 */
		$id          = ( $id ) ? $id : get_the_ID();
		$permalink   = get_permalink( $id );
		$get_excerpt = ( $string ) ? $string : get_the_excerpt( $id );
		$excerpt     = self::shorten( $get_excerpt, $new_length, $dots );
		$more_txt    = ( $more && is_string( $more ) ) ? $more : 'Read more';
		$more        = ( $more ) ? '<a href="' . $permalink . '" class="more" rel="bookmark">' . $more_txt . '</a>' : '';
		$excerpt     = ( $excerpt ) ? '<p>' . $excerpt . $more . '</p>' : '';

		return $excerpt;
	}

	public static function shorten( $s, $num, $dots ) {
		if ( $num < mb_strlen( $s ) ) {
			$fs = mb_substr( $s, 0, $num );

			for ( $i = mb_strlen( $fs ); $i >= 0; $i -- ) {
				if ( mb_substr( $fs, $i, 1 ) == ' ' ) {
					return mb_substr( $fs, 0, $i + 1 ) . $dots;
				}
			}

			return $fs . $dots;

		} else {
			return $s;
		}
	}
}
