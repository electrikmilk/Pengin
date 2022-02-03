<?php
if ( $_POST ) {
  if ( $ismobile && $_POST[ 'action' ] )$action = $_POST[ 'action' ];
  else $action = $_POST[ 'main' ];
} else $action = $_GET[ 'page' ];
if ( $action === "messages" ) {
  // Gather users conversations
  $convos = array();
  $count = 0;
  $gather = mysqli_query( $connect, "select * from data.convos where users like '%$user_session%' or author = '$user_session'" );
  while ( $convo = mysqli_fetch_array( $gather ) ) {
    unset( $new );
    unset( $new_class );
    if ( strpos( $convo[ 'users' ], $user_session ) !== false || $convo[ 'author' ] === $user_session ) {
      // Get timestamp and id of most recent message
      $id = $convo[ 'id' ];
      $recent = mysqli_query( $connect, "select * from data.messages where convo = '$id' order by timestamp desc limit 1" );
      $new = intVal( mysqli_num_rows( mysqli_query( $connect, "select * from data.messages where new = '1' and convo = '$id' and author != '$user_session'" ) ) );
      $count = $count + $new;
      if ( mysqli_num_rows( $recent ) !== 0 ) {
        $message = mysqli_fetch_array( $recent );
        $this_id = $message[ 'id' ] . "_" . $id . "_" . $new;
        $timestamp = $message[ 'timestamp' ];
      } else { // Use conversation timestamp instead
        $timestamp = $convo[ 'timestamp' ];
        $this_id = $id;
      }
      $convos[ $timestamp ] = $this_id;
    }
  }
  if ( $count !== 0 ) {
    $full_class = " inbox-title-fill";
    $new_class = " class='new-msgs'";
  }
  $count = numberFormat( $count );
  ?>
<div class="inbox-title-container">
  <div class="inbox-title<?php echo $full_class; ?>"><?php echo t("Direct Messages"); ?> <span<?php echo $new_class; ?>><?php echo $count; ?></span></div>
  <div>
    <button type="submit" class="grey-btn icon-btn icon-only-btn new-btn alone-btn" tooltip="<?php echo t("Create New DM"); ?>" data-placement="bottom" data-modal="new-dm"></button>
  </div>
</div>
<div class="conversations">
<?php
// Output conversations
if ( $convos ) {
  krsort( $convos );
  foreach ( $convos as $datetime => $id ) {
    unset( $elip );
    unset( $isactive );
    unset( $hasnew );
    unset( $usernames );
    unset( $username );
    unset( $countnew );
    if ( strpos( $id, "_" ) !== false ) {
      $split = explode( "_", $id );
      $recent = $split[ 0 ];
      $convo_id = $split[ 1 ];
      $new = intVal( $split[ 2 ] );
    } else $convo_id = $id;
    $convo = dataArray( "convos", $convo_id, "id" );
    $recentm = dataArray( "messages", $recent, "id" );
    if ( $convo[ 'name' ] ) {
      $title = $convo[ 'name' ];
    } else {
      $usernames = array();
      $users = explode( ",", $convo[ 'users' ] );
      array_push( $users, $convo[ 'author' ] );
      $i = 0;
      foreach ( $users as $userid ) {
        if ( $userid !== $user_session ) {
          $user = dataArray( "users", $userid, "id" );
          if ( $i === 0 )$icon = "data-background='/images/avatar/" . $user[ 'username' ] . "'";
          if ( $user[ 'displayname' ] )$name = $user[ 'displayname' ];
          else $name = "@" . $user[ 'username' ];
          array_push( $usernames, $name );
          ++$i;
        }
      }
      $title = implode( ", ", $usernames );
    }
    if ( $recentm ) {
      $author = dataArray( "users", $recentm[ 'author' ], "id" );
      $username = $author[ 'username' ];
      $tease = "@$username: " . $recentm[ 'content' ];
      if ( strlen( $tease ) > 25 )$elip = "...";
      $tease = substr( $tease, 0, 25 ) . $elip;
    } else $tease = "...";
    $timestamp = timeago( $datetime, true );
    if ( $new !== 0 )$hasnew = " new-msgs";
    if ( $_REQUEST[ 'id' ] === $convo_id ) {
      $isactive = "active-convo";
      unset( $hasnew );
    }
    if ( $new !== 0 )$countnew = "(" . numberFormat( $new ) . ") ";
    echo "<div class='convo-item $hasnew $isactive' data-action='messages' data-args='id=$convo_id' data-url='/messages/$convo_id'>";
    echo "<div class='convo-image'><div class='navigation-profile' $icon></div></div>";
    echo "<div class='convo-details'><div class='convo-title'><div>$countnew$title</div><div class='convo-timestamp'>$timestamp</div></div>";
    echo "<div class='convo-tease'>$tease</div></div>";
    echo "</div>";
  }
} else {
  echo "<div class='empty-state-message'><p>" . t( 'No conversations yet.' ) . "</p></div>";
}
echo "</div>";
}
else {
  ?>
<div class="sticky-right">
  <div class="right-block">
    <h3><?php echo t("Recommended People"); ?></h3>
    <hr/>
    <?php
    // From their followers, suggest mutuals they don't follow
    $followers = followsArray( $user_session, false, 3, true );
    $mutuals = array();
    foreach ( $followers as $follower ) {
      $follows = followsArray( $follower, "following" );
      $random = array_random( $follows );
      if ( !follows( $user_session, $random ) && $random !== $user_session ) {
        $mutuals[ $random ] = $random;
      }
    }
    ksort( $mutuals );
    if ( count( $mutuals ) !== 0 ) {
      foreach ( $mutuals as $m ) {
        $user = dataArray( "users", $m, "id" );
        if ( $user[ 'locale' ] === $user_loc ) {
          $username = $user[ 'username' ];
          $name = $user[ 'displayname' ];
          if ( $user[ 'verified' ] === "1" )$verify = "<span class='verified'></span>";
          $action = "data-action='profile' data-args='user=$username' data-url='/@$username'";
          echo "<div class='post-header'>";
          echo "<div class='navigation-profile' data-background='/images/avatar/$username' $action></div>";
          echo "<div class='posts-author' $action><div class='post-author'>$name$verify</div><div class='post-detail'>@$username</div></div>";
          echo "<div>" . followB( $m, true ) . "</div>";
          echo "</div>";
        }
      }
    } else echo "<center><p>" . t( 'recommended-message' ) . "</p></center>";
    ?>
  </div>
  <br/>
  <div class="right-block">
    <h3><?php echo t("Trending Threads"); ?></h3>
    <hr/>
    <script>
  $(function () {
    content(".trending-container","threads","type=trending");
  });
  </script>
    <div class="thread-container trending-container"></div>
  </div>
  <br/>
  <!-- <div class="right-block">
  <h3>Trending Hashtags</h3><hr/>
  </div> -->
  <div class="footer-status language flag-<?php echo $user_lang; ?>" data-modal="set-lang"><?php echo $languages[$user_lang]; ?></div>
  <div class="footer-status country" data-action="settings/localize"><?php echo t($countries[$user_loc])." ($user_loc)"; ?></div>
  <div class="footer-status timezone" data-action="settings/localize">
    <?php
    $format = str_replace( "_", " ", $user_tz );
    $split = explode( "/", $format );
    if ( $split[ 2 ] )$user_timezone = $split[ 2 ] . ", " . $split[ 1 ] . ", " . $split[ 0 ];
    else $user_timezone = $split[ 1 ] . ", " . $split[ 0 ];
    echo t( $user_timezone );
    ?>
  </div>
  <div class="privacy-message"><a href='javascript:;' data-action='help/location'><?php echo t("What's this?"); ?></a></div>
  <div class="footer-links">
    <?php
    $links = array(
      "about/privacy" => t( "Privacy" ),
      "about/terms" => t( "Terms of Use" ),
      "about/cookies" => t( "Cookies" )
    );
    $format_links = array();
    foreach ( $links as $url => $label ) {
      array_push( $format_links, "<a href='javascript:;' data-action='$url'>$label</a>" );
    }
    echo implode( " &bull; ", $format_links );
    ?>
  </div>
  <div class="footer-container"> &copy; <?php echo date("Y"); ?> Pengin <?php echo t("All Rights Reserved."); ?> &bull; Icons made by Pengin, <a href="https://www.flaticon.com/authors/hirschwolf">hirschwolf</a> and Google. </div>
</div>
<?php
}
