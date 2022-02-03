<?php
if ( !isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && $_SERVER[ 'HTTP_REFERER' ] != "https://pengin.app/js/index.js" ) {
  die();
}
require( "global.php" );
$action = $_POST[ 'action' ];
if ( file_get_contents( "site-backend/$action.php" ) ) include( "site-backend/$action.php" );
else http_response_code( 404 );