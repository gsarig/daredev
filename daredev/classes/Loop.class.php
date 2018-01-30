<?php 

namespace DareDev;

use WP_Query as Global_WP_Query;

class Loop {

   /*
     Fetch posts using WP_Query.
     Use it like that: 
        
        $myloop = new DareDev\Loop( $post_types, $posts_number, $additional_arguments);
        echo $myloop->fetch();
        
        Examples:

        // Minimum example (would fetch all posts)
        $myloop = new DareDev\Loop();
        echo $myloop->fetch(); 

        // Full example
        $myloop = new DareDev\Loop(['post_type_1', 'post_type_2'], 10, [
                'argument_1' => 'something',
                'argument_2' => 'something else'
            ]
        );
        echo $myloop->fetch( 'thumbnail', 'div', 'p', 'h2', false ); 
     ?>
    */

    public $post_type;
    public $posts_per_page;
    public $args = [];
    
    
    public function __construct( $post_type = 'post', $posts_per_page = -1, $args = [] ) 
    {
        $this->post_type = $post_type;
        $this->posts_per_page = $posts_per_page;
        $this->args = $args;
        $main_args = [
            'post_type' => $this->post_type,
            'posts_per_page' => $this->posts_per_page,
        ];
        $args = array_merge( $main_args, $this->args );

        $this->items = self::getData($args);
    }
    
    /*
     * Fetch the posts.
     */
    public function fetch( 
        $thumbs = false,
        $class= null,
        $container = 'ul', 
        $item = 'li', 
        $tag = 'h3', 
        $id = null, 
        $links = true,
        $excerpt = false,
        $more = false
    ) 
    {
        $class = ($class) ? ' class="' . $class . '"' : '';
        $id = ($id) ? ' id="' . $id . '"' : '';
        $container = ($container) ? $container : 'ul';
        $item = ($item) ? $item : 'li';
        $tag = ($tag) ? $tag : 'h3';

        $output = '';
        if($this->items) :
            foreach( $this->items as $this_item ) :
                $lnk_open = ($links) ? '<a href="' . get_the_permalink($this_item->ID) . '" rel="bookmark">' : '';
                $lnk_close = ($links) ? '</a>' : '';
                $img = ($thumbs) ? $lnk_open . get_the_post_thumbnail($this_item->ID, $thumbs ) . $lnk_close : '';

                // Excerpt (uses \DareDev\Excerpt::Limit method)
                $number = is_int($excerpt) ? $excerpt : 999;
                $get_excerpt = ($this_item->post_excerpt) ? $this_item->post_excerpt : $this_item->post_content;
                $get_desc = ($excerpt) ? $get_excerpt : '';
                $more_txt = ($more) ? $more : null;
                $lnk_id = ($more) ? $this_item->ID : '';
                $limitDesc = Excerpt::Limit( $number, $more_txt, $get_desc, $lnk_id );
                $desc = ($excerpt) ? $limitDesc : '';

                $output .= '
                    <'.$item.'>' .
                            $img .
                        '<'.$tag.'>' .
                        $lnk_open .
                            $this_item->post_title .
                        $lnk_close .
                        '</'.$tag.'>' .
                         $desc .
                    '</'.$item.'>';
            endforeach;
        endif;
        
        return '<'.$container. $class . $id .'>' 
                    . $output . 
                '</'.$container.'>';
    }
    
    /*
        Start a new WP_Query and return the data.
     */
    public static function getData($args) 
    {
        $the_query = new Global_WP_Query( $args );
        if( $the_query->have_posts() ) :
            while($the_query->have_posts() ) :
                
                return $the_query->posts;

            endwhile;
        endif;
        wp_reset_postdata();
    }
   
}
