<?php
$tlist = array();
$type = $_POST[ 'type' ];
if ( $type === "topic" ) {
  $topic = $_POST[ 'topic' ];
  $threads = mysqli_query( $connect, "select * from data.threads where locale = '$user_loc' and topic = '$topic' and status = '0' order by timestamp asc" );
  while ( $thread = mysqli_fetch_array( $threads ) ) {
    array_push_key_check( $tlist, $thread[ 'timestamp' ], $thread[ 'id' ] );
  }
} else if ( $type === "favorites" ) {
  $threads = mysqli_query( $connect, "select * from data.activity where author = '$user_session' and action = 'favorite'" );
  while ( $item = mysqli_fetch_array( $threads ) ) {
    $thread = dataArray( "threads", $item[ 'content' ], "id" );
    array_push_key_check( $tlist, $thread[ 'timestamp' ], $thread[ 'id' ] );
  }
} else if ( $type === "trending" ) {
  $threads = mysqli_query( $connect, "select * from data.threads where locale = '$user_loc' limit 3" );
  while ( $thread = mysqli_fetch_array( $threads ) ) {
    array_push_key_check( $tlist, $thread[ 'timestamp' ], $thread[ 'id' ] );
  }
} else if ( $type === "created" ) {
  $threads = mysqli_query( $connect, "select * from data.threads where author = '$user_session'" );
  while ( $thread = mysqli_fetch_array( $threads ) ) {
    array_push_key_check( $tlist, $thread[ 'timestamp' ], $thread[ 'id' ] );
  }
}
if ( $tlist ) {
  krsort( $tlist );
  foreach ( $tlist as $datetime => $id ) {
    unset( $s );
    unset( $nsfw );
    unset( $nsfw_tag );
    unset( $hasfaved );
    unset( $image );
    $thread = dataArray( "threads", $id, "id" );
    if ( $thread ) {
      if ( $thread[ 'status' ] !== "2" ) {
        $id = $thread[ 'id' ];
        $author = dataArray( "users", $thread[ 'author' ], "id" );
        $topic = dataArray( "topics", $thread[ 'topic' ], "id" );
        $post = dataArray( "posts", $thread[ 'op' ], "id" );
        $topic_name = $topic[ 'title' ];
        $topic_url = $topic[ 'url' ];
        $op = $thread[ 'op' ];
        $username = "@" . $author[ 'username' ];
        $title = $thread[ 'title' ];
        $timestamp = timeago( $thread[ 'timestamp' ], true );
        $tease = substr( $post[ 'content' ], 0, 70 ) . "...";
        if ( $post[ 'image' ] )$image = "data-background='/images/post/" . $post[ 'id' ] . "'";
        if ( $thread[ 'nsfw' ] === "1" ) {
          $nsfw = true;
          $nsfw_tag = "<span class='badge nsfw'>NSFW</span>";
          $title = languageFilter( $title, "strict", true );
          $tease = languageFilter( $tease, "strict", true );
          unset( $image );
        }
        if ( $type !== "trending" )$hasimage = " thread-item-image";
        if ( hasActivity( "favorite", $id ) )$hasfaved = " active-count-favorite";
        $count = numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.posts where thread = '$id' and id != '$op'" ) ) );
        $favorites = numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.activity where action = 'favorite' and content = '$id'" ) ) );
        if ( $count !== 1 )$s = "s";
        echo "<div class='thread-item$hasimage' data-action='thread' data-args='id=$id' data-url='/discuss/$topic_url/$id'>";
        if ( $hasimage )echo "<div class='thread-image' $image></div>";
        echo "<div class='thread-info'>";
        echo "<div class='thread-title'>$title$nsfw_tag</div>";
        //echo "<div class='thread-author'>$username</div>";
        if ( $type !== "trending" )echo "<div class='thread-tease'>$tease</div>";
        echo "<div class='thread-details'><div class='stat-count fav-count$hasfaved'>$favorites</div><div class='stat-count time-count'>$timestamp</div><div class='stat-count reply-count'>$count</div></div>";
        if ( $type !== "trending" && $thread[ 'tags' ] ) {
          $tags = explode( ",", $thread[ 'tags' ] );
          echo "<div class='thread-tags'>";
          foreach ( $tags as $tag ) {
            echo "<div class='thread-tag'>$tag</div>";
          }
          echo "</div>";
        }
        echo "</div></div>";
      } else echo "<div class='post'><div class='post-error'>" . t( 'removed-guidelines' ) . "</div></div>";
    } else echo "<div class='post'><div class='post-error'>" . t( 'thread-missing' ) . "</div></div>";
  }
} else {
  if ( $type === "favorites" )$message = t( 'save-prompt' ) . "<br/><button type='submit' data-action='discuss'>" . t( 'Discover Threads' ) . "</button>";
  else if ( $type === "created" )$message = t( 'create-prompt' ) . "<br/><button type='submit' data-action='create-thread'>" . t( 'Create Public Thread' ) . "</button>";
  else $message = "Sorry, no threads exist here.";
  echo "<div class='empty-state-message'><h3>" . t( 'No Threads' ) . "</h3><p>$message</p></div>";
}