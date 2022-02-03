<?php
$action = $_POST[ 'what' ];
$content = $_POST[ 'content' ];
if ( $_POST[ 'target' ] ) {
  $target = "'" . $_POST[ 'target' ] . "'";
  $iftarget = "and target = $target";
} else $target = "NULL";
if ( $user_session && $action && $content ) {
  if ( $user_array[ 'status' ] === "0" ) {
    // Can only create follow if a request exists to follow this person
    if ( $action === "follow" && $user_array[ 'canfollow' ] === "1" ) {
      $check = mysqli_query( $connect, "select * from data.activity where author = '$user_session' and action = 'request' and content = '$content'" );
      if ( mysqli_num_rows( $check ) === 0 )$proceed = false;
      else $proceed = true;
    } else $proceed = true;
    if ( blacklist( "block", $user_session, $content ) )$proceed === false;
    if ( blacklist( "block", $content, $user_session ) )$proceed === false;
    if ( $proceed === true ) {
      if ( $content === $user_session || $target === $user_session || blacklist( false, $content, $user_session ) || blacklist( false, $target, $user_session ) )$new = "0";
      else $new = "1";
      $check = mysqli_query( $connect, "select * from data.activity where author = '$user_session' and action = '$action' and content = '$content' $iftarget" );
      if ( mysqli_num_rows( $check ) === 0 ) {
        $timestamp = date( "Y-m-d H:i:s" );
        if ( mysqli_query( $connect, "insert into data.activity (author,action,content,target,new,timestamp) values ('$user_session','$action','$content',$target,'$new','$timestamp')" ) )echo json_response( "success", numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.activity where action = '$action' and content = '$content'" ) ) ) );
        else echo json_response( "error", "Something went wrong creating activity." );
      } else {
        $activity = mysqli_fetch_array( $check );
        $activity_id = $activity[ 'id' ];
        if ( mysqli_query( $connect, "delete from data.activity where id = '$activity_id'" ) )echo json_response( "success", numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.activity where action = '$action' and content = '$content'" ) ) ) );
        else echo json_response( "error", "Something went wrong removing activity." );
      }
    }
  } else echo json_response( "error", "You cannot interact with content, your account has been suspended or terminated." );
} else echo json_response( "error", "Something went wrong creating activity." );