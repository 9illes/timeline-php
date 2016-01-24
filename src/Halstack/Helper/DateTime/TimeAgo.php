<?php
namespace Halstack\Helper\DateTime;

class TimeAgo
{
    const TIMEBEFORE_NOW = 'now';
    const TIMEBEFORE_MINUTE = '{num} minute ago';
    const TIMEBEFORE_MINUTES = '{num} minutes ago';
    const TIMEBEFORE_HOUR = '{num} hour ago';
    const TIMEBEFORE_HOURS = '{num} hours ago';
    const TIMEBEFORE_YESTERDAY = 'yesterday';
    const TIMEBEFORE_FORMAT = '%e %b';
    const TIMEBEFORE_FORMAT_YEAR = '%e %b, %Y';

    public static function format($time)
    {
        $out    = ''; // what we will print out
        $now    = time(); // current time
        $diff   = $now - $time; // difference between the current and the provided dates

        if( $diff < 60 ) {// it happened now
            return TIMEBEFORE_NOW;

        } elseif( $diff < 3600 ) { // it happened X minutes ago
            return str_replace( '{num}', ( $out = round( $diff / 60 ) ), $out == 1 ? TIMEBEFORE_MINUTE : TIMEBEFORE_MINUTES );

        } elseif( $diff < 3600 * 24 ) { // it happened X hours ago
            return str_replace( '{num}', ( $out = round( $diff / 3600 ) ), $out == 1 ? TIMEBEFORE_HOUR : TIMEBEFORE_HOURS );

        } elseif( $diff < 3600 * 24 * 2 ) { // it happened yesterday
            return TIMEBEFORE_YESTERDAY;

        } else { // falling back on a usual date format as it happened later than yesterday
            return strftime( date( 'Y', $time ) == date( 'Y' ) ? TIMEBEFORE_FORMAT : TIMEBEFORE_FORMAT_YEAR, $time );
        }
    }
}
