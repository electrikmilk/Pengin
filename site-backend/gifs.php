<?php
$query = urlencode( $_POST[ 'query' ] );
$key = "UBP3rKFLRU0Wb36ZoOVVK0x9UTSyZkA4";
$url = "https://api.giphy.com/v1/gifs/search?q=$query&api_key=$key&limit=20";
$data = json_decode( file_get_contents( $url ), true );
$data = $data[ 'data' ];
foreach ( $data as $item ) {
  $url = $item[ 'url' ];
  if ( strpos( $url, "-" ) !== false )$imageID = end( explode( "-", $url ) );
  else $imageID = str_replace( "https://giphy.com/gifs/", "", $url );
  $image = "https://media.giphy.com/media/$imageID/giphy.gif";
  echo "<div class='gif-item' data-background='$image' data-gif='$image'></div>";
}
echo "<br/>";