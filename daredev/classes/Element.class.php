<?php

namespace DareDev;

class Element {

	/**
	 * Share buttons
	 *
	 * Run it like that: echo wp_kses(
	 * DareDev\Element::share(['facebook' => 'fb', 'twitter' => ''], 'button'),
	 * DareDev\Helper::kses_allow_html( [ 'button', 'div', 'span', 'ul', 'li', 'a' ] )
	 * );
	 *
	 * @param array $networks Example ['facebook' => 'my-icon', 'twitter' => 'my-icon'].
	 * @param string $button Text string.
	 * @param integer $post_id The post ID.
	 * @param string $class Override the default class.
	 *
	 * @return string
	 */
	public static function share( $networks = [], $button = null, $post_id = null, $class = 'social-share' ) {
		$permalink    = esc_url( get_the_permalink( $post_id ) );
		$thumbnail    = get_the_post_thumbnail_url( $post_id, 'medium' );
		$share_title  = get_the_title( $post_id );
		$description  = get_the_excerpt( $post_id );
		$btn          = $button ? $button : '<button class="share-toggle-button">Share</button>';
		$all_networks = [
			'facebook'  => [
				'href'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink,
				'anchor' => '<span class="anchor"><span class="text">Facebook</span></span>',
			],
			'twitter'   => [
				'href'   => '"https://twitter.com/share?text=' . $share_title . '&url=' . $permalink,
				'anchor' => '<span class="anchor"><span class="text">Twitter</span></span>',
			],
			'pinterest' => [
				'href'   => 'https://pinterest.com/pin/create/button/?url=' . $permalink . '&media=' . $thumbnail . '&description=' . $description,
				'anchor' => '<span class="anchor"><span class="text">Pinterest</span></span>',
			],
			'linkedin'  => [
				'href'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $permalink . '&title=' . $share_title . '&summary=' . $description . '&source=' . get_site_url() . '',
				'anchor' => '<span class="anchor"><span class="text">LinkedIn</span></span>',
			],
			'email'     => [
				'href'   => 'mailto:?subject=' . $share_title . '&body=' . $description . ' (' . $permalink . ')',
				'anchor' => '<span class="anchor"><span class="text">Email</span></span>',
			],
		];
		$links        = '';
		if ( $networks ) {
			foreach ( $networks as $name => $anchor ) {
				$href  = $all_networks[ $name ]['href'];
				$txt   = $anchor ? $anchor : $all_networks[ $name ]['anchor'];
				$links .= '<li class="' . $name . '"><a href="' . $href . '" target="_blank" title="' . $name . '">' . $txt . '</a></li>';
			}
		} else {
			foreach ( $all_networks as $name => $value ) {
				$href   = $value['href'];
				$anchor = $value['anchor'];
				$links  .= '<li class="' . $name . '"><a href="' . $href . '" target="_blank" title="' . $name . '">' . $anchor . '</a></li>';
			}
		}

		return '<div class="' . $class . '">
					' . $btn . '
					<ul>' . $links . '</ul>
				</div>';
	}

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

	/**
	 * Get inline SVG from WordPress image ID or URL
	 *
	 * @param $img
	 * @param string $class
	 * @param string $id
	 * @param string $title
	 *
	 * @return false|mixed|string
	 */
	public static function inline_svg( $img, $class = '', $id = '', $title = '' ) {
		$output = '';
		if ( $img ) :
			$img_url = is_int( $img ) ? get_attached_file( $img ) : $img;
			ob_start();
			include $img_url;
			$icon = ob_get_contents();
			ob_end_clean();
			if ( false === $title ) {
				$icon = Helper::replace_between( $icon, '<title>', '</title>', '' );
			} elseif ( $title ) {
				$icon = Helper::replace_between( $icon, '<title>', '</title>', $title );
			}
			if ( $class && ! strpos( $icon, 'class="' ) ) {
				$icon = str_replace( '<svg', '<svg class=""', $icon );
			}
			if ( $class || $id ) {
				$search  = [];
				$replace = [];
				array_push(
					$search,
					$class ? 'class="' : null,
					$id ? '<svg' : null
				);
				array_push(
					$replace,
					$class ? 'class="' . $class . ' ' : null,
					$id ? '<svg id="' . $id . '" ' : null
				);
				$output = str_replace( $search, $replace, $icon );
			} else {
				$output = $icon;
			}
		endif;

		return $output;
	}

	/**
	 * Get custom logo url.
	 * @return bool
	 */
	public static function logo_url() {
		$url = false;
		if ( has_custom_logo() ) {
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$logo_meta      = wp_get_attachment_image_src( $custom_logo_id, 'full' );

			$url = $logo_meta[0];
		}

		return $url;
	}

	public static function svg_logo() {
		$logo    = '';
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$logo_src = wp_get_attachment_image_src( $logo_id, 'original' );
			$site_url = esc_url( home_url( '/' ) );
			$logo_alt = get_bloginfo( 'name' );

			$logo = '<a href="' . $site_url . '" class="logo"><img src="' . $logo_src[0] . '" alt="' . $logo_alt . '"></a>';
		}

		return $logo;
	}

	public static function blogTitle() {
		/*
		 * Get the blog page title (if we are in the blog page)
		*/
		return ( get_option( 'page_for_posts' ) ) ?
			'<h1 class="entry-title">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>'
			: '';
	}

	/*
	 * Numbered pagination
	 * @link https://codex.wordpress.org/Function_Reference/paginate_links
	 */

	public static function numericPagination(
		$prev = '<span class="numbered__pagination-prev">&laquo;<span class="screen-reader-text">Previous</span></span>',
		$next = '<span class="numbered__pagination-next">&raquo;<span class="screen-reader-text">Next</span></span>',
		$class = 'numbered__pagination-container'
	) {
		global $wp_query;

		$big        = 999999999; // need an unlikely integer
		$translated = __( 'Page', 'daredev' );

		$nav = paginate_links( array(
			'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'             => '?paged=%#%',
			'current'            => max( 1, get_query_var( 'paged' ) ),
			'total'              => $wp_query->max_num_pages,
			'prev_text'          => $prev,
			'next_text'          => $next,
			'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
		) );

		return '<div class="' . $class . '">' . $nav . '</div>';
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

	/**
	 * Create links with font-icons from an array
	 *
	 * @param array array ('icon-name' => 'url')
	 * @param string $before
	 * @param string $after
	 * @param string $class_prefix
	 *
	 * @return string
	 */
	public static function icon_links(
		$links = [],
		$before = '<ul>',
		$after = '</ul>',
		$class_prefix = 'icon icon-'
	) {
		$output = '';
		if ( $links ) {
			$sites = '';
			foreach ( $links as $key => $value ) {
				$url = is_array( $value ) ? $value[0] : $value;
				$txt = is_array( $value ) ? '<span>' . $value[1] . '</span>' : '';

				$sites .= ( $url ) ?
					'<li class="' . $key . '">
                        <a href="' . esc_url( $url ) . '" target="_blank">
                            <span class="' . $class_prefix . $key . '">' . $txt . '</span>
                        </a>
                    </li>'
					: '';
			}
			$output = $before . $sites . $after;
		}

		return $output;
	}
}