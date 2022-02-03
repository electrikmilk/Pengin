<?php
require_once "global.php";
$tomorrow = date( "D, j M Y", strtotime( "+1 day" ) );
header( "Expires: $tomorrow 00:00:00 GMT" );
$type = $_GET[ 'type' ];
$id = $_GET[ 'id' ];
$arg = $_GET[ 'arg' ];
if ( $type === "avatar" ) {
  $user = dataArray( "users", $id, "username" );
  $user_id = $user[ 'id' ];
  if ( $user_session && $user && ( $user_id === $user_session || follows( $user_session, $user_id ) || strpos( $user[ 'showdetails' ], "avatar" ) !== false ) ) {
    $id = $user[ 'id' ];
    $image = $user[ 'image' ];
    if ( strpos( $image, "." ) !== false ) {
      $split = explode( "/", $image );
      $photo_id = $split[ 0 ];
      if ( $webp === false ) {
        $versions = folderArray( "img/avatars/$user_id/$photo_id" );
        foreach ( $versions as $file ) {
          $exts = pathinfo( $file, PATHINFO_EXTENSION );
          if ( !$ext && $exts !== "webp" )$ext = $exts;
        }
        if ( $arg === "large" )$image = str_replace( "image.webp", "original.$ext", $image );
        else $image = str_replace( "image.webp", "small.$ext", $image );
      }
      $name = "img/avatars/$user_id/$image";
    } else $name = "accounts/default/$image.png";
  } else $name = "accounts/default/grey.png";
} else if ( $type === "post" ) {
  $post = dataArray( "posts", $id, "id" );
  $author = dataArray( "users", $post[ 'author' ], "username" );
  if ( $author[ 'private' ] === "1" || follows( $user_session, $post[ 'author' ] ) || $post[ 'author' ] === $user_session )$showimage = true;
  else $showimage = false;
  if ( $showimage === true ) {
    if ( $post && $post[ 'image' ] ) {
      $images = explode( ",", $post[ 'image' ] );
      if ( $arg ) {
        $split = explode( "-", $arg );
        $size = $split[ 1 ];
        $i = $split[ 0 ] - 1;
        $image = trim( $images[ $i ], "/" );
      } else $image = trim( $images[ 0 ], "/" );
      if ( $webp === false ) {
        $versions = folderArray( $image );
        foreach ( $versions as $file ) {
          $exts = pathinfo( $file, PATHINFO_EXTENSION );
          if ( !$ext && $exts !== "webp" )$ext = $exts;
        }
        if ( $size && $size === "large" )$image = str_replace( "image.webp", "original.$ext", $image );
        else $image = str_replace( "image.webp", "small.$ext", $image );
      }
      $name = $image;
    } else http_response_code( 404 );
  } else http_response_code( 404 );
}
if ( $name ) {
  $ext = pathinfo( $name, PATHINFO_EXTENSION );
  $fp = fopen( $name, 'rb' );
  header( "Content-Type: image/$ext" );
  header( "Content-Length: " . filesize( $name ) );
  header( 'Content-Disposition: attachment;filename="image' . $arg . '.' . $ext . '"' );
  fpassthru( $fp );
} else http_response_code( 404 );
