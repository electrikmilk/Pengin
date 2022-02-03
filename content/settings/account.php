<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="content-block">
  <h2><?php echo t("Your Account"); ?></h2>
  <p class="input-context"><?php echo t("These fields are autosaved."); ?></p>
</div>
<hr/>
<?php

function getColumn( $column ) {
  if ( $column === "bio" ) {
    $relabel = "About You";
  } else if ( $column === "email" ) {
    $relabel = "Email Address";
  } else if ( $column === "username" ) {
    $relabel = "Username";
  } else if ( $column === "displayname" ) {
    $relabel = "Your Name";
  } else if ( $column === "link" ) {
    $relabel = "Link";
  } else if ( $column === "location" ) {
    $relabel = "Location";
  } else if ( $column === "profanity" ) {
    $relabel = "Profanity Filter";
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
$result = mysqli_query( $connect, "select * from data.users where id = '$user_session'" );
if ( mysqli_num_rows( $result ) > 0 ) {
  while ( $row = mysqli_fetch_assoc( $result ) ) {
    foreach ( $row as $col => $val ) {
      if ( $val === "undefined" )$val = "";
      $column = getColumn( $col );
      if ( $column !== false ) {
        ?>
<div class="edit-column-container" id="column-<?php echo $col; ?>">
  <div class="edit-column-label">
    <?php
    echo getColumn( $col );
    if ( $col === "email" || $col === "username" || $col === "displayname" )echo " <span class='red'>*</span>";
    if ( $col === "email" ) {
      echo "<div class='input-context field-context'>" . t( "Not publically shown" ) . "</div>";
    } else if ( $col === "displayname" ) {
      echo "<div class='input-context field-context'>" . t( 'displayname-description' ) . "</div>";
    }
    ?>
  </div>
  <div class="edit-column-value">
    <div class="edit-column-change" id="change-<?php echo $col; ?>">
      <form onsubmit="event.preventDefault(),saveEdit('<?php echo $col; ?>');">
        <?php
        if ( $col === "extra" ) {
          $placeholder = getColumn( $col );
        } else if ( $col === "bio" ) {
          $placeholder = t( "A little bit about you..." );
        } else {
          $placeholder = getColumn( $col );
        }
        if ( $col === "bio" ) {
          ?>
        <textarea placeholder="<?php echo $placeholder; ?>" id="edit-val-<?php echo $col; ?>"  onchange="saveEdit('<?php echo $col; ?>',true);" data-limit="500"><?php echo $val; ?></textarea>
        <p class="input-context" id="limit-edit-val-bio">0 / 500</p>
        <?php
        } else if ( $col === "displayname" ) {
          ?>
        <input type="text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $val; ?>" id="edit-val-<?php echo $col; ?>" data-limit="50" onchange="saveEdit('<?php echo $col; ?>',true);"/>
        <p class="input-context" id="limit-edit-val-displayname">0 / 50</p>
        <?php
        } else if ( $col === "username" ) {
          ?>
        <input type="text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $val; ?>" id="edit-val-<?php echo $col; ?>" data-limit="20" onchange="saveEdit('<?php echo $col; ?>',true);"/>
        <p class="input-context" id="limit-edit-val-username">0 / 20</p>
        <?php
        } else if ( $col === "profanity" ) {
          if ( $val === "0" )$all = "selected";
          else if ( $val === "1" )$mod = "selected";
          else if ( $val === "2" )$strict = "selected";
          else if ( $val === "3" )$verystrict = "selected";
          ?>
        <h3 id="filter-title">...</h3>
        <p id="filter-desc">...</p>
        <input type="range" value="<?php echo $val; ?>" id="edit-val-<?php echo $col; ?>" onchange="filter(),saveEdit('<?php echo $col; ?>',true);" min="0" max="3"/>
        <script>
        $(function () {
            filter();
        });
        </script>
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
?>
<div class="edit-column-container" id="column-birthday">
  <div class="edit-column-label"><?php echo t("Birthday"); ?></div>
  <div class="edit-column-value">
    <div class="column-static" id="column-value-birthday">
      <div><?php echo date("F j, Y",strtotime($user_array['birthday']))." ($user_age ".t('years old').")."; ?> <a href='javascript:;' data-action='settings/birthday'>Change</a></div>
      <br/>
      <div class="input-context">You can only change your birthday once.</div>
    </div>
  </div>
</div>
