<?php
$id = $_REQUEST[ 'id' ];
$thread = dataArray( "threads", $id, "id" );
if ( $thread[ 'status' ] === "2" ) {
  if ( $thread[ 'author' ] === $user_session )$showthread = true;
  else $showthread = false;
} else $showthread = true;
if ( $showthread === true ) {
  if ( $thread )$topic = dataArray( "topics", $thread[ 'topic' ], "id" );
  if ( $thread[ 'author' ] === $user_session )$canfav = "disabled";
  if ( hasActivity( "favorite", $id ) )$hasfaved = " active-favorite";
  if ( $thread[ 'nsfw' ] && !$_COOKIE[ "allow_$id" ] ) {
    ?>
<script>
$(function () {
  modals('nsfw');
});
</script>
<?php
}
if ( $thread[ 'status' ] !== "2" ) {
  ?>
<script>
$(function () {
  content("#thread-replies","feed","type=thread&thread=<?php echo $id; ?>&weeks=1");
  content_id = '<?php echo $id; ?>';
});
</script>
<?php
}
if ( $thread ) {
  if ( $thread[ 'status' ] === "1" )echo "<div class='message warning inline-message'><h3>This thread has been archived</h3><p>It is no longer public in the <b>" . $topic[ 'title' ] . "</b> topic or in the search. The OP, anyone who has favorited this thread and anyone who saved the link to it can still access it. Generally, this happens when a thread is old or has low activity (no one is liking posts in it or replying to it).</p></div>";
  if ( $thread[ 'status' ] === "2" )echo "<div class='message error inline-message'><h3>This thread has been banned</h3><p>This thread violates community guidelines and will never be seen on any screen other than ours and your own. We will be contacting you if nessesary.</p></div>";
  echo "<button type='submit' class='fav-btn icon-btn$hasfaved' data-activity='favorite' data-id='$id' data-author='" . $thread[ 'author' ] . "' $canfav>" . activityCount( "favorites", $id, true ) . "</button>";
  echo "<div class='back-link' data-action='threads' data-args='topic=" . $topic[ 'url' ] . "' data-url='/discuss/" . $topic[ 'url' ] . "'>" . $topic[ 'title' ] . " " . t( "Threads" ) . "</div>";
} else echo "<div class='back-link' data-action='discuss'>" . t( "Back to Topics" ) . "</div>";
?>
<div class="feed-container">
  <div class="posts-container">
    <?php
    if ( $thread ) {
      echo getPost( $thread[ 'op' ], false, true, true, true, true );
    } else echo "<center><br/><br/><h3>Thread Not Found</h3><p>Sorry, this thread was either deleted or never existed in the first place.</p><button type='submit' data-action='discuss'>" . t( "Back to Topics" ) . "</button></center>";
    if ( $thread ) {
      ?>
  </div>
</div>
<div class="threads-container new-post-container <?php echo $notallowed; ?>">
  <div class="new-post-grid">
    <div class="navigation-profile" data-background="/images/avatar/<?php echo $user_array['username']; ?>"></div>
    <textarea placeholder="Publically reply to this thread..." class="reply-btn" data-id="<?php echo $thread['op']; ?>" data-thread="<?php echo $id; ?>"></textarea>
  </div>
  <div class="privacy-message">This is a public discussion thread. Any replies you post in this thread will be seen by anyone who visits this thread, regardless of your privacy settings.</div>
</div>
<div class="feed-container">
  <div class="posts-container" id="thread-replies"></div>
</div>
<?php
}
} else echo "<center><br/><br/><h3>Thread Banned</h3><p>Sorry, this thread violated community guidelines</p><button type='submit' data-action='discuss'>" . t( "Back to Topics" ) . "</button></center>";
