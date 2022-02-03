<?php
$id = $_POST[ 'id' ];
$type = $_POST[ 'type' ];
$reporter = $_POST[ 'reporter' ];
if ( $type && $id ) {
  if ( $type === "post" && $thread = dataArray( "threads", $id, "op" ) )$type = "thread";
  if ( $reporter )$query = "select * from data.flags where reporter = '$reporter' and content_id = '$id' and type = '$type'";
  else $query = "select * from data.flags where content_id = '$id' and type = '$type'";
  $check = mysqli_query( $connect, $query );
  if ( mysqli_num_rows( $check ) === 0 ) {
    if ( createFlag( $type, $id, $reporter ) )echo json_response( "success", "Thank you, we will investigate the reported content." );
    else echo json_response( "error", "An error occurred trying to report this content. You can contact our moderation team directly at mods@pengin.app." );
  } else echo json_response( "error", "You have already reported this content. Thank you." );
} else echo json_response( "error", "No type or content ID recieved." );