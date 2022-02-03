<?php
require( "../global.php" );
if ( $page )$active[ $page ] = 'class="active"';
else $active[ "home" ] = 'class="active"';
if ( !$page )header( "Location: /mod/home" );
?>
<!doctype html>
<html>
<head>
<title>Mods</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" defer></script> 
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script> 
<script type="text/javascript" src="../js/index.js"></script> 
<script type="text/javascript" src="/mods/main.js"></script>
<link rel="stylesheet" href="../css/index.css">
<link rel="stylesheet" href="/mods/styles.css">
</head>
<body>
<div class='alert-feed'></div>
<div class="navigation-container">
  <div class="navigation">
    <div class="site-progress"></div>
    <a href="/">
    <div class="navigation-logo"></div>
    </a></div>
</div>
<section class="mod-container">
  <div class="mod-side-container">
    <ul>
      <div class="navigation-label">Main</div>
      <li><a href="home" <?php echo $active['home']; ?>>Home</a></li>
      <li><a href="faq" <?php echo $active['faq']; ?>>FAQ</a></li>
      <div class="divider"></div>
      <div class="navigation-label">Flagged/Reported Content</div>
      <li><a href="posts" <?php echo $active['posts']; ?>>Posts</a></li>
      <li><a href="threads" <?php echo $active['threads']; ?>>Threads</a></li>
      <li><a href="users" <?php echo $active['users']; ?>>Users</a></li>
    </ul>
  </div>
  <div class="mod-content-container">
    <?php
    $page = $_GET[ 'page' ];
    if ( $page ) include( "pages/$page.php" );
    else include( "pages/home.php" );
    ?>
  </div>
</section>
</body>
</html>
