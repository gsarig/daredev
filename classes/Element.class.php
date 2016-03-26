<?php 

namespace DareDev;

class Element {
    
    public static function logo($logo = 'logo') {
        /*
         * Show the site logo
        */
        $url = esc_url( home_url( '/' ) );
        $name = get_bloginfo( 'name' );
        $desc = get_bloginfo( 'description' );
        $html = '<h1 class="site-title">
                    <a href="' . $url . '" rel="home">
                        <img src="' . get_theme_mod($logo) . '" alt="' . $name . ' - ' . $desc . '">
                    </a>
                </h1>';

        return $html;
    }
    
    public static function blogTitle() {
        /*
         * Get the blog page title (if we are in the blog page)
        */
        return ( get_option( 'page_for_posts' ) ) ?
            '<h1 class="entry-title">' . get_the_title( get_option( 'page_for_posts' ) ) . '</h1>'
                : '';
    }
    
    public static function numericPagination($prevTxt = null, $nextTxt = null) {
        /*
         * Add numeric pagination to archives
        */
        if( is_singular() )
            return;

        global $wp_query;

        /** Stop execution if there's only 1 page */
        if( $wp_query->max_num_pages <= 1 )
            return;

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $wp_query->max_num_pages );

        /**	Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;

	    /**	Add the pages around the current page to the array */
	    if ( $paged >= 3 ) {
	        $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<div class="pagination"><ul>' . "\n";

        /**	Previous Post Link */
        if ( get_previous_posts_link() )
            printf( '<li>%s</li>' . "\n", get_previous_posts_link($prevTxt) );

        /**	Link to first page, plus ellipses if necessary */
        if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class="active"' : '';

            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

            if ( ! in_array( 2, $links ) )
                echo '<li>…</li>';
        }

        /**	Link to current page, plus 2 pages in either direction if necessary */
        sort( $links );
        foreach ( (array) $links as $link ) {
            $class = $paged == $link ? ' class="active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        }

        /**	Link to last page, plus ellipses if necessary */
        if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";

            $class = $paged == $max ? ' class="active"' : '';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
        }

        /**	Next Post Link */
        if ( get_next_posts_link() )
            printf( '<li>%s</li>' . "\n", get_next_posts_link($nextTxt) );

        echo '</ul></div>' . "\n";

    }

    public static function searchBox( $txt = 'Search', $icon = '<i class="icon-search"></i>', $showButton = false ) {
        $home_url = esc_url( home_url( '/' ) );
		$button = ($showButton === true ) ? '<input type="submit" class="search-submit" value="'. $txt .'">' : '';
        echo '<form role="search" method="get" class="navbar-search" action="' . $home_url . '">
				<label>
					<span class="screen-reader-text">' . $txt . '</span>
					<input type="search" class="search-query" placeholder="' . $txt . '" value="' . esc_attr( get_search_query()) . '" name="s">' .
					$icon .
				'</label>'
				. $button .
				'</form>';
    }

    public static function social($array = []) {
        /**
         * Get social media links
         *
         * @param $array array ('icon-name' => 'url')
         *
         * @return string
         */
        $output = '';
        if( $array ) {
            $sites = '';
            foreach ($array as $key => $value ) {

                $sites .= ($value) ?
                    '<li class="' . $key . '">
    							<a href="' . $value . '" target="_blank">
    								<i class="icon icon-' . $key . '"></i>
    							</a>
    						</li>'
                    : '';
            }
            $output = ($array) ? '<ul>' . $sites . '</ul>' : '';
        }
        return $output;
    }
}