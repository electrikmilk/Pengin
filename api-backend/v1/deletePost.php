<?php
$id = $_POST[ 'id' ];
if ( $id ) {
  $post = dataArray( "posts", $id, "id" );
  $thread = dataArray( "threads", $id, "op" );
  $author = $post[ 'author' ];
  if ( $author === $user_session ) {
    // Delete any images attached to the post that are not third-party
    if ( !filter_var( $post[ 'image' ], FILTER_VALIDATE_URL ) ) { // if not third-party image
      $photos = explode( ",", $post[ 'image' ] );
      foreach ( $photos as $photo ) {
        $split = explode( "/", $photo );
        deleteDir( "../img/posts/user_session/" . $split[ 4 ] . "/" );
      }
    }
    if ( mysqli_query( $connect, "delete from data.posts where id = '$id'" ) ) {
      if ( $thread ) {
        if ( mysqli_query( $connect, "delete from data.threads where op = '$id'" ) )echo json_response( "success", "Thread was deleted forever and ever." );
        else echo json_response( "error", "There was an error deleting that thread." );
      } else echo json_response( "success", "Post was deleted forever and ever." );
    } else echo json_response( "error", "There was an error deleting that post." );
  } else echo json_response( "error", "Huh...You don't appear to own that post." );
} else echo json_response( "error", "No post recieved." );