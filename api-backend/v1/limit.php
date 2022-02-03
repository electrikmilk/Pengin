<?php
if ( $_POST[ 'limit' ] === "none" )$limit = "NULL";
else $limit = "'" . date( "Y-m-d H:i:s", strtotime( "+" . $_POST[ 'limit' ] ) ) . "'";
if ( $_POST[ 'logout' ] === "true" )$logout = "'1'";
else $logout = "NULL";
if ( mysqli_query( $connect, "update data.users set timelimit = $limit, logout = $logout where id = '$user_session'" ) )echo json_response( "success", "Time limit has been set." );
else echo json_response( "error", "There was an error setting a time limit." );