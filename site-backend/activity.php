<?php
$activity = mysqli_query( $connect, "select * from data.activity where content = '$user_session' or target = '$user_session' and author != '$user_session' order by timestamp desc limit 20" );
if ( mysqli_num_rows( $activity ) !== 0 ) {
  while ( $item = mysqli_fetch_array( $activity ) ) {
    unset( $actions );
    unset( $hasactions );
    unset( $tease );
    unset( $linkitem );
    unset( $newitem );
    unset( $post_id );
    unset( $username );
    unset( $desc );
    $exists = true;
    $id = $item[ 'id' ];
    $action = $item[ 'action' ];
    $content = $item[ 'content' ];
    $target = $item[ 'target' ];
    $author = dataArray( "users", $item[ 'author' ], "id" );
    $author_id = $author[ 'id' ];
    $timestamp = timeago( $item[ 'timestamp' ], true );
    $username = $author[ 'username' ];
    if ( $item[ 'new' ] === "1" ) {
      $newitem = "activity-new";
      mysqli_query( $connect, "update data.activity set new = '0' where id = '$id'" );
    }
    if ( !blacklist( false, $user_session, $item[ 'author' ] ) ) {
      if ( $action === "follow" ) {
        if ( $content === $user_session )$desc = "<a href='javascript:;' data-action='profile' data-args='user=$username' data-url='/@$username'>@$username</a> followed you.";
        $actions = followB( $author[ 'id' ] );
      }
      if ( $action === "favorite" ) {
        $thread = dataArray( "threads", $content, "id" );
        if ( $thread ) {
          $linkitem = "data-action='thread' data-args='id=$content' data-url='/thread/$content'";
          $desc = "<a href='javascript:;' data-action='profile' data-args='user=$username' data-url='/@$username'>@$username</a> favorited your thread <a href='javascript:;' data-action='thread' data-args='id=$content' data-url='/thread/$content'>" . titleCase( $thread[ 'title' ] ) . "</a>.";
        } else $exist = false;
      }
      if ( $action === "request" ) {
        if ( $content === $user_session )$desc = "<a href='javascript:;' data-action='profile' data-args='user=$username' data-url='/@$username'>@$username</a> requested to follow you.";
        $actions = "<button type='submit' class='accept-btn' data-allow='true' data-id='$id'>" . t( 'Accept' ) . "</button><button type='submit' class='grey-btn decline-btn' data-id='$id'>" . t( 'Decline' ) . "</button>";
      }
      if ( $action === "like" || $action === "reply" || $action === "repost" || $action === "mention" ) {
        if ( $action === "reply" )$actioned = "replied to your";
        if ( $action === "like" )$actioned = "liked your";
        if ( $action === "repost" )$actioned = "reposted your";
        if ( $action === "mention" )$actioned = "mentioned you in a";
        $actioned = t( $actioned );
        $post = dataArray( "posts", $item[ 'content' ], "id" );
        if ( $post ) {
          if ( strlen( $post[ 'content' ] ) > 250 )$elip = "...";
          $tease = substr( $post[ 'content' ], 0, 250 ) . $elip;
          $thread = dataArray( "threads", $item[ 'content' ], "op" );
          if ( $thread )$what = "thread";
          else $what = "post";
          if ( $target === $user_session )$desc = "<a href='javascript:;' data-action='profile' data-args='user=$username' data-url='/@$username'>@$username</a> $actioned $what.";
          $linkitem = "data-action='post' data-args='id=$content' data-url='/@$username/post/$content'";
        } else $exist = false;
      }
      if ( $actions )$hasactions = "<div class='activity-actions'>$actions</div>";
      if ( $actions && !$ismobile ) {
        $timestamp = timeago( $item[ 'timestamp' ], true, true );
        if ( $tease )$tease .= " &bull; $timestamp";
        else $tease = $timestamp;
        unset( $timestamp );
      }
      if ( $desc && $exists === true )echo "<div class='activity-item $newitem activity-$action' $linkitem><div class='activity-details'><div class='activity-title'><div class='activity-desc'>$desc</div><div class='activity-timestamp'>$timestamp</div></div><div class='activity-tease'>$tease</div></div>$hasactions</div>";
    }
  }
} else echo "<div class='empty-state-message'><h3>No activity&mdash;yet</h3><p>When someone else interacts with you, it shows up here.</p></div>";