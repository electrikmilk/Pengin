<?php
include_once( "minifier.php" );
$js = array(
  "../js/index.js" => "../js/index.min.js"
);
$css = array(
  "../css/index.css" => "../css/index.min.css"
);
minifyJS( $js );
minifyCSS( $css );