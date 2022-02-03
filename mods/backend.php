<?php
if ( !isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && $_SERVER[ 'HTTP_REFERER' ] != "https://pengin.app/mod/main.js" ) {
  die();
}
require( "global.php" );
$action = $_POST[ 'action' ];
if ( file_get_contents( "mods/actions/$action.php" ) ) include( "mods/actions/$action.php" );
else http_response_code( 404 );