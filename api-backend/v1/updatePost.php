<?php
$id = $_POST[ 'id' ];
$thread = dataArray( "threads", $id, "op" );
$original = e( $thread[ 'title' ] );
$content = escape( $_POST[ 'content' ] );
$title = escape( $_POST[ 'title' ] );
$post = dataArray( "posts", $id, "id" );
$original = e( $post[ 'content' ] );
$timestamp = date( "Y-m-d H:i:s" );
if ( $post[ 'author' ] === $user_session ) {
  if ( $original !== $content || $thread[ 'title' ] !== $title ) {
    if ( mysqli_query( $connect, "update data.posts set content = '" . $content . "', original = '" . $original . "', edited = '$timestamp' where id = '$id'" ) ) {
      createMentions( $content, $id );
      if ( !$title )echo json_response( "success", "Post has been updated." );
      else {
        if ( mysqli_query( $connect, "update data.threads set title = '" . $title . "', original = '" . $original . "' where op = '$id'" ) )echo json_response( "success", "Post has been updated." );
        else echo json_response( "error", "There was an error updating your post. " . mysqli_error( $connect ) );
      }
    } else echo json_response( "error", "There was an error updating your post. " . mysqli_error( $connect ) );
  } else echo json_response( "success", "You didn't change anything. That's okay, you can still edit it later." );
} else echo json_response( "error", "Huh...You don't appear to own that post." );