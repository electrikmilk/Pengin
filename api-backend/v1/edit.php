<?php
$id = $_POST[ 'id' ];
$post = dataArray( "posts", $id, "id" );
$thread = dataArray( "threads", $id, "op" );
$title = e( $thread[ 'title' ] ); // htmlspecialchars_decode
$content = e( $post[ 'content' ] );
if ( $post ) {
  if ( $post[ 'author' ] === $user_session ) {
    if ( !$post[ 'edited' ] && !$post[ 'original' ] ) {
      if ( $thread ) {
        echo json_encode( array(
          "title" => $title,
          "content" => $content
        ) );
      } else {
        echo json_encode( array(
          "content" => $content
        ) );
      }
    } else echo json_response( "error", "You already edited this post." );
  } else echo json_response( "error", "Huh...You don't appear to own that post." );
} else echo json_response( "error", "Looks like we got one on the loose! We couldn't find that post." );