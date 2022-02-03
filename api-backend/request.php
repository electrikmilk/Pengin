<?php

// Pengin API
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Headers: access" );
header( "Access-Control-Allow-Methods: GET, POST" );
header( "Access-Control-Allow-Credentials: true" );
header( "Content-Type: application/json; charset=UTF-8" );

// Get global functions
require( "../global.php" );

function json_response( $status, $message ) {
  // if ( $status === "success" )http_response_code( 200 );
  // else http_response_code( 503 );
  $json = array( "status" => $status, "message" => $message );
  return json_encode( $json );
}

$version = $_GET[ 'version' ];
$action = $_GET[ 'action' ];
$token = $_POST[ 'token' ];

// If being accessed remotely by the app or by developers, a token is required
//$auth = true;
if ( $_SERVER[ 'SERVER_ADDR' ] === $_SERVER[ 'REMOTE_ADDR' ] ) {
  $remote = true;
  if ( $_POST[ 'key' ] ) {
    $auth = true;
  } else if ( !$token ) {
    echo json_response( "error", "No authentication token was received." );
    http_response_code( 401 );
    exit; // for good measure
  } else {
    $session = dataArray( "sessions", $token, "token" ); // get user_id from token
    if ( $session ) {
      $auth = true;
      $user_session = $session[ 'user_id' ];
    } else { // invalid token, return error
      $id = null;
      $auth = false;
      echo json_response( "error", "Invalid authentication token." );
      http_response_code( 401 );
      exit; // for good measure
    }
  }
} else $auth = true;

// Do request
if ( $auth === true ) include( "$version/$action.php" );