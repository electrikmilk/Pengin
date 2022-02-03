<?php
$id = $_POST[ 'id' ];
$convo = $_POST[ 'convo' ];
$message = getMessage( $id, $convo );
if ( $message )echo $message;