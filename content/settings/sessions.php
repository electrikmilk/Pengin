<?php
$devices = mysqli_query( $connect, "select * from data.devices where user = '$user_session'" );
?>
<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="content-block">
  <h2><?php echo t("Login Activity & Sessions"); ?></h2>
  <p><?php echo t("Keep track of what devices are logged into your account."); ?></p>
  <div class="privacy-message"><?php echo t("This is done to help secure your account and detect malicious attempts to login to your account."); ?></div>
</div>
<div class="session-list">
  <?php
  include_once "detect.php";
  while ( $device = mysqli_fetch_array( $devices ) ) {
    unset( $you );
    $ip = $device[ 'ip' ];
    $agent = getBrowser( $device[ 'agent' ] );
    $userinfo = userInfo( $device[ 'agent' ] );
    $os = $userinfo[ 'os' ];
    $split = explode( " ", $os );
    $osName = $split[ 0 ];
    $os = clean( $os );
    $browser = clean( $agent[ 'browser' ] );
    if ( strpos( $os, "macos" ) !== false || strpos( $os, "os-x" ) !== false ) {
      $split = explode( "-", $os );
      $os = $split[ 0 ];
      $osName = "Mac";
    }
    if ( $device[ 'session' ] === $session_id )$you = "<span class='badge'>This Device</span>";
    $last = timeago( $device[ 'updated' ], true, true );
    echo "<div class='session-item $os'><div class='session-title'>$osName$you</div><p class='input-context'>$ip &bull; $last</p></div>";
    //<div class='session-type-block $browser'>" . $agent[ 'fullbrowser' ] . " " . $agent[ 'version' ] . "</div>
  }
  ?>
</div>
