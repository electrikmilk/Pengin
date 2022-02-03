<?php
$usercontent = false;
if ( $_POST[ 'simple' ] ) {
  require( "global.php" );
  $split = explode( "/", $_POST[ 'page' ] );
  if ( strpos( $_POST[ 'page' ], "/" ) ) {
    $page = $split[ 1 ];
    $folder = $split[ 0 ];
  } else $page = $_POST[ 'page' ];
}
if ( $page === "home" || ( $user_session && !$page ) )$title = "Home";
if ( $page === "discuss" )$title = "Discuss";
if ( $page === "favorites" )$title = "Saved Threads";
if ( $page === "messages" )$title = "Direct Messages";
if ( $page === "activity" )$title = "Activity";
if ( $page === "create-thread" )$title = "Create Public Thread";
if ( $page === "post" || $page === "thread" ) {
  if ( $page === "post" ) {
    $post = dataArray( "posts", $_REQUEST[ 'id' ], "id" );
    $user = dataArray( "users", $post[ 'author' ], "id" );
    if ( strlen( $post[ 'content' ] ) > 50 )$e = "...";
    $title = t( "Post by" ) . " @" . $user[ 'username' ] . ": '" . trim( substr( $post[ 'content' ], 0, 50 ) ) . $e . "'";
    $usercontent = true;
  } else {
    $thread = dataArray( "threads", $_REQUEST[ 'id' ], "id" );
    if ( $thread ) {
      $user = dataArray( "users", $thread[ 'author' ], "id" );
      if ( strlen( $thread[ 'title' ] ) > 50 )$e = "...";
      $title = trim( substr( $thread[ 'title' ], 0, 50 ) ) . $e . " - Thread by @" . $user[ 'username' ];
      $usercontent = true;
    } else $title = "Thead not found";
  }
}
if ( $page === "profile" ) {
  $profile = dataArray( "users", $_REQUEST[ 'user' ], "username" );
  $title = $profile[ 'displayname' ] . " (@" . $profile[ 'username' ] . ")";
  $usercontent = true;
}
if ( $folder === "settings" ) {
  if ( $page === "main" )$title = "Settings";
  if ( $page === "account" )$title = "Account | Settings";
  if ( $page === "privacy" )$title = "Privacy | Settings";
  if ( $page === "reset" )$title = "Change Password | Settings";
  if ( $page === "birthday" )$title = "Change Birthday | Settings";
  if ( $page === "localize" )$title = "Localization | Settings";
  if ( $page === "sessions" )$title = "Login Activity | Settings";
}
if ( $title && $usercontent = false )$title = t( $title ); // Localize title
if ( !$title )$title = t( "Discuss topics with the world" );
if ( $_POST[ 'simple' ] )echo $title;
else echo "<title>$title - Pengin</title>";