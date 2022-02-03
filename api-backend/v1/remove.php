<?php
$id = $_POST[ 'id' ];
$check = mysqli_query( $connect, "select * from data.activity where id = '$id' and action = 'request'" );
if ( mysqli_num_rows( $check ) === 0 ) {
  echo json_response( "error", "Something went wrong removing activity." );
} else {
  if ( $_POST[ 'allow' ] ) {
    $activity = dataArray( "activity", $id, "id" );
    $content = $activity[ 'content' ];
    $author = $activity[ 'author' ];
    if ( mysqli_query( $connect, "insert into data.activity (action,author,content) values ('follow','$author','$content')" ) ) {
      $author = dataArray( "users", $author, "id" );
      if ( mysqli_query( $connect, "delete from data.activity where id = '$id'" ) )echo json_response( "success", "@" . $author[ 'username' ] . " is now following you!" );
      else echo json_response( "error", "Something went wrong removing activity." );
    } else echo json_response( "error", "Something went wrong accepting request." );
  } else {
    if ( mysqli_query( $connect, "delete from data.activity where id = '$id'" ) )echo json_response( "success", "Follow request declined." );
    else echo json_response( "error", "Something went wrong removing activity." );
  }
}