<?php
include( "global.php" );
if ( $user_session !== "4" )$min = "min.";
?>
<!doctype html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="refresh">
<meta name="msapplication-tap-highlight" content="no"/>
<meta content='en_US' property='og:locale'>
<meta content='website' property='og:type'>
<meta content='Pengin' property='og:site_name'>
<meta name="theme-color" content="#F4B34D"/>
<meta name='robots' content='noodp,noydir'>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0"/>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
<link rel="manifest" href="/favicon/site.webmanifest">
<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#F4B34D">
<meta name="msapplication-TileColor" content="#F4B34D">
<meta name="msapplication-config" content="/favicon/browserconfig.xml">
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-title" content="Pengin" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="theme-color" content="#ffffff" />
<?php include("metadata.php"); ?>

<link rel="stylesheet" href="/css/index.<?php echo $min; ?>css?refresh=<?php echo uniqid(); ?>"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script> 
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script>
<?php
if ( $page === "signup" || $page === "login" || $page === "forgot" )echo "<script type='text/javascript' src='https://www.google.com/recaptcha/api.js' defer></script>";
if ( $user_session )echo "\n<script type='text/javascript' src='/js/emoji.js'></script>\n";
?>
<script type="text/javascript" src="/translations/data"></script> 
<script type="text/javascript" src="/js/index.<?php echo $min; ?>js?refresh=<?php echo uniqid(); ?>" defer></script>
</head><body class="hinder <?php echo $browser; ?>">
<?php
//if ( $user_session && !$_COOKIE[ 'back' ] ) {
//  $preloaders = json_decode( file_get_contents( "preloaders.json" ), true );
//  $message = array_random( $preloaders[ 'message' ] );
//  echo "<div class='preload'></div>"; //<div><p>$preload</p><div class='load'></div></div>
//}
if ( $user_session )echo "<div class='alert-feed'></div><div class='new-post' data-dropdown='new-menu' tooltip='" . t( 'Create' ) . "' data-placement='top'>New Post</div>";
if ( $page === "create-thread" ) {
  ?>
<script>
confirmunload = true;
window.onbeforeunload = function() {
  return true;
};
//setTimeout(function () {
//    $(".preload > div").html("Loading seems to be taking longer than usual. Please refresh the page or contact us if this persists.");
//}, 10000);
</script>
<?php
}
if ( $user_session ) include( "content/menus.php" );
include( "content/modals.php" );
include( "content/navigation.php" );
if ( !$user_session ) {
  $allowed = array( "login", "signup", "forgot", "cookies", "privacy", "terms" );
  if ( in_array( $page, $allowed ) ) {
    if ( !$folder ) include( "content/$page.php" );
    else include( "content/$folder/$page.php" );
  } else require( "content/soon.php" ); // splash.php
} else {
  if ( $page === "login" || $page === "signup" ) {
    header( "Location: /" );
    ?>
<script>
window.location = "/";
</script>
<?php
} else {
  ?>
<section class="content-container">
  <section class="page-container">
    <?php
    echo "<section class='left-container'>";
    if ( !$page ) include( "content/home.php" );
    else if ( $page ) {
      if ( !$folder ) include( "content/$page.php" );
      else include( "content/$folder/$page.php" );
    }
    echo "</section>";
    if ( $user_session && !$mobilenotipad ) {
      echo "<section class='right-container'>";
      include( "content/side.php" );
      echo "</section>";
    }
    ?>
  </section>
</section>
<?php
}
}
?>
</body>
</html>
