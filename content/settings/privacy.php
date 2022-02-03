<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="content-block">
  <h2><?php echo t("Privacy Settings"); ?></h2>
  <div class="privacy-message"><?php echo t("shield-intro"); ?></div>
  <br/>
  <p><?php echo t("privacy-description"); ?></p>
  <br/>
  <p><?php echo t("privacy-details"); ?> <a href='javascript:;' data-action='about/privacy'><?php echo t("Privacy Policy"); ?></a>.</p>
  <br/>
  <p><?php echo t("improve-privacy"); ?> <a href='javascript:;' data-action='about/contact'><?php echo t('please let us know'); ?></a>.</p>
  <br/>
  <p class="input-context"><?php echo t("These fields are autosaved."); ?></p>
</div>
<hr/>
<?php

function getColumn( $column ) {
  if ( $column === "canfollow" ) {
    $relabel = "Anyone can follow you";
  } else if ( $column === "canmessage" ) {
    $relabel = "Strangers can message you";
  } else if ( $column === "followmessage" ) {
    $relabel = "Followers can message you";
  } else if ( $column === "mutualposts" ) {
    $relabel = "Occasional posts in your feed from mutuals";
  } else if ( $column === "likedposts" ) {
    $relabel = "See posts in your feed liked by people you follow?";
  } else if ( $column === "private" ) {
    $relabel = "By default, are posts on your profile private to you and your followers only?";
  } else if ( $column === "seereplies" ) {
    $relabel = "Should your replies outside of threads be public?";
  } else if ( $column === "showdetails" ) {
    $relabel = "To strangers only show these details";
  } else if ( $column === "showfollows" ) {
    $relabel = "Show who follows you?";
  } else if ( $column === "showfollowing" ) {
    $relabel = "Show who you follow?";
  } else if ( $column === "showyear" ) {
    $relabel = "When showing your birthday should we show the year?";
  }
  if ( $relabel ) {
    return t( $relabel );
  } else {
    return false;
  }
}

