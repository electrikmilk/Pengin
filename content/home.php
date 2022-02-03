<?php
if ( stripos( $user_lang, "en" ) !== false )$placeholders = array( "Hey, what's up?", "Say what you need to say...", "What's going on?", "Words written here go places..." );
else $placeholders = array( t( 'new-post-prompt' ) );
$placeholder = array_random( $placeholders );
?>
<div class="feed-container">
  <div class="new-post-container <?php echo $notallowed; ?>">
    <div class="new-post-grid">
      <div class="navigation-profile" data-background="/images/avatar/<?php echo $user_array['username']; ?>"></div>
      <textarea placeholder="<?php echo $placeholder; ?>" data-modal="new-post"></textarea>
    </div>
  </div>
  <div class="posts-container"></div>
</div>
