<?php
/**
 * Profile PHP Scripts
 *
 * example:
 *
 * DareDev\Profile::flag( 'My Function 1' );
 * my_function1();
 * DareDev\Profile::flag( 'My Function 2' );
 * my_function2();
 * DareDev\Profile::result();
 *
 */

namespace DareDev;


class Profile {
// Call this at each point of interest, passing a descriptive string
	public static function flag( $str ) {
		global $prof_timing, $prof_names;
		$prof_timing[] = microtime( true );
		$prof_names[]  = $str;
	}

// Call this when you're done and want to see the results
	public static function result() {
		global $prof_timing, $prof_names;
		self::flag( 'Done' );
		$size    = count( $prof_timing );
		$results = '';
		for ( $i = 0; $i < $size - 1; $i ++ ) {
			$results .= '<b>' . $prof_names[ $i ] . ':</b> ' . sprintf( '%f', $prof_timing[ $i + 1 ] - $prof_timing[ $i ] ) . ' sec <br />';
		}
		echo '<pre>' . $results . '</pre>';
	}
}