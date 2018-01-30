<?php 

namespace DareDev;

class Styles {
    
    public $classes;
    public $id;
    public $path;
    
    public static $counter = 0;

    public static function selectors( $classes = '', $path = null, $id = null ) {
        if( empty($path) && empty($classes) ) {
            $output = '';
        } else {
	        $file = basename($path, ".php");

	        $tmpl = ( !empty($path) ) ? ' tmpl-' . $file : '';
	        $class = ( !empty($classes) ) ? ' class="' . $classes . '' . $tmpl . '"' : '';
	        $id = ( !empty($id) ) ? ' id="' . $id . '"' : '';
	        $output = $class . ' ' . $id;
        }
	    return $output;
    }
}

/* Call it like that:

   echo DareDev\Styles::selectors('class1 class2', __FILE__, 'some-id');
   
*/