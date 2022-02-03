<?php
if ( $dev )$list = json_decode( file_get_contents( "translations/list.json" ), true );
else $list = json_decode( file_get_contents( "translations/list2.json" ), true );
ksort( $list );
foreach ( $list as $item => $details ) {
  unset( $selected );
  $desc = $details[ 'description' ];
  $code = $details[ 'code' ];
  if ( $user_lang === $code )$selected = " selected-lang";
  echo "<div class='lang-item flag flag-$code$selected' data-code='$code'><div><h3>$item</h3><p>$desc</p></div></div>";
}