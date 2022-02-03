<?php
$title = escape( $_POST[ 'title' ] );
$topic = $_POST[ 'topic' ];
$content = escape( $_POST[ 'content' ] );
if ( $_POST[ 'tags' ] ) {
  $tags = explode( ",", $_POST[ 'tags' ] );
  foreach ( $tags as $tag ) {
    array_push( $final_tags, cleanCase( $_POST[ 'tags' ] ) );
  }
  $tags = "'" . implode( ",", $final_tags ) . "'";
} else $tags = "NULL";
if ( $_POST[ 'nsfw' ] === "on" )$nsfw = "1";
else $nsfw = "0";
$country = $user_array[ 'country' ];
$check = mysqli_query( $connect, "select * from data.posts where content = '$content' and author = '$user_session'" );
if ( $user_array[ 'status' ] !== "1" ) {
  if ( $user_array[ 'status' ] !== "2" ) {
    if ( $content ) {
      if ( mysqli_num_rows( $check ) === 0 ) {
        $id = randString( 20 );
        if ( mysqli_query( $connect, "insert into data.threads (id,author,op,title,topic,tags,nsfw,locale) values ('$id','$user_session','$post_id','" . $title . "','$topic',$tags,'$nsfw','$country')" ) ) {
          $thread_id = $id;
          $id = randString( 20 );
          if ( mysqli_query( $connect, "insert into data.posts (id,author,content,thread) values ('$id','$user_session','" . $content . "','$thread_id')" ) ) {
            $post_id = $id;
            if ( mysqli_query( $connect, "update data.threads set op = '$post_id' where id = '$thread_id'" ) ) {
              echo json_encode( array( "id" => $thread_id ) );
              if ( languageFilter( $content, "verystrict" ) || languageFilter( $content, "banned" ) )createFlag( "threads", $post_id ); // Flag the post to moderation team}
            } else echo json_response( "error", "Something went wrong creating this thread." );
          } else echo json_response( "error", "Something went wrong creating this thread. $nsfw " . mysqli_error( $connect ) );
        } else echo json_response( "error", "Deja vu! You already posted this..." );
      } else echo json_response( "error", "You forgot to add a description." );
    } else echo json_response( "error", "Your account has been terminated." );
  } else echo json_response( "error", "Your account has either been suspended." );