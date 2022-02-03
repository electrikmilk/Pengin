<div class="back-link" data-action="discuss"><?php echo t( "Back to Topics" ); ?></div>
<div class="content-block">
  <button type="submit" class="create-thread-btn" data-action="create-thread" data-args="topic=<?php echo $_REQUEST['topic']; ?>" data-url="/create-thread/<?php echo $_REQUEST['topic']; ?>" style="float: right;"><?php echo t( "Create Thread" ); ?></button>
  <?php
  $topic = $_REQUEST[ 'topic' ];
  $topics = dataArray( "topics", $topic, "url" );
  if ( $topics ) {
    $id = $topics[ 'id' ];
    $topic_title = t( $topics[ 'title' ] );
    echo "<h2>$topic_title</h2><p>" . $topics[ 'description' ] . "</p>";
    ?>
</div>
<?php if($topic === "staff")echo "<div class='message warning inline-message'>Any threads regarding account issues or security vulnerabilities will be immediately deleted, please contact us directly instead.</div>"; ?>
<script>
$(function () {
  content(".threads-container","threads","type=topic&topic=<?php echo $id; ?>");
});
</script>
<div class="threads-container"></div>
<?php
} else echo "<div class='empty-state-message'><h3>It's a mystery...</h3><p>Uh... I could of sworn that topic were around here<br/>somewhere...it doesn't appear to exist!</p><button type='submit' onclick='load(&quot;discuss&quot;);'>" . t( "Back to Topics" ) . "</button></div>";
