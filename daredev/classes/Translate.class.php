<?php 

namespace DareDev;

class Translate {
    
    public static function permalink( $id, $type = null, $fallback = true, $lang = null ) {
        /*
        * WPML Multilingual permalink
        * Simplest use: \DareDev\Translate::permalink($id);
        *
        * $id – the ID of the post, page, tag or category
        * $type – ‘post’, ‘page’, ‘post_tag’, ‘category’ or ‘attachment’
        * $fallback – true if WPML should return the ID of the original language element if the translation is missing or false if WPML should return a NULL if translation is missing.
        * $lang (optional) – if set, forces the language of the returned object and can be different than the displayed language.
        * 
        * 
        * @link https://wpml.org/documentation/support/creating-multilingual-wordpress-themes/language-dependent-ids/
        */
        $item_id = isset($id) ? $id : get_the_ID();
        if(function_exists('icl_object_id')) {
            $permalink = get_permalink( icl_object_id( $item_id, $type, $fallback, $lang ) );
        } else {
            $permalink = get_permalink($item_id);
        }
        return $permalink;
    }
}