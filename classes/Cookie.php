<?php

class Cookie {
  public static function get( $cookie ) {
    return $_COOKIE[ $cookie ];
  }
  public static function set( $name, $value, $time ) {
    $expire = 86400; // 1 day in seconds
    if ( $time )$expire = strtotime( $time, 0 );
    setcookie( $name, $value, time() + ( $expire * 30 ), "/" );
  }
  public static function delete( $cookie ) {
    if ( isset( $_COOKIE[ $cookie ] ) ) {
      unset( $_COOKIE[ $cookie ] );
      setcookie( $cookie, null, -1, '/' );
      return true;
    } else return false;
  }
}