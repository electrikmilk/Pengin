<?php
require( "../global.php" );
// Give the frontend some localization data, this is a temporary solution, the frontend javascript should eventually just get the json localization file for the current language itself.
$localize = json_decode( file_get_contents( "$lang.json" ), true );
if ( $page )$current_page = $page;
else $current_page = "home";
if ( $ismobile )$ismobile = "true";
else $ismobile = "false";
header( "Content-Type: application/javascript" );
if ( $user_session )echo "// Localize\nvar emojis = {search: " . json_encode( t( 'Search Emojis...' ) ) . ",categories: {recents: " . json_encode( t( 'Recent Emojis' ) ) . ",smileys: " . json_encode( t( 'Smileys & People' ) ) . ",animals: " . json_encode( t( 'Animals & Nature' ) ) . ",food: " . json_encode( t( 'Food & Drink' ) ) . ",activities: " . json_encode( t( 'Activities' ) ) . ",travel: " . json_encode( t( 'Travel & Places' ) ) . ",objects: " . json_encode( t( 'Objects' ) ) . ",symbols: " . json_encode( t( 'Symbols' ) ) . ",flags: " . json_encode( t( 'Flags' ) ) . "},notFound: " . json_encode( t( 'No emojis found' ) ) . "};\n";
$vars = array(
  "lang" => $lang,
  "current_page" => $current_page,
  "current_folder" => $_POST[ 'folder' ],
  "ismobile" => $ismobile
);
foreach ( $vars as $var => $val ) {
  if ( $var !== "ismobile" )$val = "'$val'";
  echo "var $var = $val;\n";
}