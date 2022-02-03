<?php
$type = $_POST[ 'type' ];
$target = $_POST[ 'target' ];
$user = dataArray( "users", $target, "id" );
$username = $user[ 'username' ];
if ( $type === "block" )$typed = "blocked";
else $typed = "silenced";
if ( $user_session && $type && $target ) {
  $check = mysqli_query( $connect, "select * from data.blacklist where author = '$user_session' and type = '$type' and target = '$target'" );
  if ( mysqli_num_rows( $check ) === 0 ) {
    if ( mysqli_query( $connect, "insert into data.blacklist (author,type,target) values ('$user_session','$type','$target')" ) )echo json_response( "success", "You have $typed @$username." );
    else echo json_response( "error", "Something went wrong creating activity." );
  } else {
    $activity = mysqli_fetch_array( $check );
    $activity_id = $activity[ 'id' ];
    if ( mysqli_query( $connect, "delete from data.blacklist where id = '$activity_id'" ) )echo json_response( "success", "You have un-$typed @$username." );
    else echo json_response( "error", "Something went wrong removing activity." );
  }
} else echo json_response( "error", "Something went wrong creating activity." );