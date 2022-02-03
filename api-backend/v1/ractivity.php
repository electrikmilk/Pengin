<?php
$action = $_POST[ 'what' ];
$content = $_POST[ 'content' ];
if ( $user_session && $action && $content ) {
  $check = mysqli_query( $connect, "select * from data.activity where action = '$action' and author = '$content' and content = '$user_session' limit 1" );
  if ( mysqli_num_rows( $check ) === 0 ) {
    echo json_response( "error", "Activity does not exist." );
  } else {
    $activity = mysqli_fetch_array( $check );
    $activity_id = $activity[ 'id' ];
    if ( mysqli_query( $connect, "delete from data.activity where id = '$activity_id'" ) )echo json_response( "success", "Activity removed." );
    else echo json_response( "error", "Something went wrong removing activity." );
  }
} else echo json_response( "error", "Something went wrong creating activity." );