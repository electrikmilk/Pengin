<?php
$id = $_POST[ 'id' ];
$post = dataArray( "posts", $id, "id" );
if ( $post[ 'pinned' ] === "1" ) {
  $message = "Post was unpinned from your profile.";
  $pin = "0";
} else {
  $pin = "1";
  $message = "Post pinned to your profile!";
}
if ( $post[ 'author' ] === $user_session ) {
  if ( mysqli_query( $connect, "update data.posts set pinned = '0' where author = '$user_session'" ) && mysqli_query( $connect, "update data.posts set pinned = '$pin' where id = '$id' and author = '$user_session';" ) )echo json_response( "success", $message );
  else echo json_response( "error", "There was an error pinning that post to your profile." );
} else echo json_response( "error", "Huh...You don't appear to own that post." );