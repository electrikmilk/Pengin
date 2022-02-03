<?php
// Increment
$weeks = $_POST[ 'weeks' ];
if ( !$weeks )$weeks = 1;
$limit = date( "Y-m-d H:i:s", strtotime( "-" . $weeks . " week ago" ) );
$type = $_POST[ 'type' ];
if ( $type === "home" ) {
  // Get account IDs for everyone they follow
  $following = array();
  $follows = mysqli_query( $connect, "select * from data.activity where action = 'follow' and author = '$user_session'" );
  while ( $follow = mysqli_fetch_array( $follows ) ) {
    array_push( $following, $follow[ 'content' ] );
  }
  array_push( $following, $user_session );
  // Get posts from the users they follow
  $feed_posts = array();
  foreach ( $following as $followe ) {
    $posts = mysqli_query( $connect, "select * from data.posts where author = '$followe' and timestamp <= '$limit' and reply is NULL and thread is NULL" );
    while ( $post = mysqli_fetch_array( $posts ) ) {
      array_push_key_check( $feed_posts, $post[ 'timestamp' ], $post[ 'id' ] );
    }
  }
} else if ( $type === "profile" ) {
  // Get posts for this user
  $id = $_POST[ 'user' ];
  $posts = mysqli_query( $connect, "select * from data.posts where author = '$id' and timestamp <= '$limit' and reply is NULL and thread is NULL and pinned = '0'" );
  // Get pinned post if there is one
  $pinned = mysqli_query( $connect, "select * from data.posts where author = '$id' and pinned = '1' limit 1" );
  if ( mysqli_num_rows( $pinned ) !== 0 ) {
    $post = mysqli_fetch_array( $pinned );
    array_push_key_check( $feed_posts, date( "Y-m-d H:i:s" ), $post[ 'id' ] );
  }
} else if ( $type === "replies" ) {
  // Get replies to this post
  $id = $_POST[ 'post' ];
  $posts = mysqli_query( $connect, "select * from data.posts where reply = '$id' and timestamp <= '$limit'" );
} else if ( $type === "thread" ) {
  // Get replies to this thread
  $id = $_POST[ 'thread' ];
  $thread = dataArray( "threads", $id, "id" );
  $op = $thread[ 'op' ];
  $posts = mysqli_query( $connect, "select * from data.posts where reply is not NULL and thread = '$id' and id != '$op' and timestamp <= '$limit'" );
}
if ( $type !== "home" ) {
  // Create posts array
  while ( $post = mysqli_fetch_array( $posts ) ) {
    array_push_key_check( $feed_posts, $post[ 'timestamp' ], $post[ 'id' ] );
  }
}
// Sort and output posts
if ( $feed_posts ) {
  if ( $type === "replies" || $type === "thread" )ksort( $feed_posts );
  else krsort( $feed_posts );
  foreach ( $feed_posts as $datetime => $id ) {
    $feed .= getPost( $id );
  }
}
if ( $feed ) {
  echo "$feed<center><div class='end-posts'>MFW no more posts...</div></center>";
} else {
  if ( $type === "home" )echo "<div class='empty-state-message'><h3>This feed is lonely...</h3><p>Be a friend and give it some posts!</p><br/><br/><br/></div>";
}