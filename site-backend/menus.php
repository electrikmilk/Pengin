<?php
$type = $_POST[ 'type' ];
$id = $_POST[ 'id' ];
if ( $type === "post" ) {
  $post = dataArray( "posts", $id, "id" );
  $thread = dataArray( "threads", $post[ 'thread' ], "id" );
  $user = dataArray( "users", $post[ 'author' ], "id" );
  $username = $user[ 'username' ];
  if ( $thread ) {
    $isthread = true;
    $type = "Thread";
    $thread_url = "https://pengin.app/thread/{$thread[ 'id' ]}";
    $post_url = "https://pengin.app/@$username/post/$id";
  } else {
    $type = "Post";
    $post_url = "https://pengin.app/@$username/post/$id";
  }
  if ( $post[ 'author' ] === $user_session ) {
    ?>
<ul class="owner-opts">
  <?php if($post['original'] || $post['edited'])$edit_class = " disablethis"; ?>
  <li class="edit-opt edit-post<?php echo $edit_class; ?> <?php echo $notallowed; ?>" data-edit="<?php echo $id; ?>"><?php echo t("Edit Post"); ?><span><?php echo t("edit-description"); ?></span></li>
  <li class="delete-opt delete-post <?php echo $notallowed; ?>" data-delete="<?php echo $id; ?>"><?php echo t("Delete Post"); ?><span><?php echo t("delete-description"); ?></span></li>
  <div class="dropdown-divider"></div>
  <?php
  if ( !$post[ 'thread' ] ) {
    if ( $post[ 'pinned' ] )echo "<li class='pin-opt pin-post $notallowed' data-id='$id'>" . t( 'Unpin from your profile' ) . "<span>" . t( 'unpin-description' ) . "</span></li>";
    else echo "<li class='pin-opt pin-post $notallowed' data-id='$id'>" . t( 'Pin to your profile' ) . "<span>" . t( 'pin-description' ) . "</span></li>";
  }
  ?>
</ul>
<?php
}
?>
<ul>
  <?php
  if ( $thread )$post_desc = t( "public-sharing-description" );
  else {
    if ( $post[ 'public' ] === "0" ) {
      if ( $post[ 'author' ] === $user_session )$post_desc = t( "sharing-your-followers" );
      else $post_desc = t( "Only" ) . " @$username's " . t( "sharing-their-followers" );
    } else $post_desc = t( "Copy the link to this post for sharing." );
  }
  if ( $thread )echo "<li class='edit-post-btn link-opt' data-copy='$post_url'>" . t( 'Copy Thread Link' ) . "<span>" . t( 'copy-thread-link' ) . "</li>";
  echo "<li class='edit-post-btn link-opt' data-copy='$post_url'>" . t( 'Copy Post Link' ) . "<span>$post_desc</li>";
  // Non-author options
  if ( $post[ 'author' ] !== $user_session ) {
    if ( follows( $user_session, $post[ 'author' ] ) )$isfollowing = true;
    $user_id = $post[ 'author' ];
    if ( $isfollowing )echo "<li class='unfollow-opt $notallowed' data-activity='follow' data-id='$user_id'>" . t( 'Unfollow' ) . " @$username<span>" . t( 'unfollow-description' ) . "</span></li>";
    ?>
  <li class="privacy-opt" data-action="settings/privacy" data-url="/settings/privacy"><?php echo t("Privacy Settings"); ?><span><?php echo t("privacy-desc"); ?></span></li>
  <li class="block-opt <?php echo $notallowed; ?>" data-dropdown="block-menu" data-item="<?php echo $post['author']; ?>"><?php echo t("Limit Interactions with"); ?> @<?php echo $username; ?><span><?php echo t("limit-description"); ?></span></li>
  <li class="report-opt <?php echo $notallowed; ?>" data-id="<?php echo $post['id']; ?>" data-type="post" data-reporter="<?php echo $user_array['id']; ?>"><?php echo t("Report"); ?> <?php echo $type;?><span><?php echo t("report-description"); ?></span></li>
  <?php
  }
  ?>
</ul>
<?php
}
if ( $type === "profile" ) {
  echo "<ul>";
  $user = dataArray( "users", $id, "id" );
  $username = $user[ 'username' ];
  if ( $id === $user_session ) {
    $whos = "your";
    ?>
<li class="user-opt" data-action="profile" data-args="user=<?php echo $user_array['username']; ?>" data-url="/@<?php echo $user_array['username']; ?>"><?php echo t("Your Profile"); ?><span><?php echo t("Go to your profile page."); ?></span></li>
<li class="privacy-opt" data-action="settings/privacy"><?php echo t("Privacy Settings"); ?><span><?php echo t("privacy-desc"); ?></span></li>
<li class="settings-opt" data-action="settings/main"><?php echo t("Settings"); ?><span><?php echo t("settings-description"); ?></span></li>
<li class="threads-opt" data-action="created"><?php echo t("Your Threads"); ?><span><?php echo t("your-threads-desc"); ?></span></li>
<li class="edit-post-btn link-opt" data-copy="<?php echo "https://pengin.app/@$username"; ?>"><?php echo t("Copy Profile Link"); ?><span><?php echo t("Copy the link to"); ?> <?php echo $whos; ?> <?php echo t("profile"); ?>.</li>
<div class="dropdown-divider"></div>
<li class="more-opt" data-dropdown="more-menu"><?php echo t("More"); ?></li>
<?php
} else $whos = "@$username's";
if ( $id !== $user_session ) {
  if ( blacklist( "silence", $user_session, $id ) )echo "<li class='silence-opt' data-blacklist='silence' data-target='$id'>" . t( 'Stop Silencing' ) . " @$username<span>" . t( 'This will resume notifications from' ) . " @$username. " . t( "They'll also be back in your activity feed." ) . "</span></li>";
  if ( blacklist( "block", $user_session, $id ) )echo "<li class='block-opt' data-blacklist='block' data-target='$id'>" . t( 'Stop Blocking' ) . " @$username<span>" . t( 'stop-block-description' ) . "</span></li>";
  if ( !blacklist( "block", $user_session, $id ) && follows( $user_session, $id ) )echo "<li class='unfollow-opt' data-activity='follow' data-id='$id'>" . t( 'Unfollow' ) . " @$username<span>" . t( 'unfollow-description' ) . "</span></li>";
  if ( !blacklist( "block", $user_session, $id ) ) {
    ?>
<li class="block-opt" data-dropdown="block-menu" data-item="<?php echo $id; ?>"><?php echo t('Limit Interactions with'); ?> @<?php echo $username; ?><span><?php echo t('limit-description'); ?></span></li>
<?php
}
?>
<li class="report-opt" data-id="<?php echo $user['id']; ?>" data-type="user" data-reporter="<?php echo $user_array['id']; ?>"><?php echo t("Report")." @$username"; ?><span><?php echo t("report-user-description"); ?></span></li>
<li class="edit-post-btn link-opt" data-copy="<?php echo "https://pengin.app/@$username" ?>"><?php echo t("Copy Profile Link"); ?><span> <?php echo t("Copy the link to")." ".$whos." ".t("profile"); ?>.</li>
<?php
}
?>
</ul>
<?php
}
if ( $type === "block" ) {
  $user = dataArray( "users", $id, "id" );
  $username = $user[ 'username' ];
  if ( follows( $id, $user_session ) )$stop_follow = "<li class='unfollow-opt' data-remove='follow' data-id='$id'>" . t( 'Remove' ) . " @$username " . t( 'from your followers' ) . "<span>" . t( 'remove-follow-description' ) . "</span></li>";
  if ( blacklist( "silence", $user_session, $id ) )$silence = t( 'Stop Silencing' ) . " @$username<span>" . t( 'This will resume notifications from' ) . " @$username. " . t( "They'll also be back in your activity feed." ) . "</span>";
  else $silence = t( 'Silence' ) . " @$username<span>" . t( 'silence-description' ) . "</span>";
  if ( blacklist( "block", $user_session, $id ) )$block = t( 'Stop Blocking' ) . " @$username<span>" . t( 'stop-block-description' ) . "</span>";
  else $block = t( 'Block' ) . " @$username<span>" . t( 'block-description' ) . "</span>";
  ?>
<ul>
  <?php
  if ( !blacklist( "block", $user_session, $id ) ) {
    ?>
  <li class="silence-opt" data-blacklist="silence" data-target="<?php echo $id; ?>"><?php echo $silence; ?></li>
  <?php
  }
  echo $stop_follow;
  ?>
  <li class="block-opt" data-blacklist="block" data-target="<?php echo $id; ?>"><?php echo $block; ?></li>
</ul>
<?php
}
if ( $type === "convo" ) {
  $convo = dataArray( "convo", $id, "id" );
  $users = explode( ",", $convo[ 'users' ] );
  if ( count( $users ) === 1 )$user = dataArray( "users", $convo[ 'author' ], "id" );
  ?>
<ul>
  <li class="follow-opt">Invite Someone<span>Invite someone to this conversation.</span></li>
  <li class="silence-opt">Mute Notifications<span>Stop recieving notifications for this conversation.</span></li>
  <li class="block-opt" data-action="messages">Close Conversation<span>Close this conversation, you'll still recieve notifications.</span></li>
</ul>
<?php
}
