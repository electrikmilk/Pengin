<?php
$public = $_POST[ 'public' ];
if ( $public === "false" ) {
  if ( $user_array[ 'private' ] === "1" )$public = true;
  else $public = false;
} else {
  if ( $public === "1" )$public = true;
  else $public = false;
}
if ( $public === false ) {
  $privacy_message = t( "private-message" );
  $type = "public";
} else {
  $privacy_message = t( "public-message" );
  $type = "private";
}
echo $privacy_message . " <a href='javascript:;' class='toggle-privacy'>" . t( 'Change to' ) . " $type</a>.";