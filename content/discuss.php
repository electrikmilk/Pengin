<div class="content-block">
  <h1><?php echo t("Discuss"); ?></h1>
  <p><?php echo t("discuss-description"); ?></p>
  <!-- <center><button type="submit" class="create-thread-btn grey-btn" data-action="suggest">Suggest Topic</button></center> --> 
  <br/>
  <center>
    <button type="submit" class="large-btn <?php echo $notallowed; ?>" data-action="create-thread"><?php echo t("Create Public Thread"); ?></button>
  </center>
</div>
<hr/>
<div class="topics">
  <?php

  function threadCount( $id ) {
    global $connect;
    global $user_loc;
    return mysqli_num_rows( mysqli_query( $connect, "select * from data.threads where locale = '$user_loc' and topic = '$id' and status = '0'" ) );
  }
  $topics = mysqli_query( $connect, "select * from data.topics order by title asc" );
  while ( $topic = mysqli_fetch_array( $topics ) ) {
    unset( $s );
    $title = t( $topic[ 'title' ] );
    $count = threadCount( $topic[ 'id' ] );
    $query = str_replace( "-", "+", $topic[ 'url' ] );
    $topColor = $topic[ 'color' ];
    $color = $topic[ 'color' ] . "30";
    $simple = $topic[ 'url' ];
    if ( $simple === "politics" ) {
      $image = "https://giphygifs.s3.amazonaws.com/media/3o7bu6ctZy3qGJyBkk/giphy.gif";
    } else {
      if ( $simple === "film" )$query = "movies";
      $key = "UBP3rKFLRU0Wb36ZoOVVK0x9UTSyZkA4";
      $url = "https://api.giphy.com/v1/gifs/search?q=$query&api_key=$key&limit=3";
      $data = json_decode( file_get_contents( $url ), true );
      $rand = rand( 0, 2 );
      $url = $data[ "data" ][ $rand ][ "url" ];
      if ( strpos( $url, "-" ) !== false )$imageID = end( explode( "-", $url ) );
      else $imageID = str_replace( "https://giphy.com/gifs/", "", $url );
      $image = "https://giphygifs.s3.amazonaws.com/media/$imageID/giphy.gif";
    }
    if ( $count !== 1 )$s = "s";
    echo "<div class='topic' style='background-image: linear-gradient(180deg, $topColor 0%, $color 100%), url($image);' data-action='threads' data-args='topic=$simple' data-url='/discuss/$simple'><div class='topic-title'>$title</div><div class='topic-detail'>$count active thread$s</div></div>";
  }

  ?>
</div>
