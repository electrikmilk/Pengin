<div class="content-block">
  <h2>Flagged Users</h2>
  <p>Users are currently flagged if a user reports a post to us. This needs to be changed, the system should pick up on when a user doesn't confirm their email address.</p>
</div>
<hr/>
<?php
$flagged_posts = mysqli_query( $connect, "select * from data.flags where type = 'user' order by timestamp desc" );
if ( mysqli_num_rows( $flagged_posts ) !== 0 ) {
  echo "<div class='content-list'>";
  while ( $item = mysqli_fetch_array( $flagged_posts ) ) {
    $user = getUser( $item[ 'content_id' ] );
    $account = dataArray( "users", $item[ 'content_id' ], "id" );
    $reporter = dataArray( "users", $item[ 'reporter' ], "id" );
    if ( $item[ 'reporter' ] )$source = "Reported by <a href='/@{$reporter['username']}' target='_blank'>@{$reporter['username']}</a>";
    else $source = "Flagged by system";
    if ( $user ) {
      echo "<div class='list-item'><h3>$source</h3>$user<p><a href='/@{$account['username']}' target='_blank'>Permalink</a> &bull; Reported " . timeago( $item[ 'timestamp' ], true ) . " ago</p></div>";
    } else echo "<div class='list-item'><h3>$source</h3><p>[redacted user]</p></div>";
  }
  echo "</div>";
} else echo "No users are currently flagged for moderation.";
