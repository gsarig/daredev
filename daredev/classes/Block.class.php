<?php


namespace DareDev;


class Block {
	/**
	 * Get specific Gutenberg blocks from a post
	 *
	 * @param array $block_names
	 * @param string|integer $selector
	 * @param null $post_id
	 *
	 * @return mixed|void|null
	 */
	public static function get( $block_names = [], $selector = 'first', $post_id = null ) {
		if ( ! $block_names || ! is_array( $block_names ) || empty( $block_names ) ) {
			return null;
		}
		$output = '';
		$blocks = parse_blocks( get_the_content( null, false, $post_id ) );

		$requested = Helper::array_search( $blocks, 'blockName', $block_names );
		if ( $requested ) {
			if ( 'all' === $selector ) {
				foreach ( $requested as $block ) {
					$output .= render_block( $block );
				}
			} elseif ( 'first' === $selector ) {
				$output = render_block( $requested[0] );
			} elseif ( 'last' === $selector ) {
				$output = render_block( end( $requested ) );
			} else {
				$int = intval( $selector );
				if ( isset( $requested[ $int ] ) ) {
					$output = render_block( $requested[ $int ] );
				}
			}
		}

		return apply_filters( 'the_content', $output );
	}
}