<?php
$query = e( trim( strip_tags( htmlspecialchars( $_REQUEST[ 'query' ] ) ) ) );
$autocomplete = mysqli_query( $connect, "select * from data.searches where query like '%$query%' or final like '%$query%' order by timestamp asc" );
while ( $complete = mysqli_fetch_array( $autocomplete ) ) {
  $search = $complete[ 'query' ];
  $completethis = str_ireplace( $query, "<strong>$query</strong>", $complete[ 'query' ] ); // bold matching text
  $results .= "<div class='quick-search-item' data-action='search' data-args='query=$search' data-url='/search/$search'>$completethis</div>";
}
$users = mysqli_query( $connect, "select * from data.users where displayname like '%$query%' or username like '%$query%' order by displayname desc" );
while ( $user = mysqli_fetch_array( $users ) ) {
  $username = $user[ 'username' ];
  $completethis = str_ireplace( $query, "<strong>$query</strong>", $username );
  $results .= "<div class='quick-search-item' data-action='profile' data-args='user=$username' data-url='/@$username'>@$completethis</div>";
}
$ttags = mysqli_query( $connect, "select * from data.trending where tag like '%$query%' order by timestamp desc" );
while ( $tag = mysqli_fetch_array( $ttags ) ) {
  $thistag = $tag[ 'tag' ];
  $completethis = str_ireplace( $query, "<strong>$query</strong>", $thistag );
  $results .= "<div class='quick-search-item' data-action='search' data-args='query=$thistag' data-url='hashtag/$thistag'>#$completethis</div>";
}
echo $results . "<div class='quick-search-item quick-search-all' data-action='search' data-args='query=$query' data-url='search/$query'>" . t( 'all-results' ) . "</div>";