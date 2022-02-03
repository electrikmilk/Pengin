<?php
$limit = $_POST[ 'limit' ];
$id = $_POST[ 'id' ];
$convo = dataArray( "convos", $id, "id" );
$usernames = array();
$users = explode( ",", $convo[ 'users' ] );
foreach ( $users as $userid ) {
  $user = dataArray( "users", $userid, "id" );
  if ( $user[ 'displayname' ] )$name = $user[ 'displayname' ];
  else $name = "@" . $user[ 'username' ];
  array_push( $usernames, $name );
}
if ( $convo[ 'name' ] )$title = $convo[ 'name' ];
else $title = implode( ", ", $usernames );
foreach ( $usernames as $username ) {
  if ( count( $usernames ) === 1 )$list = " and $username";
  else {
    if ( empty( $list ) )$list = ", " . $username;
    else {
      if ( $username === end( $usernames ) )$list .= " and $username";
      else $list .= ", $username";
    }
  }
}
mysqli_query( $connect, "update data.messages set new = '0' where convo = '$id' and author != '$user_session'" );
$start = timeago( $convo[ 'timestamp' ], false, true );
echo "<div class='content-block'><h3>" . t( 'begin-convo' ) . "$list</h3><div class='input-context'>" . t( 'Created' ) . " $start</div><br/><div class='privacy-message'>" . t( 'Only you' ) . "$list " . t( 'can see these messages.' ) . "</div></div><hr/>";
$messages = mysqli_query( $connect, "select * from data.messages where convo = '$id' order by timestamp asc" );
if ( intVal( mysqli_num_rows( $messages ) ) > 20 ) {
  if ( !$limit )$limit = date( "Y-m-d H:i:s", strtotime( "-1 day" ) );
  $messages = mysqli_query( $connect, "select * from data.messages where convo = '$id' and timestamp >= STR_TO_DATE('$limit', '%Y-%m-%d %H:%i:%s') order by timestamp asc" );
}
$save = $convo[ 'timestamp' ];
while ( $message = mysqli_fetch_array( $messages ) ) {
  $timestamp = timeago( $message[ 'timestamp' ], false, true );
  if ( date( "H", strtotime( $message[ 'timestamp' ] ) ) !== date( "H", strtotime( $save ) ) ) {
    $save = $message[ 'timestamp' ];
    echo "<div class='messages-timestamp'>$timestamp</div>";
  }
  echo getMessage( $message[ 'id' ], $convo[ 'id' ] );
}