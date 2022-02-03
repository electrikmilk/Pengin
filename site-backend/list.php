<?php
$type = $_POST[ 'type' ];
$target = $_POST[ 'id' ];
$search_query = trim( strip_tags( htmlspecialchars( $_POST[ 'query' ] ) ) );
$list = array();
if ( $type === "messages" ) {
  $query = mysqli_query( $connect, "select * from data.users" );
  while ( $item = mysqli_fetch_array( $query ) ) {
    if ( $user_session !== $item[ 'id' ] ) {
      $canmessage = true;
      if ( $item[ 'canmessage' ] === "1" && !follows( $user_session, $item[ 'id' ] ) )$canmessage = false;
      if ( $canmessage === true ) {
        array_push( $list, $item[ 'id' ] );
      }
    }
  }
} else if ( $type === "followers" ) {
  $query = mysqli_query( $connect, "select * from data.activity where action = 'follow' and content = '$target'" );
  while ( $item = mysqli_fetch_array( $query ) ) {
    array_push( $list, $item[ 'author' ] );
  }
} else if ( $type === "following" ) {
  $query = mysqli_query( $connect, "select * from data.activity where action = 'follow' and author = '$target'" );
  while ( $item = mysqli_fetch_array( $query ) ) {
    array_push( $list, $item[ 'content' ] );
  }
}
sort( $list );
foreach ( $list as $id ) {
  $user = dataArray( "users", $id, "id" );
  $name = $user[ 'displayname' ];
  $username = $user[ 'username' ];
  $detail = "@$username";
  $classes = array();
  if ( $type !== "messages" )$action = " data-action='profile' data-args='user=$username' data-url='/@$username'";
  if ( $user[ 'verified' ] === "1" )$verify = "<span class='verified'></span>";
  echo "<div class='post-header'>";
  echo "<div class='navigation-profile' data-background='/images/avatar/$username' $action></div>";
  echo "<div class='posts-author' $action><div class='post-author'>$name$verify</div><div class='post-detail'>@$username</div></div>";
  echo "<div>" . followB( $id, true ) . "</div>";
  echo "</div>";
}
if ( $users )echo $users;
else echo "<div class='empty-state-message'>" . t( 'No results for' ) . " <b>'$search_query'</b></div>";