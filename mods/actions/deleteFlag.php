<?php
$id = $_POST[ 'id' ];
if ( mysqli_query( $connect, "select * from data.flags where id = '$id'" ) )echo "deleted";
else echo "error: " . mysqli_error( $connect );