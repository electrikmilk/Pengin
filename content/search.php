<?php
if ( $_REQUEST[ 'hashtag' ] )$hashtag = "#";
$query = trim( strip_tags( htmlspecialchars( $hashtag . $_REQUEST[ 'query' ] ) ) );
?>
<div class="content-block">
  <h2><?php echo $query; ?></h2>
  <p><?php echo t("Results for this search"); ?></p>
</div>
<div class="search-results-container">
  <div class="inline-loading">
    <div class="load"></div>
  </div>
</div>
<script>
$(function () {
  content(".search-results-container","results","query=<?php echo $query; ?>");
});
</script> 
