<?php

namespace DareDev;

class Element {

	public static function table( $table_id ) {
		// get the post-table pair json data
		$table_json = get_option( 'tablepress_tables' );
		// json decode to array
		$json_dec = json_decode( $table_json, true );
		// get the pair data
		$post_table = $json_dec['table_post'];
		// flip the key/value of the array
		$flip = array_flip( $post_table );
		// you get the table id from postID by $flip[$post_obj->ID]
		$shortcode = '[table id=' . $flip[ $table_id ] . ' /]';

		return do_shortcode( $shortcode );
	}

	public static function content( $post_id, $post_field = 'post_content' ) {

		if ( $post_id === 'home' ) {
			$id = get_option( 'page_on_front' );
		} elseif ( $post_id === 'blog' ) {
			$id = get_option( 'page_for_posts' );
		} else {
			$id = filter_var( $post_id, FILTER_SANITIZE_NUMBER_INT );
		}
		$field = ( $post_field === 'excerpt' ) ? 'post_excerpt' : $post_field;

		return apply_filters( 'the_content', get_post_field( $field, $id ) );
	}

	/**
	 * Takes the input $content and returns it based in your corrections
	 * Call example with description on the result
	 * $mytest_content = content_filtered =( array(
	 * 'find' => 'replace',
	 * 'find2'=> array('replace2',1, $int)
	 * ) , null , true);
	 * we care about the array so for the first $key => $value
	 * this function will perform a str_replace($key,$value,get_the_content())
	 * but for the second $key => $value the function will preform a preg_replace like so
	 * preg_replace ($key , '/'.preg_quote($value[0],'/').'/',$value[1],$value[2])
	 * value[1] is the number of replacements to be made and $value[2] if set will return an int
	 * of the changes actually made
	 *
	 * @param array $replace
	 * @param string $content
	 * @param boolean $filter
	 *
	 * @return string
	 */
	public static function content_filtered( $replace = [], $content = null, $filter = true ) {
		$data = $content ? $content : get_the_content();
		if ( $replace ) {
			foreach ( $replace as $needle => $replacement ) {
				if ( 'array' === gettype( $replacement ) ) {
					$needle = '/' . preg_quote( $needle, '/' ) . '/';
					$extra  = ( count( $replacement ) > 2 ) ? $replacement[2] : '';
					$data   = preg_replace( $needle, $replacement[0], $data, $replacement[1], $extra );
				} else {
					$data = str_replace( $needle, $replacement, $data );
				}
			}
			$output = $data;
		} else {
			$output = $data;
		}

		return $filter ? apply_filters( 'the_content', $output ) : $output;
	}

	public static function logo( $src ) {
		/*
		 * Show the site logo
		*/
		$url  = esc_url( home_url( '/' ) );
		$name = get_bloginfo( 'name' );
		$desc = get_bloginfo( 'description' );
		$html = '<h1 class="site-title">
                    <a href="' . $url . '" rel="home">
                        <img src="' . esc_url( $src ) . '" alt="' . $name . ' - ' . $desc . '">
                    </a>
                </h1>';

		return $src ? $html : '';
	}

	public static function blogTitle() {
		/*
		 * Get the blog page title (if we are in the blog page)
		*/
		return ( get_option( 'page_for_posts' ) ) ?
			'<h1 class="entry-title">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>'
			: '';
	}

	public static function numericPagination( $prevTxt = null, $nextTxt = null ) {
		/*
		 * Add numeric pagination to archives
		*/
		if ( is_singular() ) {
			return;
		}

		global $wp_query;

		/** Stop execution if there's only 1 page */
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
		$max   = intval( $wp_query->max_num_pages );

		/**    Add current page to the array */
		if ( $paged >= 1 ) {
			$links[] = $paged;
		}

		/**    Add the pages around the current page to the array */
		if ( $paged >= 3 ) {
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}

		if ( ( $paged + 2 ) <= $max ) {
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}

		echo '<div class="pagination"><ul>' . "\n";

		/**    Previous Post Link */
		if ( get_previous_posts_link() ) {
			printf( '<li>%s</li>' . "\n", get_previous_posts_link( $prevTxt ) );
		}

		/**    Link to first page, plus ellipses if necessary */
		if ( ! in_array( 1, $links ) ) {
			$class = 1 == $paged ? ' class="active"' : '';

			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

			if ( ! in_array( 2, $links ) ) {
				echo '<li>…</li>';
			}
		}

		/**    Link to current page, plus 2 pages in either direction if necessary */
		sort( $links );
		foreach ( (array) $links as $link ) {
			$class = $paged == $link ? ' class="active"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
		}

		/**    Link to last page, plus ellipses if necessary */
		if ( ! in_array( $max, $links ) ) {
			if ( ! in_array( $max - 1, $links ) ) {
				echo '<li>…</li>' . "\n";
			}

			$class = $paged == $max ? ' class="active"' : '';
			printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
		}

		/**    Next Post Link */
		if ( get_next_posts_link() ) {
			printf( '<li>%s</li>' . "\n", get_next_posts_link( $nextTxt ) );
		}

		echo '</ul></div>' . "\n";

	}

	public static function searchBox( $txt = 'Search', $icon = '<i class="icon-search"></i>', $showButton = false ) {
		$home_url = esc_url( home_url( '/' ) );
		$button   = ( $showButton === true ) ? '<input type="submit" class="search-submit" value="' . $txt . '">' : '';
		echo '<form role="search" method="get" class="navbar-search" action="' . $home_url . '">
                <label>
                    <span class="screen-reader-text">' . $txt . '</span>
                    <input type="search" class="search-query" placeholder="' . $txt . '" value="' . esc_attr( get_search_query() ) . '" name="s">' .
		     $icon .
		     '</label>'
		     . $button .
		     '</form>';
	}

	public static function social( $array = [] ) {
		/**
		 * Get social media links
		 *
		 * @param $array array ('icon-name' => 'url')
		 *
		 * @return string
		 */
		$output = '';
		if ( $array ) {
			$sites = '';
			foreach ( $array as $key => $value ) {
				$url   = is_array( $value ) ? $value[0] : $value;
				$txt   = is_array( $value ) ? '<span>' . $value[1] . '</span>' : '';
				$sites .= ( $url ) ?
					'<li class="' . $key . '">
                                <a href="' . $url . '" target="_blank">
                                    <i class="icon icon-' . $key . '">' . $txt . '</i>
                                </a>
                            </li>'
					: '';
			}
			$output = ( $array ) ? '<ul>' . $sites . '</ul>' : '';
		}

		return $output;
	}
}