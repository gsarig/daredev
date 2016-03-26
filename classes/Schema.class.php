<?php 

namespace DareDev;

class Schema {
    
    public static function duration( $duration ) {
        
        /* Convert string duration to ISO 8601 duration format */
        
        $time = strtotime($duration . ' minutes', 0);
        $units = array(
            "Y" => 365*24*3600,
            "D" =>     24*3600,
            "H" =>        3600,
            "M" =>          60,
            "S" =>           1,
        );

        $str = "P";
        $istime = false;

        foreach ($units as $unitName => &$unit) {
            $quot  = intval($time / $unit);
            $time -= $quot * $unit;
            $unit  = $quot;
            if ($unit > 0) {
                if (!$istime && in_array($unitName, array("H", "M", "S"))) { // There may be a better way to do this
                    $str .= "T";
                    $istime = true;
                }
                $str .= strval($unit) . $unitName;
            }
        }

        return $str;
    }

}