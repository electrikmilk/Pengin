<div class="content-block">
  <h2>Flagged Threads</h2>
  <p>Threads are currently flagged for profanity or if a user reports a thread to us.</p>
</div>
<hr/>
<?php
$flagged_posts = mysqli_query( $connect, "select * from data.flags where type = 'thread' order by timestamp desc" );
if ( mysqli_num_rows( $flagged_posts ) !== 0 ) {
  echo "<div class='content-list'>";
  while ( $item = mysqli_fetch_array( $flagged_posts ) ) {
    $post = dataArray( "posts", $item[ 'content_id' ], "id" );
    $reporter = dataArray( "users", $item[ 'reporter' ], "id" );
    if ( $item[ 'reporter' ] )$source = "Reported by <a href='/@{$reporter['username']}' target='_blank'>@{$reporter['username']}</a>";
    else $source = "Flagged by system";
    if ( $post ) {
      $user = dataArray( "users", $post[ 'author' ], "id" );
      echo "<div class='list-item'><h3>$source</h3><div class='repost-container'>" . getPost( $item[ 'content_id' ], false, false, false, true, false, true ) . "</div><p><a href='/@{$user['username']}' target='_blank'>@{$user['username']}</a> &bull; <a href='/post/{$item['content_id']}' target='_blank'>Thread Permalink</a> &bull; Reported " . timeago( $item[ 'timestamp' ], true ) . " ago<strong></strong></p></div>";
    } else echo "<div class='list-item'><h3>$source</h3><p>[redacted post]</p></div>";
  }
  echo "</div>";
} else echo "<div class='content-block disabled'>No threads are currently flagged for moderation.</div>";
