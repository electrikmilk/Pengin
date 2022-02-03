<?php
$id = $_REQUEST[ 'id' ];
if ( !$id ) {
  if ( !$ismobile )echo "<div class='conversation-container'>";
  if ( $ismobile ) include( "side.php" );
  else {
    ?>
<div class="empty-state-message">
  <h3><?php echo t("Select a conversation or create one"); ?></h3>
  <button type="submit" class="icon-btn compose-btn" data-modal="new-dm"><?php echo t("Create New DM"); ?></button>
</div>
<?php
}
} else {
  ?>
<script>
current_convo = '<?php echo $id; ?>';
</script>
<?php
if ( $ismobile && $id || !$ismobile )echo "<div class='conversation-container'>";
$convo = dataArray( "convos", $id, "id" );
$usernames = array();
$users = explode( ",", $convo[ 'users' ] );
foreach ( $users as $userid ) {
  $user = dataArray( "users", $userid, "id" );
  if ( $user[ 'displayname' ] )$name = $user[ 'displayname' ];
  else $name = "@" . $user[ 'username' ];
  array_push( $usernames, $name );
}
if ( $convo[ 'name' ] ) {
  $title = $convo[ 'name' ];
} else {
  $title = implode( ", ", $usernames );
}
foreach ( $usernames as $username ) {
  if ( count( $usernames ) === 1 )$list = " and $username";
  else {
    if ( empty( $list ) )$list = ", " . $username;
    else {
      if ( $username === end( $usernames ) )$list .= " and $username";
      else $list .= ", $username";
    }
  }
}
?>
<div class="convo-header">
  <div class="convo-header-title"><?php echo $title; ?></div>
  <div>
    <div class="post-more-btn" data-dropdown="convo-menu" data-item="<?php echo $id; ?>"></div>
  </div>
</div>
<div class="messages-container" data-convo="<?php echo $id; ?>">
  <div class="inline-loading">
    <div class="load"></div>
  </div>
</div>
<div class="message error" id="newmessage-error" style="display: none;"><?php echo t("send-message-error"); ?></div>
<div class="compose-container"> 
  <!--<div class="message-compose" contenteditable="true"></div>-->
  <div>
    <input type="text" id="message-content" placeholder="Type a message..." autocomplete="off" data-emoji="true" autofocus/>
  </div>
  <div>
    <button type="submit" class="icon-btn send-msg-btn" data-id="<?php echo $id; ?>" disabled><?php echo t("Send"); ?></button>
  </div>
</div>
<?php
if ( $ismobile && $id || !$ismobile )echo "</div>";
}
if ( !$ismobile )echo "</div>";
