<?php
if ( !$page )$active_home = "active-tab";
if ( $page === "discuss" || $page === "threads" || $page === "thread" )$active_discuss = "active-tab";
if ( $page === "favorites" )$active_favs = "active-tab";
if ( $page === "messages" )$active_messages = "active-tab";
if ( $page === "activity" )$active_activity = "active-tab";
$count = intVal( mysqli_num_rows( mysqli_query( $connect, "select * from data.activity where new = '1' and content = '$user_session' or target = '$user_session' and author != '$user_session' and new = '1'" ) ) );
if ( $count !== 0 )$new_activity = "new-activity";
?>
<div class="navigation-container">
  <?php
  if ( !$user_session && $_GET[ 'l' ] )echo "<div class='message success inline-message'>" . t( "logout-message" ) . "</div>";
  if ( $user_array[ 'status' ] === "1" ) {
    if ( $user_array[ 'suspend' ] )$until = " for " . str_replace( " ago", "", timeago( $user_array[ 'suspend' ], true, true ) ) . " (until " . date( "F j, Y", strtotime( $user_array[ 'suspend' ] ) ) . ")";
    echo "<div class='message warning inline-message'>Your account has been suspended$until. Until then, you cannot post or interact with others and their posts. If you believe we did this in error, please contact us at support@pengin.app.</div>";
  }
  if ( $user_array[ 'status' ] === "2" )echo "<div class='message error inline-message'>Your account has been terminated and will eventually be deleted. If you believe this is in error, please contact us at support@pengin.app.</div>";
  ?>
  <div class="navigation">
    <div class="site-progress"></div>
    <?php
    if ( $user_session ) {
      ?>
    <div class="navigation-logo" data-action="home"></div>
    <?php
    echo "<div class='navigation-btn nav-btn-home $active_home' data-action='home' tooltip='" . t( "Home" ) . "' data-placement='bottom'></div><div class='navigation-btn nav-btn-discuss $active_discuss' data-action='discuss' tooltip='" . t( "Discuss" ) . "' data-placement='bottom'></div>";
    echo "<div class='navigation-tabs'><ul><li class='home-tab $active_home'  data-action='home'>" . t( "Home" ) . "</li><li class='discuss-tab $active_discuss'  data-action='discuss'>" . t( "Discuss" ) . "</li></ul></div>";
    include( "quick-search.php" );
    if ( !$ismobile ) {
      ?>
    <div class="navigation-btn nav-btn-limit" data-modal="limit" tooltip="<?php echo t( "Time Limit" ); ?>" data-placement="bottom"></div>
    <?php
    }
    ?>
    <div class="navigation-btn nav-btn-favorites <?php echo $active_favs; ?>" data-action="favorites" tooltip="<?php echo t( "Saved Threads" ); ?>" data-placement="bottom"></div>
    <!-- <div class="navigation-btn nav-btn-messages <?php echo $active_messages; ?>" data-action="messages" tooltip="<?php echo t( "Direct Messages" ); ?>" data-placement="bottom"></div>-->
    <div class="navigation-btn nav-btn-activity  <?php echo $new_activity." ".$active_activity; ?>" data-action="activity" tooltip="<?php echo t( "Activity" ); ?>" data-placement="bottom"></div>
    <div class="navigation-profile" data-dropdown="profile-menu" data-item="<?php echo $user_session; ?>" data-background="/images/avatar/<?php echo $user_array['username']; ?>" tooltip="<?php echo t( "Account" ); ?>" data-placement="bottom"></div>
    <?php
    } else echo "<a href='/'><div class='navigation-logo'></div></a>";
    ?>
  </div>
</div>
