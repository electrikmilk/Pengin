<?php
$profile = dataArray( "users", $_REQUEST[ 'user' ], "username" );
$username = $profile[ 'username' ];
if ( $profile ) {
  if ( blacklist( "block", $profile[ 'id' ], $user_session ) === false ) {
    $privated = true;
    $id = $profile[ 'id' ];
    $icon = " data-background='/images/avatar/" . $profile[ 'username' ] . "/large'"; // Icon
    //$header = " data-background='/images/header/" . $profile[ 'username' ] . "/large'"; // Header
    if ( $profile[ 'id' ] === $user_session || follows( $user_session, $profile[ 'id' ] ) === true )$privated = false; // If user or if following
    if ( $profile[ 'bio' ] )$bio = nl2br( linkify( $Parsedown->line( htmlspecialchars_decode( filter( $profile[ 'bio' ] ) ) ) ) );
    if ( $profile[ 'location' ] )$location = $profile[ 'location' ];
    if ( $profile[ 'joined' ] )$joined = t( 'Joined' ) . " " . timeago( $profile[ 'joined' ], true, true );
    if ( $profile[ 'birthday' ] )$birthday = strftime( "%b %e", strtotime( $profile[ 'birthday' ] ) );
    if ( $profile[ 'showyear' ] !== "1" ) {
      if ( $profile[ 'showyear' ] === "0" || ( $profile[ 'showyear' ] === "2" && $privated === false ) || $privated === false ) {
        $birthday .= ", " . strftime( "%Y", strtotime( $profile[ 'birthday' ] ) );
        if ( $profile[ 'showyear' ] === "2" ) {
          if ( $id === $user_session )$birthday .= "<p class='input-context'>" . t( 'only-monthday-message' ) . "</p>";
        }
      }
    }
    if ( $profile[ 'link' ] )$link = $profile[ 'link' ];
    if ( $profile[ 'verified' ] === "1" )$verified = "<span class='verified'></span>";
    if ( $ismobile )$small = true;
    else $small = false;
    ?>
<!--<div class="profile-header" <?php echo $header; ?>></div>-->
<div class="profile-contents">
  <div class="profile-info"><?php echo "<div class='post-more-btn' data-dropdown='profile-menu' data-item='$id'></div>".followB($id,$small); // .messageB($id,$small) ?>
    <div class="profile-grid">
      <?php
      if ( $user_session !== $id ) {
        ?>
      <div class="profile-icon" <?php echo $icon; ?>></div>
      <?php
      } else {
        ?>
      <div class="profile-icon <?php echo $notallowed; ?>" <?php echo $icon; ?>>
        <div class="edit-profile">
          <div class="edit-profile-action"><?php echo t("Edit"); ?></div>
        </div>
      </div>
      <input type="file" id="avatarFile" style="display: none;"/>
      <div class="upload-photo" style="display: none;">
        <div class="progress-text"><?php echo t("Uploading..."); ?></div>
        <div class="progress">
          <div class="indeterminate"></div>
          <div class="determinate" id="progressbar" style="display: none;width: 0%;"></div>
        </div>
      </div>
      <input type="hidden" id="edit-val-image" value="<?php echo $user_array['image']; ?>"/>
      <?php
      }
      ?>
      <div class="profile-names">
        <div class="profile-name"><?php echo $profile['displayname']."$verified"; ?></div>
        <div class="profile-detail">@<?php echo $profile['username']; ?></div>
      </div>
    </div>
    <div class="profile-details">
      <?php if($privated === false || strpos($profile['showdetails'],"bio") !== false)echo "<div class='profile-bio'>$bio</div>"; ?>
      <div class="profile-item profile-link"><a href="<?php echo $link; ?>" target="_blank" rel="noopener"><?php echo $link; ?></a></div>
      <?php if($privated === false || strpos($profile['showdetails'],"location") !== false)echo "<div class='profile-item profile-location'>$location</div>"; ?>
      <?php if($privated === false || strpos($profile['showdetails'],"joined") !== false)echo "<div class='profile-item profile-joined'>$joined</div>"; ?>
      <?php if($privated === false || strpos($profile['showdetails'],"birthday") !== false)echo "<div class='profile-item profile-birthday'>$birthday</div>"; ?>
    </div>
    <div class="profile-counts">
      <?php
      if ( $privated === false || strpos( $profile[ 'showdetails' ], "followers" ) !== false ) {
        $count = activityCount( "followers", $profile[ 'id' ] );
        if ( $count != 1 && stripos( $user_lang, "en" ) !== false )$s = "s";
        echo "<div class='profile-count' $followers_action><div class='profile-count-number'>$count</div><div class='profile-count-label'>" . t( 'Follower' ) . "$s</div></div>";
      }
      if ( $privated === false || strpos( $profile[ 'showdetails' ], "following" ) !== false ) {
        $count = activityCount( "following", $profile[ 'id' ] );
        echo "<div class='profile-count' $following_action><div class='profile-count-number'>$count</div><div class='profile-count-label'>" . t( 'Following' ) . "</div></div>";
      }
      ?>
    </div>
  </div>
</div>
<?php
if ( $privated === false || $profile[ 'private' ] === "1" ) {
  ?>
<script>
var weeks = 1;
$(function () {
  content(".posts-container","feed","type=profile&user=<?php echo $profile['id']; ?>&weeks=1");
});
</script>
<div class="feed-container">
  <div class="posts-container"></div>
</div>
<?php
} else echo "<center><br/><br/><br/><h3>" . t( 'Followers Only Feed' ) . "</h3><p>This person prefers to keep the posts on their<br/>profile visible to their followers only.<br/><br/>Follow them to see their posts!</p></center>";
}
else echo "<center><br/><br/><br/><h3>@$username " . t( 'has blocked you' ) . "</h3><p>You'll no longer be able to see their posts or interact with them.</p><button type='submit' data-action='home'>Back to Home</button></center></center>";
}
else echo "<center><br/><br/><br/><h3>" . t( 'That user does not exist' ) . "</h3><p>Either someone has gone missing or they left on purpose...<br/>...or maybe they never existed at all?</p><button type='submit' data-action='home'>Back to Home</button></center></center>";
