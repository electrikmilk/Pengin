<?php
$username = $user_array[ 'username' ];
$responses = array( "Wham-o! Your post was sent!", "Annnndddd it's outta here! Your post was sent!", "It's a bird, it's a plane, nope it's just @$username's post!", "And there goes your post! Sent!" );
$success_message = array_random( $responses );
if ( $_POST[ 'content' ] )$content = "'" . e( $_POST[ 'content' ] ) . "'";
else $content = "NULL";
if ( $_POST[ 'image' ] )$image = "'" . $_POST[ 'image' ] . "'";
else $image = "NULL";
if ( $_POST[ 'reply' ] ) {
  $reply = "'" . $_POST[ 'reply' ] . "'";
  $action = "reply";
  $target_id = $_POST[ 'reply' ];
} else $reply = "NULL";
if ( $_POST[ 'repost' ] ) {
  $repost = "'" . $_POST[ 'repost' ] . "'";
  $action = "repost";
  $target_id = $_POST[ 'repost' ];
} else $repost = "NULL";
if ( $_POST[ 'repost' ] || $_POST[ 'reply' ] ) {
  $target_post = dataArray( "posts", $target_id, "id" );
  $target = $target_post[ 'author' ];
}
if ( $_POST[ 'thread' ] )$thread = "'" . $_POST[ 'thread' ] . "'";
else $thread = "NULL";
if ( $_POST[ 'public' ] )$public = "'" . $_POST[ 'public' ] . "'";
else $public = "0";
$timestamp = date( "Y-m-d H:i:s" );
if ( $user_array[ 'status' ] !== "1" ) {
  if ( $user_array[ 'status' ] !== "2" ) {
    if ( $content || $image !== "NULL" ) {
      // Store hashtags used in post
      $hashtags = getHashtags( $_POST[ 'content' ] );
      foreach ( $hashtags as $htag ) {
        $tag = cleanCase( str_replace( "#", "", $htag ) );
        $check = mysqli_query( $connect, "select * from data.trending where tag = '$tag'" );
        if ( mysqli_num_rows( $check ) !== 0 ) {
          $thistag = mysqli_fetch_array( $check );
          $value = $thistag[ 'count' ];
          $value++;
          $query = mysqli_query( $connect, "update data.trending set count = '$value' where tag = '$tag'" );
        } else $query = mysqli_query( $connect, "insert into data.trending (tag,count) values ('$tag','1')" );
      }
      // If any urls in the post, store data on that url for easier loading later, or update data on a url if it already exists
      if ( $image === "NULL" ) {
        preg_match( '/https?\:\/\/[^\" ]+/i', $_POST[ 'content' ], $matches );
        $post_link = trim( $matches[ 0 ] );
        if ( strpos( $post_link, "youtube" ) !== false || strpos( $post_link, "youtu.be" ) !== false )$isyoutube = true;
        if ( $post_link ) {
          $info = linkArray( $post_link, true );
          $linktitle = e( htmlspecialchars( $info[ 'title' ] ) );
          $desc = $info[ 'description' ];
          if ( $isyoutube === true ) {
            if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $post_link, $match ) )$videoid = $match[ 1 ];
            else $videoid = str_replace( "https://youtu.be/", "", str_replace( "http://youtu.be/", "", $post_link ) );
            $linkimage = "https://img.youtube.com/vi/$videoid/hqdefault.jpg";
          } else {
            $linkimage = $info[ 'image' ];
            if ( !$linkimage )$linkimage = getScreenshot( $post_link );
          }
          if ( !$videoid )$videoid = "NULL";
          else $videoid = "'$videoid'";
          if ( $desc === "..." )$desc = "NULL";
          else $desc = "'" . e( htmlspecialchars( $desc ) ) . "'";
          $check = mysqli_query( $connect, "select * from data.urls where url = '$post_link'" );
          if ( mysqli_num_rows( $check ) !== 0 ) {
            $thisurl = mysqli_fetch_array( $check );
            $url_id = $thisurl[ 'id' ];
            $query = mysqli_query( $connect, "update data.urls set title = '$linktitle', description = $desc, image = '$linkimage', videoid = $videoid where id = '$url_id'" );
          } else $query = mysqli_query( $connect, "insert into data.urls (url,title,description,image,videoid) values ('$post_link','$linktitle',$desc,'$linkimage',$videoid)" );
        }
      }
      $check = 0;
      if ( $content !== "NULL" )$check = mysqli_query( $connect, "select * from data.posts where content = " . $content . " and author = '$user_session'" );
      if ( mysqli_num_rows( $check ) === 0 || !$_POST[ 'content' ] ) {
        $id = randString( 20 );
        if ( mysqli_query( $connect, "insert into data.posts (id,author,content,reply,repost,thread,image,public,timestamp) values ('$id','$user_session'," . $content . ",$reply,$repost,$thread,$image,$public,'$timestamp')" ) ) {
          // Create activity so if they are replying or reposting, they author is notified
          if ( $action !== "newPost" ) {
            if ( $action )$create = mysqli_query( $connect, "insert into data.activity (author,action,content,identifier,target) values ('$user_session','$action','$id','$target_id','$target')" );
            createMentions( $content, $id );
          }
          if ( languageFilter( $content, "verystrict" ) || languageFilter( $content, "banned" ) )createFlag( "post", $id ); // Flag the post to moderation team
          echo json_response( "success", $success_message );
        } else echo json_response( "error", "Something went wrong sending your post." );
      } else echo json_response( "error", "Deja vu! You already posted this..." );
    } else echo json_response( "error", "You forgot to add something to post." );
  } else echo json_response( "error", "Your account has been terminated." );
} else echo json_response( "error", "Your account has either been suspended." );