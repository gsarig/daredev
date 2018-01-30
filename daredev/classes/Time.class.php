<?php 

namespace DareDev;

class Time {
    
    public static function isActive( $starts = '', $ends = '') {
            /*
             * Get present and compare it with a date.
             * Indicates the state of an event, returning 'past', 'active' and 'future'
             *
             * Usage:
             * 
             * $check_time = \DareDev\Time::isActive($from, $to);
             * if($check_time === 'active') { // Do something }
             * 
            */
            date_default_timezone_set(get_option('timezone_string'));
            $now = time();

            $start_date = strtotime($starts);
            $end_date = strtotime($ends);
            if( $now > $start_date && empty($end_date) || ($now < $end_date) && empty($start_date) ) {
                $output = 'active';
            } else if( $now < $start_date ) {
                $output = 'future';
            } elseif( $now > $end_date ) {
                $output = 'past';
            } else {
                $output = 'active';
            }

            return $output;
        
    }
}