<div class="back-link" onclick="history.back();"><?php echo t("Back"); ?></div>
<div class="posts">
  <?php
  $id = $_REQUEST[ 'id' ];
  $post = dataArray( "posts", $id, "id" );
  if ( $post[ 'thread' ] )echo "<div class='message warning inline-message'>Keep in mind, this post and replies to this post are part of a public thread. Replies you make to this post (or posts replying this post) will not be private regardless of your privacy settings.</div>";
  if ( $post[ 'reply' ] ) {
    ?>
  <script>
setTimeout(function () {
  scrollOn("post-<?php echo $id; ?>");
}, 500);
</script>
  <?php
  }
  echo getPost( $id, $_REQUEST[ 'history' ], true, true, false, $post[ 'thread' ], true );
  if ( $post ) {
    ?>
  <script>
var weeks = 1;
$(function () {
  content(".posts-container","feed","type=replies&post=<?php echo $id; ?>&weeks=1");
  content_id = '<?php echo $id; ?>';
});
</script> 
</div>
<div class="feed-container">
  <div class="new-post-container <?php echo $notallowed; ?>">
    <div class="new-post-grid">
      <div class="navigation-profile" data-background="/images/avatar/<?php echo $user_array['username']; ?>"></div>
      <textarea placeholder="Post a reply..." class="reply-btn" data-id="<?php echo $id; ?>"></textarea>
    </div>
  </div>
  <div class="posts-container"></div>
</div>
<?php
}
?>
