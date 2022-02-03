<?php
$id = $_POST[ 'id' ];
$convo = $_POST[ 'convo' ];
$content = escape( $_POST[ 'content' ] );
if ( $convo && $_POST[ 'content' ] ) {
  $check = mysqli_query( $connect, "select * from data.messages where author = '$user_session' and convo = '$convo' and content = '$content'" );
  if ( mysqli_num_rows( $check ) <= 10 ) {
    $timestamp = date( "Y-m-d H:i:s" );
    if ( mysqli_query( $connect, "insert into data.messages (id,convo,author,content,timestamp) values ('$id','$convo','$user_session','$content','$timestamp')" ) ) {
      $id = mysqli_insert_id( $connect );
      echo json_encode( array(
        "status" => "success",
        "id" => $id
      ) );
    } else echo json_response( "error", "Something went wrong creating this conversation." );
  } else echo json_response( "error", "Sorry, you've sent this message too many times." );
} else echo json_response( "error", "You forgot to type something or no conversation ID." );