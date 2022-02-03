<?php
$count = numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.activity where new = '1' and content = '$user_session' or target = '$user_session' and author != '$user_session' and new = '1'" ) ) );
if ( $count !== 1 )$s = "s";
if ( $count !== 0 )echo json_response( "new", "$count new notification$s for you." );
else echo json_response( "none", "No new activity." );