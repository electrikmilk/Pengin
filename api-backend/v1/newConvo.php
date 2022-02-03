<?php
$list = $_POST[ 'users' ];
$users = array();
if ( $list ) {
  $examine = explode( ",", $list );
  foreach ( $examine as $id ) {
    $user = dataArray( "users", $id, "id" );
    $doesfollow = follows( $user_session, $user[ 'id' ] );
    if ( $user[ 'followmessage' ] === "2" && $doesfollow || $user[ 'canmessage' ] === "2" && !$doesfollow ) {
      // send invite or what have u
    } else if ( $item[ 'followmessage' ] === "1" && follows( $user_session, $item[ 'id' ] ) ) {
      // do nothing, I dunno how they got here
    } else array_push( $users, $user[ 'id' ] );
  }
  $users = implode( ",", $users );
  $check = mysqli_query( $connect, "select * from data.convos where author = '$user_session' and users = '$users'" );
  if ( mysqli_num_rows( $check ) === 0 ) {
    $id = randString( 20 );
    if ( mysqli_query( $connect, "insert into data.convos (id,author,users) values ('$id','$user_session','$users')" ) ) {
      // Create activity so if they are replying or reposting, they author is notified
      // if($action !== "newPost") {
      //   if($action)$create = mysqli_query($connect,"insert into data.activity (author,action,content,identifier,target) values ('$user_session','$action','$id','$target_id','$target')");
      //   createMentions($content,$id);
      // }
      echo json_response( "success", $id );
    } else echo json_response( "error", "Something went wrong creating this conversation." );
  } else echo json_response( "error", "Deja vu! You already created a conversation with these people." );
} else echo json_response( "error", "You forgot to add users." );