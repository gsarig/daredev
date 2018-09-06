<?php

namespace DareDev;

class Transform {

	public static function removeAccents( $string ) {
		$text = [
			'Ά'  => 'Α',
			'Έ'  => 'Ε',
			'Ή'  => 'Η',
			'Ί'  => 'Ι',
			'Ό'  => 'Ο',
			'Ύ'  => 'Υ',
			'Ώ'  => 'Ω',
			'ά'  => 'α',
			'έ'  => 'ε',
			'ή'  => 'η',
			'ί'  => 'ι',
			'ό'  => 'ο',
			'ύ'  => 'υ',
			'ώ'  => 'ω',
			'ΰ'  => 'ϋ',
			'ΐ'  => 'ϊ',
			'ΆΙ' => 'ΑΪ',
			'ΆΥ' => 'ΑΫ',
			'ΈΙ' => 'ΕΪ',
			'ΌΙ' => 'ΟΪ',
			'ΈΥ' => 'ΕΫ',
			'ΌΥ' => 'ΟΫ',
			'άι' => 'αϊ',
			'έι' => 'εϊ',
			'Άυ' => 'αϋ',
			'άυ' => 'αϋ',
			'όι' => 'οϊ',
			'Έυ' => 'εϋ',
			'έυ' => 'εϋ',
			'όυ' => 'οϋ',
			'Όυ' => 'οϋ',
		];

		foreach ( $text as $key => $value ) {
			if ( $key ) {
				$string = str_replace( $key, $value, $string );
			}
		}

		return $string;
	}

	public static function format( $text, $wrapper = '%%', $before = '<span>', $after = '</span>' ) {
		$match = preg_match(
			'/(?<=' . $wrapper . ')[^' . $wrapper . ']+(?=' . $wrapper . ')/', $text, $matches
		);

		return $match ? preg_replace(
			'/(' . $wrapper . ').*(' . $wrapper . ')/', $before . $matches[0] . $after, $text
		) : $text;
	}

	/*
	* Get the content with formatting
	*/
	public static function post_content_with_formatting( $content ) {
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}
}

/* Call it like that:

   echo \DareDev\Transform::removeAccents($string);
   
*/