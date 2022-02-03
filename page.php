<?php
require( "global.php" );
$action = $_POST[ 'action' ];
if ( file_get_contents( "content/$action.php" ) ) include( "content/$action.php" );
else echo "Error, page not found for ($action).";