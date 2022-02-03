<?php

require( "../global.php" );

function correctImage( $filename ) {
  $exif = exif_read_data( $filename );
  if ( !empty( $exif[ 'Orientation' ] ) ) {
    $image = imagecreatefromjpeg( $filename );
    switch ( $exif[ 'Orientation' ] ) {
      case 3:
        $image = imagerotate( $image, 180, 0 );
        break;
      case 6:
        $image = imagerotate( $image, -90, 0 );
        break;
      case 8:
        $image = imagerotate( $image, 90, 0 );
        break;
    }
    imagejpeg( $image, $filename, 90 );
  }
}

$action = $_POST[ 'action' ];
if ( $action === "posts" || $action === "avatars" ) {
  if ( $action === "avatars" ) {
    // Delete old avatar
    if ( strpos( $user_array[ 'image' ], "." ) !== false ) {
      $user_id = $user_array[ 'id' ];
      $split = explode( "/", $user_array[ 'image' ] );
      deleteDir( "avatars/$user_id/" . $split[ 0 ] );
    }
  }
  $folder = $user_array[ 'id' ];
  $photo_id = randString( 10 );
  $filename = $_FILES[ 'file' ][ 'name' ]; // Getting file name
  $filesize = $_FILES[ 'file' ][ 'size' ]; // Getting File size
  makeFolder( "$action/$folder" ); // Location
  makeFolder( "$action/$folder/$photo_id" );
  $location = "$action/$folder/$photo_id/$filename";
  $return_arr = array();
  $src;
  if ( move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], $location ) ) { // Upload file
    // checking file is image or not
    if ( getimagesize( $location ) > 500000 ) {
      $ext = pathinfo( $filename, PATHINFO_EXTENSION );
      $allowed = array( "jpg", "jpeg", "png", "gif" );
      if ( in_arrayi( $ext, $allowed ) ) {
        rename( $location, "$action/$folder/$photo_id/original.$ext" );
        $location = "$action/$folder/$photo_id/original.$ext";
        correctImage( $location );
        if ( stripos( $ext, "jpg" ) !== false || stripos( $ext, "JPG" ) !== false || stripos( $ext, "jpeg" ) !== false || stripos( $ext, "JPEG" ) !== false || stripos( $ext, "png" ) !== false || stripos( $ext, "PNG" ) !== false ) {
          // make low res version in the original format
          $opt = "$action/$folder/$photo_id/small.$ext"; //This is the new file you saving
          list( $width, $height ) = getimagesize( $location );
          $modwidth = floor( $width * 2 ) / 2;
          $diff = $width / $height;
          $modheight = floor( $height * 2 ) / 2;
          $tn = imagecreatetruecolor( $modwidth, $modheight );
          if ( stripos( $ext, "jpg" ) !== false || stripos( $ext, "JPG" ) !== false || stripos( $ext, "jpeg" ) !== false || stripos( $ext, "JPEG" ) !== false ) {
            $image = imagecreatefromjpeg( $location );
          } else {
            imagealphablending( $tn, false );
            imagesavealpha( $tn, true );
            $transparency = imagecolorallocatealpha( $tn, 255, 255, 255, 127 );
            imagefilledrectangle( $tn, 0, 0, $modwidth, $modheight, $transparency );
            $image = imagecreatefrompng( $location );
          }
          imagecopyresampled( $tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height );
          if ( stripos( $ext, "jpg" ) !== false || stripos( $ext, "JPG" ) !== false || stripos( $ext, "jpeg" ) !== false || stripos( $ext, "JPEG" ) !== false ) {
            imagejpeg( $tn, $opt, 50 );
          } else {
            copy( "$action/$folder/$photo_id/original.$ext", "$action/$folder/$photo_id/small.$ext" ); // imagepng( $tn, $opt, 75 );
          } // make webp version for chrome
          $save = "$action/$folder/$photo_id/image.webp"; //This is the new file you saving
          $modwidth = floor( $width * 2 ) / 2;
          $diff = $width / $height;
          $modheight = floor( $height * 2 ) / 2;
          $tn = imagecreatetruecolor( $modwidth, $modheight );
          imagecopyresampled( $tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height );
          imagewebp( $tn, $save, 70 );
          //$save = "$action/$folder/$photo_id/small.$ext";
          //$src = "/img/$save";
          //$src = "$photo_id/small.$ext";
          if ( $action === "posts" )$src = "$folder/";
          $src .= "$photo_id/small.$ext";
        } else {
          copy( $location, str_replace( "original.$ext", "image.$ext", $location ) );
          //$src = "/img/$action/$folder/$photo_id/image.$ext";
          if ( $action === "posts" )$src = "$folder/";
          $src .= "$photo_id/image.$ext";
        }
        echo $src;
      } else echo "badext";
    } else {
      unlink( $location );
      echo "toolarge";
    }
  } else echo "error";
}

if ( $action === "temp" ) { // quick upload for temporary files
  $folder = randString( 20 ) . "-" . uniqid();
  $filename = $_FILES[ 'file' ][ 'name' ];
  makeFolder( "temp/$folder" );
  $location = "temp/$folder/$filename";
  if ( move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], $location ) ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    rename( "temp/$folder/$filename", "temp/$folder/image.$ext" );
    echo "/img/temp/$folder/image.$ext";
  } else echo "error";
}

if ( $action === "snapshot" ) { // feedback snapshots
  $type = $_POST[ 'type' ];
  $folder = randString( 20 );
  $id = randString( 10 );
  $snapfile = "$type-snapshot-$id";
  makeFolder( "snapshots/$folder" );
  $imgData = str_replace( ' ', '+', $_POST[ 'data' ] );
  $imgData = substr( $imgData, strpos( $imgData, "," ) + 1 );
  $imgData = base64_decode( $imgData );
  $path = "/img/snapshots/$folder/$snapfile.jpg";
  $filePath = $_SERVER[ 'DOCUMENT_ROOT' ] . "$path";
  $file = fopen( $filePath, 'w' );
  fwrite( $file, $imgData );
  fclose( $file );
  echo $path;
}