<?php

namespace DareDev;

class Field {

	/**
	 * Construct Link HTML from ACF Link field
	 *
	 * @param        $link_obj
	 * @param string $class
	 *
	 * @return string
	 */
	public static function link( $link_obj, $class = '', $text_before = '', $text_after = '' ) {
		$url         = $link_obj ? $link_obj['url'] : '';
		$title       = $link_obj ? $link_obj['title'] : '';
		$target      = $link_obj ? $link_obj['target'] : '';
		$show_target = $target ? ' target="' . $target . '"' : '';
		$show_class  = $class ? ' class="' . $class . '"' : '';

		return ( $url && $title ) ? '<a href="' . $url . '" ' . $show_target . $show_class . '>' . $text_before . $title . $text_after .
		                            '</a>' : '';
	}

	/**
	 * Take a textarea field and give each line a different markup
	 *
	 * @param       $data
	 * @param array $elements
	 *
	 * @return string
	 */
	public static function textarea( $data, $elements = [] ) {
		$output = '';
		$text   = explode( PHP_EOL, $data );
		if ( $text ) {
			$count = count( $elements );
			foreach ( $text as $key => $value ) {
				// If element tags are less than the text lines, format the remaining lines with the last element tag
				$element = isset( $elements[ $key ] ) ? $elements[ $key ] : $elements[ $count - 1 ];
				// If an element tag is blank, return the value
				$markup = $element ? str_replace( '><', '>' . $value . '<', $element ) : $value;
				$output .= $value ? $markup : '';
			}
		}

		return $output;
	}

	/**
	 * Pass extra parameters and attributes to the oEmbedd iframe.
	 *
	 * @param $field
	 * @param array $params
	 * @param string $attributes
	 *
	 * @return mixed
	 */
	public static function oembed( $field, $params = [], $attributes = '' ) {
		preg_match( '/src="(.+?)"/', $field, $matches );
		if ( isset( $matches[1] ) ) {
			$src     = $matches[1];
			$new_src = add_query_arg( $params, $src );
			$field   = str_replace( $src, $new_src, $field );
			$field   = str_replace( '></iframe>',
				' ' . $attributes . '></iframe>',
				$field );
		}

		return $field;
	}

	/**
	 * Get event date
	 *
	 * @param        $from
	 * @param string $to
	 * @param string $separator
	 * @param string $markup
	 *
	 * @return mixed|string
	 */
	public static function date_range(
		$from,
		$to = '',
		$separator = '-',
		$markup = '',
		$date_format = 'd/m/Y g:i a'
	) {
		$get_from       = $from ? DateTime::createFromFormat( $date_format, $from ) : '';
		$get_to         = $to ? DateTime::createFromFormat( $date_format, $to ) : '';
		$timestamp_from = $from ? $get_from->getTimestamp() : '';
		$timestamp_to   = $to ? $get_to->getTimestamp() : '';
		$fd             = date( 'j', $timestamp_from );
		$fm             = date( 'F', $timestamp_from );
		$fy             = date( 'Y', $timestamp_from );
		$td             = $to ? date( 'j', $timestamp_to ) : '';
		$tm             = $to ? date( 'F', $timestamp_to ) : '';
		$ty             = $to ? date( 'Y', $timestamp_to ) : '';

		$m1  = isset( $tm ) && ( $fm === $tm ) ? '' : $fm . ' ';
		$y1  = isset( $ty ) && ( $fy === $ty ) ? '' : $fy . ' ';
		$d2  = isset( $td ) && ( $fd === $td && $fm === $tm && $fy === $ty ) ? '' : $td . ' ';
		$m2  = isset( $tm ) && ( $fm !== $tm ) ? $tm : $fm;
		$y2  = isset( $ty ) && ( $fy !== $ty ) ? $ty : $fy . ' ';
		$sep = ( $d2 || ( $fm !== $tm ) || ( $fy !== $ty ) ) ? $separator : '';

		$output = $fd . ' ' . $m1 . $y1 . $sep . $d2 . $m2 . ' ' . $y2;

		return $markup ? str_replace( '><', '>' . $output . '<', $markup ) : $output;
	}

	/**
	 * Link Wrapper
	 * If URL exists, returns a link, else returns only the anchor
	 *
	 * @param        $url
	 * @param        $anchor
	 * @param string $extras
	 *
	 * @return string
	 */
	public static function link_wrapper( $url, $anchor, $extras = '' ) {
		return $url ? '<a href="' . esc_url( $url ) . '" ' . $extras . '>' . $anchor . '</a>' : $anchor;
	}

	/**
	 * Remove the final s if the number is one.
	 *
	 * @param        $text
	 * @param        $number
	 * @param string $lang
	 *
	 * @return string
	 */
	public static function pluralize( $text, $number, $lang = 'en' ) {
		$number = intval( $number );

		return ( $number === 1 && $lang === 'en' ) ? rtrim( $text, 's' ) : $text;
	}
}