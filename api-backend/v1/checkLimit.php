<?php
if ( $user_array[ 'logout' ] === "1" )$logout = true;
if ( $user_array[ 'timelimit' ] ) {
  if ( date( "Y-m-d H:i:s" ) >= $user_array[ 'timelimit' ] ) {
    if ( mysqli_query( $connect, "update data.users set timelimit = NULL, logout = NULL where id = '$user_session'" ) ) {
      if ( $logout === true )echo json_response( "logout", t( "Logging you out..." ) );
      else echo json_response( "reached", t( "Your set time limit has been reached!" ) );
    } else echo json_response( "error", t( "There was an error clearing your time limit." ) );
  } else echo json_response( "waiting", "Your time limit is up in " . trim( str_replace( "ago", "", timeago( $user_array[ 'timelimit' ], true ) ) ) . "." );
} else echo json_response( "success", t( "You currently have no time limit set." ) );