function handleVal( $column, $value ) {
  if ( !$value || $value === "undefined" ) {
    echo "<span class='input-context'>Nothing here yet...</span>";
  } else {
    if ( strpos( $value, "@" ) !== false ) {
      return $value;
    } else {
      if ( $column === "extraurl" || $column === "des" ) {
        return nl2br( linkify( $value ) );
      } else if ( $column === "user_name" ) {
        return "<a href='https://pengin.app/@$value' target='_blank'>pengin.app/@$value</a>";
      } else {
        if ( $value = "0" )$value = "Yes";
        if ( $value = "1" )$value = "No";
        return $value;
      }
    }
  }
}
$privacy_fields = array( "canfollow", "canmessage", "followmessage", "mutualposts", "likedposts", "private", "showfollows", "showfollowing", "showyear" );
$result = mysqli_query( $connect, "select * from data.users where id = '$user_session'" );
if ( mysqli_num_rows( $result ) > 0 ) {
  while ( $row = mysqli_fetch_assoc( $result ) ) {
    foreach ( $row as $col => $val ) {
      if ( $val === "undefined" )$val = "";
      $column = getColumn( $col );
      if ( $column !== false ) {
        ?>
<div class="edit-column-container" id="column-<?php echo $col; ?>">
  <div class="edit-column-label"><?php echo getColumn( $col ); ?></div>
  <div class="edit-column-value">
    <?php
    if ( $col === "private" ) {
      $context = "Everytime you post to your profile, you can set your new post as private or public. Any of your posts or replies within public discussion threads will still be public.";
    } else if ( $col === "canfollow" ) {
      $context = "Anyone on the platform can follow you.";
    } else if ( $col === "followmessage" ) {
      $context = "People who follow you can message you.";
    } else if ( $col === "canmessage" ) {
      $context = "People who do not follow you can message you.";
    } else if ( $col === "mutualposts" ) {
      $context = "See posts from people that you follow are following.";
    } else if ( $col === "seereplies" ) {
      $context = "Your followers will still be able to see your replies.";
    } else if ( $col === "showdetails" ) {
      $context = "People who don't follow you will only see the details you check below.";
    } else if ( $col === "showfollows" ) {
      $context = "Can anyone view a list of who follows you?";
    } else if ( $col === "showfollowing" ) {
      $context = "Can anyone view a list of who you follow?";
    }
    echo "<div class='input-context field-context'>" . t( $context ) . "</div>";
    ?>
    <div class="edit-column-change" id="change-<?php echo $col; ?>">
      <form onsubmit="event.preventDefault(),saveEdit('<?php echo $col; ?>');">
        <?php
        if ( $col === "extra" ) {
          $placeholder = getColumn( $col );
        } else if ( $col === "bio" ) {
          $placeholder = t( "A little bit about you..." );
        } else {
          $placeholder = t( 'Your' ) . " " . getColumn( $col );
        }
        if ( $col === "bio" ) {
          ?>
        <textarea placeholder="<?php echo $placeholder; ?>" id="edit-val-<?php echo $col; ?>"  onchange="saveEdit('<?php echo $col; ?>',true);" data-limit="500"><?php echo $val; ?></textarea>
        <p class="input-context" id="limit-edit-val-bio">0 / 500 characters</p>
        <?php
        } else if ( $col === "showdetails" ) {
          if ( strpos( $val, "avatar" ) !== false )$showavatar = "checked";
          if ( strpos( $val, "header" ) !== false )$showheader = "checked";
          if ( strpos( $val, "bio" ) !== false )$showbio = "checked";
          if ( strpos( $val, "location" ) !== false )$showlocation = "checked";
          if ( strpos( $val, "joined" ) !== false )$showjoined = "checked";
          if ( strpos( $val, "birthday" ) !== false )$showbirthday = "checked";
          if ( strpos( $val, "followers" ) !== false )$showfollowers = "checked";
          if ( strpos( $val, "following" ) !== false )$showfollowing = "checked";
          ?>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showavatar" value="avatar" <?php echo $showavatar; ?>/>
          <label for="showavatar"><?php echo t("Profile picture"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showheader" value="header" <?php echo $showheader; ?>/>
          <label for="showheader"><?php echo t("Header"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showbio" value="bio" <?php echo $showbio; ?>/>
          <label for="showbio"><?php echo t("Bio"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showlocation" value="location" <?php echo $showlocation; ?>/>
          <label for="showlocation"><?php echo t("Set Location"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showjoined" value="joined" <?php echo $showjoined; ?>/>
          <label for="showjoined"><?php echo t("When you joined"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showbirthday" value="birthday" <?php echo $showbirthday; ?>/>
          <label for="showbirthday"><?php echo t("Birthday (month & day)"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showfollowers" value="followers" <?php echo $showfollowers; ?>/>
          <label for="showfollowers"><?php echo t("Followers Count"); ?></label>
        </div>
        <div class="checkbox">
          <input type="checkbox" name="showdetails" id="showfollowing" value="following" <?php echo $showfollowing; ?>/>
          <label for="showfollowing"><?php echo t("Following Count"); ?></label>
        </div>
        <input type="hidden" id="edit-val-<?php echo $col; ?>" value="<?php echo $val; ?>"/>
        <?php
        } else if ( in_array( $privacy_fields, $col ) !== false ) {
          unset( $oneON );
          unset( $zeroON );
          unset( $twoON );
          if ( $val === "0" )$oneON = "selected";
          ?>
        <select id="edit-val-<?php echo $col; ?>" onchange="saveEdit('<?php echo $col; ?>',true);">
          <option value="0" <?php echo $oneON; ?>><?php echo t("Yes"); ?></option>
          <?php
          if ( $col !== "canfollow" ) {
            if ( $val === "1" )$zeroON = "selected";
            ?>
          <option value="1" <?php echo $zeroON; ?>><?php echo t("No"); ?></option>
          <?php
          }
          if ( $col === "canfollow" || $col === "canmessage" || $col === "followmessage" || $col === "showyear" || $col === "showfollows" || $col === "showfollowing" ) {
            if ( $val === "2" )$twoON = "selected";
            if ( $col !== "showyear" && $col !== "showfollows" && $col !== "showfollowing" ) {
              ?>
          <option value="2" <?php echo $twoON; ?>><?php echo t("Only by request"); ?></option>
          <?php
          } else if ( $col === "showyear" || $col === "showfollows" || $col === "showfollowing" ) {
            ?>
          <option value="2" <?php echo $twoON; ?>><?php echo t("If they follow me"); ?></option>
          <?php
          }
          }
          ?>
        </select>
        <?php
        } else {
          ?>
        <input type="text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $val; ?>" id="edit-val-<?php echo $col; ?>" onchange="saveEdit('<?php echo $col; ?>',true);"/>
        <?php
        }
        ?>
      </form>
    </div>
  </div>
</div>
<?php
}
}
}
}
