<?php

class Timezone {
  public static function set( $identifier ) {
    date_default_timezone_set( $identifier ); // Set it for PHP
    define( "TIMEZONE", $identifier );
    $now = new DateTime(); // Now sync MySQL
    $mins = $now->getOffset() / 60;
    $sgn = ( $mins < 0 ? -1 : 1 );
    $mins = abs( $mins );
    $hrs = floor( $mins / 60 );
    $mins -= $hrs * 60;
    $offset = sprintf( '%+d:%02d', $hrs * $sgn, $mins );
    $db = new PDO( 'mysql:host=localhost;dbname=data', 'admin', 'N0rthP013$' );
    $db->exec( "SET time_zone='$offset';" );
  }
  public static function convert( $datetime, $to, $from ) {
    $date = new DateTime( $datetime, new DateTimeZone( $from ) );
    $date->setTimezone( new DateTimeZone( $to ) );
    $datetime = $date->format( "Y-m-d H:i:s" );
    return $datetime;
  }
}