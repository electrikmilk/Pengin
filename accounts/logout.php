<?php
require( "../database.php" );
// Delete database record of this particular session
mysqli_query( $connect, "delete from data.sessions where token = '$session_id'" );
// Delete Session Cookie
setcookie( 'session', '', time() - 3600, '/' );
header( "Location: /?l=true" );