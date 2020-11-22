<?php

/**
 *
 * In order to make ACF lighter, we disable it on the frontend and
 * use the much faster and native get_post_meta() and get_option() to
 * retrieve the content. We wrap everything on our own get_field() function
 * to avoid breaking things. If ACF is enabled on the frontend, it will
 * work as usual. If not, our get_field() takes over and outputs the same data.
 *
 */

function dd_get_field_replacement() {
	if ( ! function_exists( 'get_field' ) ) {

		/**
		 * get_field() replacement
		 *
		 */
		function get_field( $field, $entry_id = null, $entry_type = [] ) {
			$post_id = get_acf_post_id( $entry_id );
			$output  = null;
			if ( $entry_type ) {
				if ( array_key_exists( 'repeater', $entry_type ) ) {
					$output = get_acf_repeater( $field, $entry_type['repeater'], $entry_id );
				} elseif ( array_key_exists( 'group', $entry_type ) ) {
					$output = get_acf_group( $field, $entry_type['group'], $entry_id );
				}
			} else {
				if ( 'option' === $entry_id ) {
					$field  = get_option( 'options_' . $field );
					$output = ( is_array( $field ) && $field ) ?
						$field[0] :
						$field;
				} else {
					$field  = get_post_meta( $post_id, $field );
					$output = ( is_array( $field ) && $field ) ?
						$field[0] :
						$field;
				}
			}

			return $output;
		}

		function get_acf_group( $field, $subfields, $entry_id ) {
			$post_id = get_acf_post_id( $entry_id );
			$values  = [];
			if ( $subfields ) {
				foreach ( $subfields as $subfield ) {
					if ( 'option' === $entry_id ) {
						$values[ $subfield ] = get_option( $field . '_' . $subfield, true );
					} else {
						$values[ $subfield ] = get_post_meta( $post_id, $field . '_' . $subfield, true );
					}
				}
			}

			return $values;
		}

		function get_acf_repeater( $field, $subfields = [], $entry_id = null ) {
			$post_id = get_acf_post_id( $entry_id );
			$values  = [];
			if ( $subfields ) {
				$repeater = 'option' === $entry_id ? 'options_' . $field : $field;
				if ( 'option' === $entry_id ) {
					$data = get_option( $repeater, 0 );
				} else {
					$data = get_post_meta( $post_id, $repeater, true );
				}
				$count = (int)$data;

				for ( $i = 0; $i < $count; $i ++ ) {
					$value = [];
					foreach ( $subfields as $subfield ) {
						if ( 'option' === $entry_id ) {
							$value[ $subfield ] = get_option( $repeater . '_' . $i . '_' . $subfield );
						} else {
							$value[ $subfield ] = get_post_meta( $post_id, $repeater . '_' . $i . '_' . $subfield );
						}
					}
					$values[] = $value;
				}
			}

			return $values;
		}

		function get_acf_post_id( $entry_id ) {
			return $entry_id && 'option' !== $entry_id ? $entry_id : get_the_ID();
		}
	}
}

add_action( 'init', 'dd_get_field_replacement' );


/**
 * Disable ACF on Frontend
 *
 */
function dd_disable_acf_on_frontend( $plugins ) {

	if ( is_admin() ) {
		return $plugins;
	}

	foreach ( $plugins as $i => $plugin ) {
		if ( 'advanced-custom-fields-pro/acf.php' === $plugin ) {
			unset( $plugins[ $i ] );
		}
	}

	return $plugins;
}

add_filter( 'option_active_plugins', 'dd_disable_acf_on_frontend' );