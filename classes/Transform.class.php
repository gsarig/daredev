<?php 

namespace DareDev;

class Transform {
    
    public static function removeAccents($string) { 
        $text = [
            'Ά' => 'Α',
            'Έ' => 'Ε',
            'Ή' => 'Η',
            'Ί' => 'Ι',
            'Ό' => 'Ο',
            'Ύ' => 'Υ',
            'Ώ' => 'Ω',
            'ά' => 'α',
            'έ' => 'ε',
            'ή' => 'η',
            'ί' => 'ι',
            'ό' => 'ο',
            'ύ' => 'υ',
            'ώ' => 'ω',
            'ΰ' => 'ϋ',
            'ΐ' => 'ϊ',
        ];

        foreach( $text as $key => $value ) {
            if( $key !== false && $key >= 0 ) {
                $string = str_replace( $key, $value, $string );
            }
        }
        
        return $string;
    }

}

/* Call it like that:

   echo \DareDev\Transform::removeAccents($string);
   
*/