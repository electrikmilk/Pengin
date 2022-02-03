<?php
$user = dataArray( "users", $id, "id" );
echo json_encode( array(
  "id" => $user[ 'id' ],
  "username" => $user[ 'username' ],
  "display" => $user[ 'displayname' ]
) );